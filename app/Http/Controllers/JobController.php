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
        $jobs= Job::where('status', 1);

        //search using location
        if(request()->has('location') && !empty(request()->location)){
            $jobs = $jobs->where('location', 'like', '%' . request()->location . '%');
        }
        //search using category
        if(request()->has('category') && !empty(request()->category)){
            $jobs = $jobs->where('category_id', request()->category);
        }
        //search using job type
        if(request()->has('job_type') && !empty(request()->job_type)){
            $jobs = $jobs->where('job_type_id', request()->job_type);
        }

        $jobs= $jobs->orderBy('created_at', 'desc')->with('jobType')->paginate(9);
        
        
        return view('front.jobs', compact('categories', 'jobTypes', 'jobs'));
    }
}
