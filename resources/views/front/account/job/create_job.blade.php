@extends('front.layouts.app')

@section('main')
<section class="section-5 bg-2">
    <div class="container py-5">
        <div class="row">
            <div class="col">
                <nav aria-label="breadcrumb" class="rounded-3 p-3 mb-4">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="#">Home</a></li>
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

                <form action="" method="POST" name="createJobForm" id="createJobForm">
                    <div class="card border-0 shadow mb-4">
                        <div class="card-body card-form p-4">
                            <h3 class="fs-4 mb-1">Job Details</h3>
                            
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="mb-2">Title<span class="req">*</span></label>
                                    <input type="text" placeholder="Job Title" id="title" name="title" class="form-control">
                                    <p></p>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="mb-2">Category<span class="req">*</span></label>
                                    <select name="category" id="category" class="form-control">
                                        <option value="">Select a Category</option>
                                        @if ($categories->isNotEmpty())
                                            @foreach ($categories as $category)
                                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                                            @endforeach
                                        @endif

                                    </select>
                                    <p></p>

                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="mb-2">Job Type<span class="req">*</span></label>
                                    <select name="job_nature" id="job_nature" class="form-control">

                                        <option value="">Select Job Type</option>
                                        @if ($job_types->isNotEmpty())
                                            @foreach ($job_types as $job_type)
                                                <option value="{{ $job_type->id }}">{{ $job_type->name }}</option>
                                            @endforeach
                                        @endif

                                    </select>
                                    <p></p>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="mb-2">Vacancy<span class="req">*</span></label>
                                    <input type="number" min="1" placeholder="Vacancy" id="vacancy" name="vacancy" class="form-control">
                                    <p></p>
                                </div>

                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="mb-2">Salary<span class="req">*</span></label>
                                    <input type="text" placeholder="Salary" id="salary" name="salary" class="form-control">
                                    <p></p>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="mb-2">Location<span class="req">*</span></label>
                                    <input type="text" placeholder="Location" id="location" name="location" class="form-control">
                                    <p></p>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="mb-2">Description<span class="req">*</span></label>
                                <textarea class="textarea" name="description" id="description" rows="5" placeholder="Description"></textarea>
                            </div>

                            <div class="mb-4">
                                <label class="mb-2">Qualifications</label>
                                <textarea class="textarea" name="qualifications" id="qualifications" rows="5" placeholder="Qualifications"></textarea>
                            </div>

                            <div class="mb-4">
                                <label class="mb-2">Experience</label>
                                <textarea class="textarea" name="experience" id="experience" rows="5" placeholder="Experience"></textarea>
                            </div>

                            <h3 class="fs-4 mb-1 mt-5 border-top pt-5">Company Details</h3>

                            <div class="row">
                                <div class="col-md-6 mb-4">
                                    <label class="mb-2">Name<span class="req">*</span></label>
                                    <input type="text" placeholder="Company Name" id="company_name" name="company_name" class="form-control">
                                    <p></p>
                                </div>
                                <div class="col-md-6 mb-4">
                                    <label class="mb-2">Location</label>
                                    <input type="text" placeholder="Location" id="company_location" name="company_location" class="form-control">
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="mb-2">Website</label>
                                <input type="text" placeholder="Website" id="website" name="website" class="form-control">
                                <p></p>
                            </div>
                        </div>

                        <div class="card-footer p-4">
                            <button type="submit" class="btn btn-primary">Save Job</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection

@section('customJS')
<script>
    $(document).ready(function () {

        $('input, select, textarea').on('input change', function () {
        $(this).removeClass('is-invalid');
        $(this).next('p.invalid-feedback').html('');
    });
        $("#createJobForm").submit(function (e) {
            e.preventDefault();

            $('input').removeClass('is-invalid');
            $('#createJobForm p').removeClass('invalid-feedback').html('');

            $('button[type="submit"]').prop('disabled', true);
            $.ajax({
                url: "{{ route('account.saveJob') }}",
                type: "POST",
                dataType: "json",
                data: $(this).serialize(),

                success: function (response) {
                    $('button[type="submit"]').prop('disabled', false);
                    if (response.status==true) {
                        $('#msg-container').html(`
                            <div class="alert alert-success alert-dismissible fade show">
                                ${response.message}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        `);            

                        window.location.href = "{{ route('account.myJobs') }}";
                    } else {
                        var errors = response.errors;

                        $('input, select, textarea').removeClass('is-invalid');
                        $('p.invalid-feedback').html('');

                        $.each(errors, function(key, value) {
                            let input = $('#' + key);

                            input.addClass('is-invalid');

                           input.siblings('p')
                                .addClass('invalid-feedback')
                                .html(value[0]);
                        });

                    }
                },
                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;

                        $('input, select, textarea').removeClass('is-invalid');
                        $('p.invalid-feedback').html('');

                        $.each(errors, function(key, value) {
    let input = $('#' + key);

    input.addClass('is-invalid');

    if (input.next('p').length === 0) {
        input.after('<p class="invalid-feedback"></p>');
    }

    input.next('p')
        .addClass('invalid-feedback')
        .html(value[0]);
});

                    }
                }
            });
        });
    });
</script>
@endsection
