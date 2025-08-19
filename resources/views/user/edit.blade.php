@extends('components.default')

@section('title', Auth::user()->name . "'s Edit Profile")

@section('nav')
@include('includes.profile')
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-white border-0 d-flex align-items-center">
                <i class="bi bi-pencil-square text-primary me-2 fs-5"></i>
                <h5 class="mb-0 fw-semibold">Edit Profile</h5>
            </div>

            <div class="card-body p-4">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')

                    <!-- Avatar Preview -->
                    <div class="text-center mb-4">
                        <img id="avatarPreview"
                            src="{{ Auth::user()->profile && Auth::user()->profile->avatar !== null ? 
                            asset('storage/' . Auth::user()->profile->avatar) : 
                            'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}" 
                            class="rounded-circle shadow-sm border border-2 border-light" 
                            width="140" height="140" 
                            alt="Avatar">

                        <div class="mt-2">
                            <label for="avatar" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-upload me-1"></i> Change Avatar
                            </label>
                            <input type="file" id="avatar" name="avatar" class="d-none" accept="image/*" onchange="previewAvatar(event)">
                        </div>
                    </div>

                    <!-- First Name -->
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control shadow-sm" id="first_name" name="first_name" 
                            value="{{ Auth::user()->profile->first_name ?? '' }}" placeholder="First Name">
                        <label for="first_name">First Name</label>
                    </div>

                    <!-- Last Name -->
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control shadow-sm" id="last_name" name="last_name" 
                            value="{{ Auth::user()->profile->last_name ?? '' }}" placeholder="Last Name">
                        <label for="last_name">Last Name</label>
                    </div>

                    <!-- Contact Number -->
                    <div class="form-floating mb-4">
                        <input type="text" class="form-control shadow-sm" id="contact_number" name="contact_number" 
                            value="{{ Auth::user()->profile->contact_number ?? '' }}" placeholder="Contact Number">
                        <label for="contact_number">Contact Number</label>
                    </div>

                    <!-- Buttons -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('user.index') }}" class="btn btn-sm btn-outline-danger px-3 rounded-pill shadow-sm">
                            <i class="bi bi-arrow-left"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-sm btn-primary px-4 rounded-pill shadow-sm">
                            <i class="bi bi-save"></i> Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
function previewAvatar(event) {
    const input = event.target;
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('avatarPreview').src = e.target.result;
        };
        reader.readAsDataURL(input.files[0]);
    }
}
</script>
@endsection
