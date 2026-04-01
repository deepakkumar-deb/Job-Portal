@extends('front.layouts.app')

@section('main')
<section class="section-4 bg-2">    
    <div class="container pt-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class=" rounded-3 p-3">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('jobs') }}"><i class="fa fa-arrow-left" aria-hidden="true"></i> &nbsp;Back to Jobs</a></li>
                    </ol>
                </nav>
            </div>
        </div> 
    </div>
    <div class="container job_details_area">
        <div class="row pb-5">
            <div class="col-md-8">

                @include('front.layouts.message')

                <div class="card shadow border-0">
                    <div class="job_details_header">
                        <div class="single_jobs white-bg d-flex justify-content-between">
                            <div class="jobs_left d-flex align-items-center">
                                
                                <div class="jobs_conetent">
                                    <a href="{{ route('job.details', $job->id) }}">
                                        <h4>{{ $job->title }}</h4>
                                    </a>
                                    <div class="links_locat d-flex align-items-center">
                                        <div class="location">
                                            <p> <i class="fa fa-map-marker"></i> {{ $job->location }}</p>
                                        </div>
                                        <div class="location">
                                            <p> <i class="fa fa-clock-o"></i> {{ $job->jobType->name }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="jobs_right">
                                <div class="apply_now">
                                    <a class="heart_mark" href="#"> <i class="fa fa-heart-o" aria-hidden="true"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="descript_wrap white-bg">
                        <div class="single_wrap">
                            <h4>Job description</h4>
                            {!! nl2br(e($job->description)) !!}

                        </div>
                        <div class="single_wrap">
                            @if(!empty($job->qualifications))
                            <h4>Qualifications</h4>
                            {!! nl2br(e($job->qualifications)) !!}
                            @endif
                        </div>
                        <div class="single_wrap">
                            @if(!empty($job->experience))
                            <h4>Experience</h4>
                            {!! nl2br(e($job->experience)) !!}
                            @endif
                        </div>
                        <div class="border-bottom"></div>
                        <div class="pt-3 text-end">
                            <a href="#" class="btn btn-secondary">Save</a>
                            @if(Auth::check())
                                <a href="javascript:void(0)" onclick="applyJob(event, {{ $job->id }})" class="btn btn-primary">Apply</a>
                            @else
                                <a href="{{ route('account.login') }}" class="btn btn-primary">Apply</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card shadow border-0">
                    <div class="job_sumary">
                        <div class="summery_header pb-1 pt-4">
                            <h3>Job Summary</h3>
                        </div>
                        <div class="job_content pt-3">
                            <ul>
                                <li>Published on: <span>{{ $job->created_at->format('d M, Y') }}</span></li>
                                <li>Vacancy: <span>{{ $job->vacancy }}</span></li>
                                <li>Salary: <span>{{ $job->salary }}</span></li>
                                <li>Location: <span>{{ $job->location }}</span></li>
                                <li>Job Nature: <span> {{ $job->jobType->name }}</span></li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="card shadow border-0 my-4">
                    <div class="job_sumary">
                        <div class="summery_header pb-1 pt-4">
                            <h3>Company Details</h3>
                        </div>
                        <div class="job_content pt-3">
                            <ul>
                                <li>Name: <span>{{ $job->company_name }}</span></li>
                                <li>Location: <span>{{ $job->company_location }}</span></li>
                                <li>
                                    Website: 
                                    <span>
                                        <a href="{{ Str::startsWith($job->company_website, ['http://', 'https://']) 
                                            ? $job->company_website 
                                            : 'https://' . $job->company_website }}" 
                                        target="_blank">
           
                                        {{ $job->company_website }}
                                        </a>
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('customJS')
<script type="text/javascript">
function applyJob(event, jobId) {

    event.preventDefault();

    if(confirm('Are you sure you want to apply for this job?')) {
        $.ajax({
            url: '{{ route("applyJob") }}',
            method: 'POST',
            data: {
                job_id:jobId,
                 _token: '{{ csrf_token() }}' },
            dataType: 'json',
            success: function(response) {
                if(response.status) {
                    alert(response.message);
                    window.location.href = "{{ route('account.myJobApplications') }}";
                } else {
                    alert(response.message);
                }
            },
            error: function(xhr) {
                console.log(xhr.responseText);
                alert('Server Error. Please try again later.');
            }
        });
    }
}
</script>
@endsection