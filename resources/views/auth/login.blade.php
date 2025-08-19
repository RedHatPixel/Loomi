@extends('components.default')

@section('title', 'Log in your account')

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
            <i class="bi bi-cart-check display-1"></i> Welcome Back to Loomi
        </h1>
        <p class="lead fs-5 text-muted">
            Log in to access your account, manage your orders, and continue your shopping experience with ease.
        </p>
    </div>

    <div class="col-md-10 col-lg-5">
        <div class="card shadow-lg border-0 rounded-4">
            <div class="card-body p-4 p-md-5">
                <h2 class="h4 mb-4 fw-bold text-center text-dark">Log in</h2>
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    @method("POST")

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

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="remember" id="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>
                        {{-- <a href="javascript:void(0);" id="forgot-link" class="small text-info fw-semibold">
                            Forgot Password?
                        </a> --}}
                    </div>

                    <button type="submit" class="w-100 btn btn-primary fw-bold py-2">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Log in
                    </button>
                </form>

                <hr class="my-4">
                <p class="text-center small text-muted mb-0">
                    New to Loomi? 
                    <a href="{{ route('show.register') }}" class="fw-semibold text-info">Create an account</a>
                </p>
            </div>
        </div>
    </div>
</div>

{{-- Forgot Password Modal --}}
<div class="modal fade" id="forgotModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold text-primary">Reset Your Password</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('password.email') }}" method="POST">
                    @csrf
                    <div class="form-floating mb-3">
                        <input type="email" class="form-control" name="reset_email" id="reset_email" 
                            placeholder="Enter your email" required>
                        <label for="reset_email">Email address</label>
                    </div>
                    <button type="submit" class="btn btn-info text-white w-100 fw-semibold">
                        Send Reset Link
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('footer')
@include('includes.footer')
@endsection

@section('scripts')
<script>
    const forgotLink = document.getElementById("forgot-link");
    if (forgotLink) {
        forgotLink.addEventListener("click", () => {
            const modal = new bootstrap.Modal(document.getElementById("forgotModal"));
            modal.show();
        });
    }
</script>
@endsection
