@extends('front.layouts.app')

@section('main')

<section class="section-5">
    <div class="container my-5">
        <div class="row d-flex justify-content-center">
             @if (Session::has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ Session::get('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (Session::has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ Session::get('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
            <div class="col-md-5">
                <div class="card shadow border-0 p-5">
                    <h1 class="h3">Register</h1>

                    <form action="{{ route('account.processRegistration') }}" id="registrationForm" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label>Name*</label>
                            <input type="text" name="name" id="name" class="form-control">
                            <p></p>
                        </div>

                        <div class="mb-3">
                            <label>Email*</label>
                            <input type="text" name="email" id="email" class="form-control">
                            <p></p>
                        </div>

                        <div class="mb-3">
                            <label>Password*</label>
                            <input type="password" name="password" id="password" class="form-control">
                            <p></p>
                        </div>

                        <div class="mb-3">
                            <label>Confirm Password*</label>
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                            <p></p>
                        </div>

                        <button class="btn btn-primary">Register</button>
                    </form>
                </div>
                <div class="mt-4 text-center">
                    <p>Have an account? <a  href="{{ route('account.login') }}">Login</a></p>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@section('customJS')
<script>

$('input').on('input', function() {
    $(this).removeClass('is-invalid')
        .next('p').removeClass('invalid-feedback').html('');
});

$('#registrationForm').submit(function(e) {
    e.preventDefault();

    $('input').removeClass('is-invalid');
    $('p').removeClass('invalid-feedback').html('');

    $.ajax({
        url: '{{ route("account.processRegistration") }}',
        type: 'post',
        data: $(this).serialize(),
        dataType: 'json',

        success: function(response) {
            if (response.status == true) {
                alert(response.message);
                window.location.href = "{{ route('account.login') }}";
            }
        },

        error: function(xhr) {
            if (xhr.status == 422) {
                var errors = xhr.responseJSON.errors;

                if (errors.name) {
                    $("#name").addClass('is-invalid')
                        .next('p').addClass('invalid-feedback').html(errors.name[0]);
                }

                if (errors.email) {
                    $("#email").addClass('is-invalid')
                        .next('p').addClass('invalid-feedback').html(errors.email[0]);
                }

                if (errors.password) {
                    $("#password, #password_confirmation").addClass('is-invalid');

                    $("#password").next('p')
                        .addClass('invalid-feedback').html(errors.password[0]);

                    $("#password_confirmation").next('p')
                        .addClass('invalid-feedback').html(errors.password[0]);
                }
            }
        }
    });
});
</script>
@endsection
