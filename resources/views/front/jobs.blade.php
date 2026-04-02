@extends('front.layouts.app')

@section('main')

<section class="section-3 py-5 bg-2 ">
    <div class="container">     
        <div class="row">
            <div class="col-6 col-md-10 ">
                <h2>Find Jobs</h2>  
            </div>
            <div class="col-6 col-md-2">
                <div class="align-end">
                    <label for="sort">Sort By:</label>
                    <select name="sort" id="sort" class="form-control">
                        <option name="sort" value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Latest</option>
                        <option name="sort" value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="row pt-5">

            <div class="col-md-4 col-lg-3 sidebar mb-4">
                <form action="" name="searchForm" id="searchForm">

                <div class="card border-0 shadow p-4">
                    <div class="mb-4">
                        <h2>Keywords</h2>
                    <input 
                        type="text" 
                        class="form-control" 
                        name="keywords" 
                        id="keywords" 
                        placeholder="Job Title / Keywords"
                        value="{{ request('keywords') }}"
                    >
                    </div>
                    <div class="mb-4">
                        <h2>Location</h2>
                        <input type="text" id="location" name="location" placeholder="Location" class="form-control" value="{{ request('location') }}">
                    </div>

                    <div class="mb-4">
                        <h2>Category</h2>
                        <select name="category" id="category" class="form-control">
                            <option value="">Select a Category</option>

                            @if ($categories->isNotEmpty())
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                @endforeach
                            @endif

                        </select>
                    </div>                   

                    <div class="mb-4">
                        <h2>Job Type</h2>
                        

                        @if ($jobTypes->isNotEmpty())
                            @foreach ($jobTypes as $jobType)
                                <div class="form-check mb-2"> 
                                    <input {{ in_array($jobType->id, $jobTypeArr) ? 'checked' : '' }} class="form-check-input " name="job_type" type="checkbox" value="{{ $jobType->id }}" id="job-Type-{{ $jobType->id }}">    
                                    <label class="form-check-label " for="job-Type-{{ $jobType->id }}">{{ $jobType->name }}</label>
                                </div>
                            @endforeach
                        @endif
                    </div>
                <button type="submit" class="btn btn-primary">Search</button>
                <button type="button" id="reset" class="btn btn-secondary mt-3">
                    Reset
                </button>
            </div>
                </form>
            </div>
            <div class="col-md-8 col-lg-9 ">
                <div class="job_listing_area">                    
                    <div class="job_lists">
                    <div class="row">
                        @if ($jobs->isNotEmpty())
                            @foreach ($jobs as $job)
                            <div class="col-md-4">
                                <div class="card border-0 p-3 shadow mb-4">
                                    <div class="card-body">
                                        <h3 class="border-0 fs-5 pb-2 mb-0">{{ $job->title }}</h3>
                                        <p>{{ $job->description }}</p>
                                        <div class="bg-light p-3 border">
                                            <p class="mb-0">
                                                <span class="fw-bolder"><i class="fa fa-map-marker"></i></span>
                                                <span class="ps-1">{{ $job->location }}</span>
                                            </p>
                                            <p class="mb-0">
                                                <span class="fw-bolder"><i class="fa fa-briefcase"></i></span>
                                                <span class="ps-1">{{ $job->category->name }}</span>
                                            </p>
                                            <p class="mb-0">
                                                <span class="fw-bolder"><i class="fa fa-clock-o"></i></span>
                                                <span class="ps-1">{{ $job->jobType->name }}</span>
                                            </p>
                                            <p class="mb-0">
                                                <span class="fw-bolder"><i class="fa fa-usd"></i></span>
                                                <span class="ps-1">{{ $job->salary }}</span>
                                            </p>
                                        </div>

                                        <div class="d-grid mt-3">
                                            <a href="{{ route('job.details', $job->id) }}" class="btn btn-primary btn-lg">Details</a>
                                    </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        @else
                            <div class="col">
                                <p class="text-center">No jobs found.</p>
                            </div>
                        @endif

                                                 
                    </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</section>
@endsection

@section('customJS')
    <script>
        $('#searchForm').on('submit', function(e) {
            e.preventDefault();
            
            var url = "{{ route('jobs') }}";
            var params = [];
            
            var location = $('#location').val();
            if(location != "") {
                params.push('location=' + encodeURIComponent(location));
            }
            
            var category = $('#category').val();
            if(category != "") {
                params.push('category=' + category);
            }
            
            var jobTypes = [];
            $('input[name="job_type"]:checked').each(function() {
                jobTypes.push($(this).val());
            });
            if(jobTypes.length > 0) {
                params.push('job_type=' + jobTypes.join(','));
            }
            
            if(params.length > 0) {
                url += '?' + params.join('&');
            }
            
            var $sort = $('#sort').val();
            if($sort != "") {
                url += (params.length > 0 ? '&' : '?') + 'sort=' + $sort;
            }
            window.location.href = url;
        });

        $('#reset').on('click', function() {
            window.location.href = "{{ route('jobs') }}";
        });
    </script>

@endsection