<!-- Forgot Password Modal Form (Initially Hidden) -->
<div id="forgot-password-form" 
    class="position-fixed top-0 start-0 w-100 h-100 d-none"
    style="background-color: rgba(0, 0, 0, 0.6); z-index: 1050;">
    <div class="d-flex justify-content-center align-items-center h-100">
        <form action="{{ route('password.email') }}" method="POST" class="bg-white p-4 rounded shadow" style="width: 100%; max-width: 400px;">
            @csrf
            <h5 class="mb-3 text-center text-primary">Reset Your Password</h5>
            <p class="text-center small text-muted">Enter your email and we’ll send a password reset link.</p>

            <div class="form-floating my-3">
                <input type="email" class="form-control" 
                    name="email" id="email" placeholder="name@example.com" value="{{ old('email') }}" required>
                <label for="email">Email address</label>
            </div>

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-link-45deg"></i> Send Link
                </button>
                <button type="button" id="cancel-forgot" class="btn btn-outline-secondary">
                    <i class="bi bi-x-circle"></i> Cancel
                </button>
            </div>
        </form>
    </div>
</div>