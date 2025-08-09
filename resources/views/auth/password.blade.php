@extends('components.default')

@section('title', 'Reset your password')

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
<div class="py-5 d-flex justify-content-center">
    <div class="card shadow-sm p-4 w-100" style="max-width: 400px;">
        <h4 class="mb-3">Reset Your Password</h4>
        <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <div class="form-floating mb-3">
                <input type="password" class="form-control" 
                    name="password" id="password" placeholder="Password" value="{{ old('password') }}" required autofocus>
                <label for="password">New Password</label>
            </div>

            <div class="form-floating mb-3">
                <input type="password" class="form-control" 
                    name="password_confirmation" id="password_confirmation" placeholder="Confirm Password" 
                    value="{{ old('password_confirmation') }}" required autofocus>
                <label for="password_confirmation">Confirm Password</label>
            </div>

            <button type="submit" class="btn btn-primary text-white fw-bold w-100">Reset Password</button>
        </form>
    </div>
</div>
@endsection

@section('footer')
<footer class="text-center w-100 py-4 text-muted small">
    &copy; {{ now()->year }} Loomi. All rights reserved.
</footer>
@endsection