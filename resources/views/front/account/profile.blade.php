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
                <!-- User Profile Form -->
                <div class="card border-0 shadow mb-4">
                    <form action="{{ route('account.updateProfile') }}" method="POST" id="userForm" name="userForm">
                        @csrf
                        <div class="card-body p-4">
                            <h3 class="fs-4 mb-1">My Profile</h3>
                            
                            <div class="mb-4">
                                <label for="name" class="mb-2">Name*</label>
                                <input type="text" name="name" id="name" placeholder="Enter Name" class="form-control" value="{{ $user->name }}">
                                <p></p>
                            </div>

                            <div class="mb-4">
                                <label for="email" class="mb-2">Email*</label>
                                <input type="text" name="email" id="email" placeholder="Enter Email" class="form-control" value="{{ $user->email }}">
                                <p></p>
                            </div>

                            <div class="mb-4">
                                <label for="designation" class="mb-2">Designation</label>
                                <input type="text" name="designation" id="designation" placeholder="Designation" class="form-control" value="{{ $user->designation }}">
                                    <p></p>
                            </div>

                            <div class="mb-4">
                                <label for="mobile" class="mb-2">Mobile</label>
                                <input type="text" name="mobile" id="mobile" placeholder="Mobile" class="form-control" value="{{ $user->mobile }}">
                                <p></p>
                            </div>
                        </div>

                        <div class="card-footer p-4">
                            <button type="submit" class="btn btn-primary">Update</button>
                        </div>
                    </form>
                </div>

                <!-- Change Password Form -->
                <div class="card border-0 shadow mb-4">
                    <form action="{{ route('account.changePassword') }}" method="POST" id="passwordForm" name="passwordForm">
                        @csrf
                    <div class="card-body p-4">
                        <h3 class="fs-4 mb-1">Change Password</h3>

                        <div class="mb-4">
                            <label for="old_password" class="mb-2">Old Password*</label>
                            <input type="password" name="old_password" id="old_password" placeholder="Old Password" class="form-control">
                            <p></p>
                        </div>

                        <div class="mb-4">
                            <label for="new_password" class="mb-2">New Password*</label>
                            <input type="password" name="new_password" id="new_password" placeholder="New Password" class="form-control">
                            <p></p>
                        </div>

                        <div class="mb-4">
                            <label for="new_password_confirmation" class="mb-2">Confirm Password*</label>
                            <input type="password" name="new_password_confirmation" id="new_password_confirmation" placeholder="Confirm Password" class="form-control">
                            <p></p>
                        </div>
                    </div>

                    <div class="card-footer p-4">
                        <button type="submit" class="btn btn-primary">Update</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('customJS')
<script type="text/javascript">
    $("#userForm").submit(function (e) {
            e.preventDefault();

            $('input').removeClass('is-invalid');
            $('#userForm p').removeClass('invalid-feedback').html('');

            $.ajax({
                url: "{{ route('account.updateProfile') }}",
                type: "POST",
                dataType: "json",
                data: $(this).serialize(),

                success: function (response) {
                    if (response.status) {
                        $('#msg-container').html(`
                            <div class="alert alert-success alert-dismissible fade show">
                                ${response.message}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        `);
                        
                        $("#user-name").text(response.user.name);
                        $("#user-designation").text(response.user.designation);
                    } else {
                        $('#msg-container').html(`
                            <div class="alert alert-danger alert-dismissible fade show">
                                ${response.message}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        `);
                    }
                },


                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;

                        if (errors.name) {
                            $("#name").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.name[0]);
                        }

                        if (errors.email) {
                            $("#email").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.email[0]);
                        }

                        if (errors.designation) {
                            $("#designation").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.designation[0]);
                        }

                        if (errors.mobile) {
                            $("#mobile").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.mobile[0]);
                        }
                    }
                }
            });
    });


    $("#passwordForm").submit(function (e) {
            e.preventDefault();

            $('input').removeClass('is-invalid');
            $('#passwordForm p').removeClass('invalid-feedback').html('');

            $.ajax({
                url: "{{ route('account.changePassword') }}",
                type: "POST",
                dataType: "json",
                data: $(this).serialize(),

                success: function (response) {
                    if (response.status) {
                        $('#msg-container').html(`
                            <div class="alert alert-success alert-dismissible fade show">
                                ${response.message}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        `);
                        
                        $('#passwordForm')[0].reset();
                    } else {
                        $('#msg-container').html(`
                            <div class="alert alert-danger alert-dismissible fade show">
                                ${response.message}
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        `);
                    }
                },


                error: function(xhr) {
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;

                        if (errors.old_password) {
                            $("#old_password").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.old_password[0]);
                        }
                        if (errors.new_password) {
                            $("#new_password").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.new_password[0]);
                        }
                        if (errors.new_password_confirmation) {
                            $("#new_password_confirmation").addClass('is-invalid')
                                .siblings('p')
                                .addClass('invalid-feedback')
                                .html(errors.new_password_confirmation[0]);
                        }
                    }
                }
            });
    }); 
</script>
@endsection