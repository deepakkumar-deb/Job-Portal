<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Job;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::where('status', 1)->orderBy('name', 'asc')->take(10)->get();

        $featuredJobs = Job::where('status', 1)->where('is_featured', 1)->orderBy('created_at', 'desc')->with('jobType')->take(6)->get();

        $latestJobs = Job::where('status', 1)->orderBy('created_at', 'desc')->with('jobType')->take(6)->get();
        return view('front.home', compact('categories', 'featuredJobs', 'latestJobs'));
    }
}
