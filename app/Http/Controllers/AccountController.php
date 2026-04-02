<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\JobType;
use App\Models\SavedJobs;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

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
                'user_id' => Auth::user()->id,
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
        $jobs = Job::where('user_id', Auth::id())->with('jobType', 'category')->latest()->paginate(10);
        // dd($jobs);

        return view('front.account.job.my_jobs', compact('jobs'));
    }

    public function editJob(Request $request, $id)
    {

        $job = Job::where([
            'id' => $id,
            'user_id' => Auth::id()
        ])->firstOrFail();

        $categories = Category::orderBy('name', 'ASC')->where('status', 1)->get();
        $job_types = JobType::orderBy('id', 'ASC')->where('status', 1)->get();

        return view('front.account.job.edit_job', compact('job', 'categories', 'job_types'));
    }

    public function updateJob(Request $request, $id)
    {
        $job = Job::where([
            'id' => $id,
            'user_id' => Auth::id()
        ])->firstOrFail();

        // Validation and updating logic for the job posting will go here
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

            $job->update([
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

            return response()->json([
                'status' => true,
                'message' => 'Job updated successfully!'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }
    }

    public function deleteJob(Request $request)
    {
        $job = Job::where([
            'id' => $request->job_id,
            'user_id' => Auth::id()
        ])->firstOrFail();

        $job->delete();

        return response()->json([
            'status' => true,
            'message' => 'Job deleted successfully!'
        ]);
    }

    public function myJobApplications()
    {
        $applications = JobApplication::where('user_id', Auth::id())
            ->with(['job' => function ($q) {
                $q->withCount('applications as applicants_count')->with('jobType');
            }])
            ->latest()
            ->paginate(10);

        return view('front.account.job.my_applications', compact('applications'));
    }


    public function removeJobs(Request $request)
    {
        $jobApplication = JobApplication::where([
            'id'      => $request->application_id,
            'user_id' => Auth::id()
        ])->firstOrFail(); // throws 404 automatically if not found

        $jobApplication->delete();

        session()->flash('success', 'Job application removed successfully.');
        return response()->json(['status' => true]);
    }

    public function savedjobs()
    {
        $savedJobs = SavedJobs::where('user_id', Auth::id())
            ->with(['job' => function ($q) {
                $q->withCount('saved_jobs as saved_jobs_count')->with('jobType');
            }])
            ->latest()
            ->paginate(10);

        return view('front.account.job.saved_jobs', compact('savedJobs'));
    }

    public function removeSavedJob(Request $request)
    {
        $savedJob = SavedJobs::where([
            'id' => $request->job_id,
            'user_id' => Auth::id()
        ])->firstOrFail();

        $savedJob->delete();

        session()->flash('success', 'Saved job removed successfully.');
        return response()->json(['status' => true]);
    }

    public function changePassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        if ($validator->passes()) {
            $user = User::findOrFail(Auth::id());

            if (!Hash::check($request->old_password, $user->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Old password is incorrect'
                ], 400);
            }

            $user->password = Hash::make($request->new_password);
            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'Password changed successfully!'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }
    }
}
