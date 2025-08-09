@extends('components.default')

@section('title', 'Log in your account')

@section('nav')
<header class="shadow-lg">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4 py-2">
        <a class="navbar-brand d-flex" href="{{ route('home') }}">
            <span class="fs-2 text-primary">Loomi</span>
        </a>
    </nav>
</header>
@endsection

@include('components.email')

@section('content')
<div class="row align-items-center g-lg-5 py-5">
    <div class="col-lg-7 text-center text-lg-start">
        <h1 class="display-4 fw-bold lh-1 text-primary mb-3">
            <i class="bi bi-cart display-1"></i> Welcome Back to Loomi.
        </h1>
        <p class="lead col-lg-10 fs-4">
            Log in to access your account, manage your orders, and continue your shopping experience with ease.
        </p>
    </div>
    <div class="col-md-10 mx-auto col-lg-5">
        <form action="{{ route('login') }}" method="POST" class="p-4 p-md-5 border rounded-3 bg-body-tertiary">
            @csrf
            @method("POST")
            <div class="form-floating mb-3">
                <input type="email" class="form-control" 
                    name="email" id="email" placeholder="name@example.com" value="{{ old('email') }}" required>
                <label for="email">Email address</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control" 
                    name="password" id="password" placeholder="Password" value="{{ old('password') }}" required>
                <label for="password">Password</label>
            </div>
            <div class="checkbox d-flex gap-2 pb-3">
                <input type="checkbox" name="remember-me" id="remember-me" value="{{ old('remember-me') }}">
                <label for="remember-me">Remember me</label>
            </div>
            <button type="submit" name="login" class="w-100 btn btn-primary text-light fw-bold">Log in</button>
            <div class="text-end pt-3">
                <p class="text-decoration-underline text-info" style="cursor: pointer;" id="forgot-link">Forgot Password</p>
            </div>
            <hr class="my-4">
            <small class="text-body-secondary">
                New to Loomi? <a href="{{ route('show.register') }}" class="text-info">Sign up</a>
            </small>
        </form>
    </div>
</div>
@endsection

@section('footer')
@include('includes.footer')
@endsection

@section('scripts')
<script>
    const forgotLink = document.getElementById("forgot-link");
    const cancelForgot = document.getElementById("cancel-forgot");
    const forgotForm = document.getElementById("forgot-password-form");

    if (forgotLink) {
        forgotLink.addEventListener("click", function (e) {
            forgotForm.classList.remove("d-none");
        });
    }

    if (cancelForgot) {
        cancelForgot.addEventListener("click", function () {
            forgotForm.classList.add("d-none");
        });
    }
</script>
@endsection