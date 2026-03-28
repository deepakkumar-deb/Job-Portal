<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Category;
use App\Models\JobType;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class AccountController extends Controller
{
    public function registration()
    {
        return view('front.account.registration');
    }

    public function processRegistration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
        ]);

        if ($validator->passes()) {

            $data = $request->only('name', 'email', 'password');
            $data['password'] = Hash::make($data['password']);

            User::create($data);

            return response()->json([
                'status' => true,
                'message' => 'Registration successful!'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }
    }

    public function login()
    {
        return view('front.account.login');
    }

    public function authenticate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if ($validator->passes()) {

            if (Auth::attempt($request->only('email', 'password'))) {
                return redirect()->route('account.profile');
            } else {
                return redirect()->route('account.login')
                    ->with('error', 'Either email or password is incorrect.')
                    ->withInput($request->only('email'));
            }
        } else {
            return redirect()->route('account.login')
                ->withErrors($validator)
                ->withInput($request->only('email'));
        }
    }
    public function profile()
    {
        $id = Auth::user()->id;

        // $user=User::where('id',$id)->first();

        $user = User::find($id);

        return view('front.account.profile', compact('user'));
    }


    public function updateProfile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|min:5|max:20',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'designation' => 'nullable|max:50',
            'mobile' => 'nullable|digits:10',
        ]);

        if ($validator->passes()) {
            $id = Auth::id();
            $user = User::find($id);

            $user->name = $request->name;
            $user->email = $request->email;
            $user->designation = $request->designation;
            $user->mobile = $request->mobile;

            if (!$user->isDirty()) {
                return response()->json([
                    'status' => false,
                    'message' => 'No changes detected',

                ]);
            }
            $user->save();

            // session()->flash('success', 'Profile updated successfully!');

            return response()->json([
                'status' => true,
                'message' => 'Profile updated successfully!',
                'user' => $user
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('account.login');
    }

    public function updateProfilePicture(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        if (!$request->hasFile('image')) {
            return response()->json([
                'status' => false,
                'message' => 'No file found'
            ], 400);
        }

        $imageFile = $request->file('image');

        $imageName = time() . '.' . $imageFile->getClientOriginalExtension();

        $user = Auth::user();
        if ($user->profile_picture) {
            $existingImagePath = public_path('profile_pictures/' . $user->profile_picture);
            $existingThumbPath = public_path('profile_pictures/thumb/' . $user->profile_picture);

            if (file_exists($existingImagePath)) {
                unlink($existingImagePath);
            }

            if (file_exists($existingThumbPath)) {
                unlink($existingThumbPath);
            }
        }

        $originalPath = public_path('profile_pictures/' . $imageName);
        $thumbPath = public_path('profile_pictures/thumb/' . $imageName);

        $manager = new ImageManager(new Driver());

        $image = $manager->read($imageFile->getPathname());
        $image->save($originalPath);


        $imageThumb = $manager->read($imageFile->getPathname());
        $imageThumb->cover(150, 150)->save($thumbPath);

        User::where('id', Auth::id())->update([
            'profile_picture' => $imageName
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Profile picture updated successfully!',
            'image_url' => asset('profile_pictures/' . $imageName)
        ]);
    }

    public function createJob()
    {
        $categories = Category::orderBy('name', 'ASC')->where('status', 1)->get();
        $job_types = JobType::orderBy('id', 'ASC')->where('status', 1)->get();
        return view('front.account.job.create_job', compact('categories', 'job_types'));
    }

    public function saveJob(Request $request)
    {
        // Validation and saving logic for the job posting will go here
        $rules = [
            'title' => 'required|string|max:255',
            'category' => 'required|exists:categories,id',
            'job_nature' => 'required|exists:job_types,id',
            'vacancy' => 'required|integer|min:1',
            'salary' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'description' => 'required|string',
            'company_name' => 'required|string|max:255',
            'company_location' => 'nullable|string|max:255',
            'website' => 'nullable|url'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->passes()) {

            Job::create([
                'title' => $request->title,
                'category_id' => $request->category,
                'job_type_id' => $request->job_nature,
                'vacancy' => $request->vacancy,
                'salary' => $request->salary,
                'location' => $request->location,
                'description' => $request->description,
                'qualifications' => $request->qualifications,
                'experience' => $request->experience,
                'company_name' => $request->company_name,
                'company_location' => $request->company_location,
                'company_website' => $request->website,
            ]);

            session()->flash('success', 'Job posted successfully!');

            return response()->json([
                'status' => true,
                'message' => 'Job posted successfully!'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }
    }

    public function myJobs()
    {
        // Logic to retrieve and display the user's job postings will go here
        return view('front.account.job.my_jobs');
    }
}
