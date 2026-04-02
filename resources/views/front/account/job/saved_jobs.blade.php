@extends('front.layouts.app')

@section('main')

<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Account Settings</li>
                    </ol>
                </nav>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-3">
                @include('front.account.sidebar')
            </div>

            <div class="col-lg-9">
                @include('front.layouts.message')

                <div class="card border-0 shadow mb-4 p-3">
                    <div class="card-body card-form">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h3 class="fs-4 mb-1">Saved Jobs</h3>
                            </div>
                            
                        </div>

                        <div class="table-responsive">
                            <table class="table">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Title</th>
                                        <th>Saved Date</th>
                                        <th>Applicants</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody class="border-0">
                                    @if($savedJobs->isNotEmpty())
                                        @foreach($savedJobs as $savedjob)
                                            <tr>
                                                <td>
                                                    <div class="job-name fw-500">{{ $savedjob->job->title }}</div>
                                                    <div class="info1">{{ $savedjob ->job->jobType->name }}.{{ $savedjob->job->location }}</div>
                                                </td>

                                                <td>{{\Carbon\Carbon::parse($savedjob->created_at)->format('d M, Y') }}</td>

                                                <td>{{ $savedjob->job->saved_jobs_count ?? 0 }} Applications</td>

                                                <td>
                                                    @if($savedjob->job->status == 1)
                                                        <div class="job-status text-capitalize">Active</div>
                                                    @else
                                                        <div class="job-status text-capitalize">Blocked</div>
                                                    @endif
                                                </td>

                                                <td>
                                                    <div class="action-dots">
                                                        <button href="#" class="btn" data-bs-toggle="dropdown">
                                                            <i class="fa fa-ellipsis-v"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('job.details', $savedjob->job->id) }}">
                                                                     <i class="fa fa-eye"></i> View
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); removeSavedJob({{ $savedjob->id }});">
                                                                     <i class="fa fa-trash"></i> Remove Application
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center">No applications found</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <div class="mt-3">
                            {{ $savedJobs->links() }}
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
    function removeSavedJob(jobId) {
        if (confirm('Are you sure you want to remove this saved job?')) {
            $.ajax({
                url: "{{ route('account.removeSavedJob') }}",
                type: "POST",
                data: {
                    job_id: jobId,
                    _token: "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(response) {
                    window.location.href="{{ route('account.savedJobs') }}";
                },
                error: function(xhr) {
                    $('#msg-container').html(`
                        <div class="alert alert-danger alert-dismissible fade show">
                            An error occurred while deleting the application.
                        </div>
                    `);
                }
            });
        }
    }
</script>
@endsection