<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Models\Job;

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

        $jobTypeArr = [];
        if (request('job_type')) {
            $jobTypeArr = explode(',', request()->job_type);
            $jobs = $jobs->whereIn('job_type_id', $jobTypeArr);
        }
        
        if (request('sort') == 'oldest') {
            $jobs->orderBy('created_at', 'asc'); // Oldest
        } else {
            $jobs->orderBy('created_at', 'desc'); // Latest (default)
        }
        $jobs = $jobs->orderBy('created_at', 'desc')->with('jobType')->paginate(9);


        return view('front.jobs', compact('categories', 'jobTypes', 'jobs', 'jobTypeArr'));
    }

    public function details($id)
    {
        $job = Job::where('id', $id)->where('status', 1)->with('category', 'jobType')->firstOrFail();
        return view('front.job_details', compact('job'));
    }
}
