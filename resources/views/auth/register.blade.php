@extends('components.default')

@section('title', 'Create your Loomi account')

@section('nav')
<header class="shadow-sm">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark px-4 py-2">
        <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('home') }}">
            
            <span class="fs-2 fw-bold text-primary">Loomi</span>
        </a>
    </nav>
</header>
@endsection

@include('components.email')

@section('content')
<div class="row align-items-center justify-content-center g-lg-5 py-5">
    <div class="col-lg-6 text-center text-lg-start mb-4 mb-lg-0">
        <h1 class="display-5 fw-bold lh-1 text-primary mb-4">
            <i class="bi bi-cart-check display-1"></i> Join Loomi Today
        </h1>
        <p class="lead fs-5 text-muted">
            Create your free account to start shopping, track your orders, and enjoy a seamless experience.
        </p>
    </div>

    <div class="col-md-10 col-lg-5">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-body p-4 p-md-5">

                {{-- Alerts --}}
                {{-- Error Alert --}}
                @if($errors->any())
                    <div class="alert alert-danger custom-alert alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-start me-3">
                            <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
                            <div>
                                <ul class="mb-0 ps-0">
                                    @foreach ($errors->all() as $error)
                                        <li class="small">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        <button type="button" class="btn-close ms-2" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Success Alert --}}
                @if(session('success'))
                    <div class="alert alert-success custom-alert alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center me-3">
                            <i class="bi bi-check-circle-fill fs-5 me-2"></i>
                            <div class="small text-break">{{ session('success') }}</div>
                        </div>
                        <button type="button" class="btn-close ms-2" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                {{-- Info Alert --}}
                @if(session('info'))
                    <div class="alert alert-info custom-alert alert-dismissible fade show" role="alert">
                        <div class="d-flex align-items-center me-3">
                            <i class="bi bi-info-circle-fill fs-5 me-2"></i>
                            <div class="small text-break">{{ session('info') }}</div>
                        </div>
                        <button type="button" class="btn-close ms-2" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <h2 class="h4 mb-4 fw-bold text-center text-dark">Sign Up</h2>

                <form action="{{ route('register') }}" method="POST">
                    @csrf
                    @method("POST")

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" 
                            name="name" id="name" placeholder="Your full name" 
                            value="{{ old('name') }}" required>
                        <label for="name">Full Name</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" 
                            name="email" id="email" placeholder="name@example.com" 
                            value="{{ old('email') }}" required>
                        <label for="email">Email address</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" 
                            name="password" id="password" placeholder="Password" required>
                        <label for="password">Password</label>
                    </div>

                    <div class="form-floating mb-3">
                        <input type="password" class="form-control" 
                            name="password_confirmation" id="password_confirmation" placeholder="Confirm Password" required>
                        <label for="password_confirmation">Confirm Password</label>
                    </div>

                    <button type="submit" class="w-100 btn btn-primary fw-bold py-2 mb-3">
                        <i class="bi bi-person-plus-fill me-1"></i> Create Account
                    </button>
                </form>

                <hr class="my-4">
                <p class="text-center small text-muted mb-0">
                    Already have an account? 
                    <a href="{{ route('show.login') }}" class="fw-semibold text-info">Log in here</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
@include('includes.footer')
@endsection
