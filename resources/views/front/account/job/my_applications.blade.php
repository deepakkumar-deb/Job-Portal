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
                                <h3 class="fs-4 mb-1">My Applications</h3>
                            </div>
                            
                        </div>

                        <div class="table-responsive">
                            <table class="table">
                                <thead class="bg-light">
                                    <tr>
                                        <th>Title</th>
                                        <th>Applied Date</th>
                                        <th>Applicants</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>

                                <tbody class="border-0">
                                    @if($applications->isNotEmpty())
                                        @foreach($applications as $application)
                                            <tr>
                                                <td>
                                                    <div class="job-name fw-500">{{ $application->job->title }}</div>
                                                    <div class="info1">{{ $application->job->jobType->name }}</div>
                                                </td>

                                                <td>{{ $application->applied_date->format('d M, Y') }}</td>

                                                <td>{{ $application->job->applicants_count ?? 0 }} Applications</td>

                                                <td>
                                                    @if($application->job->status == 1)
                                                        <div class="job-status text-capitalize">Active</div>
                                                    @else
                                                        <div class="job-status text-capitalize">Blocked</div>
                                                    @endif
                                                </td>

                                                <td>
                                                    <div class="action-dots float-end">
                                                        <button href="#" class="btn" data-bs-toggle="dropdown">
                                                            <i class="fa fa-ellipsis-v"></i>
                                                        </button>
                                                        <ul class="dropdown-menu dropdown-menu-end">
                                                            <li>
                                                                <a class="dropdown-item" href="{{ route('job.details', $application->job->id) }}">
                                                                     <i class="fa fa-eye"></i> View
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" href="#" onclick="event.preventDefault(); deleteApplication({{ $application->id }});">
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
                            {{ $applications->links() }}
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
    function deleteApplication(applicationId) {
        if (confirm('Are you sure you want to remove this application?')) {
            $.ajax({
                url: "{{ route('account.removeJobs') }}",
                type: "POST",
                data: {
                    application_id: applicationId,
                    _token: "{{ csrf_token() }}"
                },
                dataType: "json",
                success: function(response) {
                    window.location.href="{{ route('account.myJobApplications') }}";
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