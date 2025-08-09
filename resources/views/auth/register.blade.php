@extends('components.default')

@section('title', 'Create your new account!')

@section('nav')
<header class="shadow-lg">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4 py-2">
        <a class="navbar-brand d-flex" href="{{ route('home') }}">
            <span class="fs-2 text-primary">Loomi</span>
        </a>
    </nav>
</header>
@endsection

@section('content')
<div class="row align-items-center g-lg-5 py-5">
    <div class="col-lg-7 text-center text-lg-start">
        <h1 class="display-4 fw-bold lh-1 text-primary mb-3">
            <i class="bi bi-shop display-1"></i> Join Loomi, Where Convenience Meets Choice.
        </h1>
        <p class="lead col-lg-10 fs-4">
            Create your account today and explore thousands of products at unbeatable prices.
        </p>
    </div>
    <div class="col-md-10 mx-auto col-lg-5">
        <form action="{{ route('register') }}" method="POST" class="p-4 p-md-5 border rounded-3 bg-body-tertiary">
            @csrf
            @method("POST")
            <div class="form-floating mb-3">
                <input type="text" class="form-control" 
                    name="name" id="name" placeholder="name example" value="{{ old('name') }}" required>
                <label for="name">Name</label>
            </div>
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
            <div class="form-floating mb-3">
                <input type="password" class="form-control" name="password_confirmation" required
                    id="password_confirmation" placeholder="Confirm Password"  value="{{ old('password_confirmation') }}">
                <label for="password_confirmation">Confirm Password</label>
            </div>
            <div class="checkbox d-flex gap-2 pb-3">
                <input type="checkbox" name="remember-me" id="remember-me" value="{{ old('remember-me') }}">
                <label for="remember-me">Remember me</label>
            </div>
            <button type="submit" name="login" class="w-100 btn btn-primary text-light fw-bold">Sign up</button>
            <hr class="my-4">
            <small class="text-body-secondary">
                By clicking Sign up, you agree to the terms of use.
            </small>
            <hr class="my-4">
            <small class="text-body-secondary">
                Have an Account? <a href="{{ route('show.login') }}" class="text-info">Log in</a>
            </small>
        </form>
    </div>
</div>
@endsection

@section('footer')
@include('includes.footer')
@endsection