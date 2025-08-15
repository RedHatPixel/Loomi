@extends('components.default')

@section('title', Auth::user()->name . "'s Edit Profile")

@section('nav')
@include('includes.profile')
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="bi bi-pencil-square"></i> Edit Profile</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="mb-4 text-center">
                        <img  id="avatarPreview"
                            src="
                            {{ Auth::user()->profile->avatar ? 
                            asset('storage/' . Auth::user()->profile->avatar) :
                            'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) }}" 
                            class="rounded-circle mb-3" 
                            width="150" height="150" 
                            alt="Avatar">
                        <div>
                            <label for="avatar" class="btn btn-sm btn-outline-dark mt-2 d-inline-flex gap-2 justify-content-center align-items-center">
                                <i class="bi bi-person-circle fs-6"></i> <span>Change Avatar</span>
                            </label>
                            <input type="file" id="avatar" name="avatar" class="d-none" accept="image/*" onchange="previewAvatar(event)">
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="first_name" name="first_name" 
                            value="{{ Auth::user()->profile->first_name ?? '' }}">
                        <label for="first_name" class="form-label">First Name</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="last_name" name="last_name" 
                            value="{{ Auth::user()->profile->last_name ?? '' }}">
                        <label for="last_name" class="form-label">Last Name</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" id="contact_number" name="contact_number" 
                            value="{{ Auth::user()->profile->contact_number ?? '' }}">
                            <label for="contact_number" class="form-label">Contact Number</label>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-sm btn-primary">
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