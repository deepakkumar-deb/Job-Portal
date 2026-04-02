<?php

namespace App\Http\Controllers;

use App\Mail\JobNotificationEmail;
use App\Models\Category;
use App\Models\Job;
use App\Models\JobApplication;
use App\Models\SavedJobs;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;


class JobController extends Controller
{
    public function index()
    {
        $categories = Category::where('status', 1)->orderBy('name', 'asc')->take(10)->get();
        $jobTypes = Job::where('status', 1)->orderBy('id', 'asc')->with('jobType')->get()->pluck('jobType')->unique('id')->values();
        $jobs = Job::where('status', 1);

        //search using location
        if (request('location')) {
            $jobs = $jobs->where('location', 'like', '%' . request()->location . '%');
        }
        //search using category
        if (request('category')) {
            $jobs = $jobs->where('category_id', request()->category);
        }

        if (request()->filled('keywords')) {
            $jobs = $jobs->where('title', 'like', '%' . request()->keywords . '%');
        }

        $jobTypeArr = [];
        if (request('job_type')) {
            $jobTypeArr = explode(',', request()->job_type);
            $jobs = $jobs->whereIn('job_type_id', $jobTypeArr);
        }

        if (request('sort') == 'oldest') {
            $jobs = $jobs->orderBy('created_at', 'asc');
        } else {
            $jobs = $jobs->orderBy('created_at', 'desc');
        }
        $jobs = $jobs->with('jobType')->paginate(9);

        return view('front.jobs', compact('categories', 'jobTypes', 'jobs', 'jobTypeArr'));
    }

    public function details($id)
    {
        $job = Job::where('id', $id)->where('status', 1)->with('category', 'jobType')->firstOrFail();

        $applications = JobApplication::where('job_id', $id)->with('user')->get(['id', 'user_id', 'applied_date']);

        return view('front.job_details', compact('job', 'applications'));
    }

    public function applyJob(Request $request)
    {
        $id = $request->job_id;

        $job = Job::where('id', $id)->where('status', 1)->first();

        if (!$job) {
            $msg = 'Job does not exist';
            return response()->json([
                'status' => false,
                'message' => $msg
            ]);
        }

        // cannot apply to own job
        if ($job->user_id === Auth::id()) {
            return response()->json([
                'status' => false,
                'message' => 'You cannot apply for your own job'
            ]);
        }

        // ❗ check already applied (FIXED)
        $alreadyApplied = JobApplication::where([
            'job_id' => $id,
            'user_id' => Auth::id()
        ])->exists();

        if ($alreadyApplied) {
            return response()->json([
                'status' => false,
                'message' => 'You already applied for this job'
            ]);
        }

        // create new application
        JobApplication::create([
            'job_id' => $id,
            'user_id' => Auth::id(),
            'employer_id' => $job->user_id,
            'applied_date' => now()
        ]);

        //send email to employer about new application (optional)
        $employer = User::where('id', $job->user_id)->first();
        $maildata = [
            'employer' => $employer,
            'job' => $job,
            'user' => Auth::user(),
            'applicant_email' => Auth::user()->email,
        ];
        Mail::to($employer->email)->send(new JobNotificationEmail($maildata));

        return response()->json([
            'status' => true,
            'message' => 'Job application submitted successfully'
        ]);
    }

    public function saveJob(Request $request)
    {
        $id = $request->job_id;

        $job = Job::where('id', $id)->where('status', 1)->first();

        if (!$job) {
            $msg = 'Job does not exist';
            return response()->json([
                'status' => false,
                'message' => $msg
            ]);
        }

        // cannot save own job
        if ($job->user_id === Auth::id()) {
            return response()->json([
                'status' => false,
                'message' => 'You cannot save your own job'
            ]);
        }

        // check already saved
        $count = SavedJobs::where([
            'job_id' => $id,
            'user_id' => Auth::id()
        ])->count();

        if ($count) {
            return response()->json([
                'status' => false,
                'message' => 'You already saved this job'
            ]);
        }

        // save job to user's saved jobs
        $savedJob = new SavedJobs;
        $savedJob->create([
            'job_id' => $id,
            'user_id' => Auth::id()
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Job saved successfully'
        ]);
    }
}
