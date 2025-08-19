@extends('components.default')

@section('title', 'Add Address')

@section('nav')
@include('includes.profile')
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8 col-md-10">
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-header bg-white border-0 d-flex align-items-center">
                <i class="bi bi-geo-alt-fill text-primary me-2 fs-5"></i>
                <h5 class="mb-0 fw-semibold">Add Address</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('address.store') }}" method="POST" class="row g-3">
                    @csrf
                    
                    {{-- Address Info --}}
                    <h6 class="fw-semibold text-muted mb-2">🏠 Address Info</h6>
                    <div class="col-md-6">
                        <label for="house_number" class="form-label">House Number</label>
                        <input type="text" class="form-control shadow-sm-sm" id="house_number" name="house_number" placeholder="e.g. 123">
                    </div>
                    <div class="col-md-6">
                        <label for="subdivision" class="form-label">Subdivision</label>
                        <input type="text" class="form-control shadow-sm-sm" id="subdivision" name="subdivision" placeholder="e.g. Green Meadows">
                    </div>
                    <div class="col-12">
                        <label for="street" class="form-label">Street</label>
                        <input type="text" class="form-control shadow-sm-sm" id="street" name="street" placeholder="e.g. Mabini St.">
                    </div>

                    <hr class="my-3">

                    {{-- Location Info --}}
                    <h6 class="fw-semibold text-muted mb-2">📍 Location Info</h6>
                    <div class="col-md-4">
                        <label for="province_id" class="form-label">Province <span class="text-danger">*</span></label>
                        <select class="form-select shadow-sm-sm" id="province_id" name="province_id" required>
                            <option value="">Select Province</option>
                            @foreach($provinces as $province)
                                <option value="{{ $province->id }}">{{ $province->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="municipality_id" class="form-label">Municipality <span class="text-danger">*</span></label>
                        <select class="form-select shadow-sm-sm" id="municipality_id" name="municipality_id" required disabled>
                            <option value="">Select Municipality</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="barangay_id" class="form-label">Barangay <span class="text-danger">*</span></label>
                        <select class="form-select shadow-sm-sm" id="barangay_id" name="barangay_id" required disabled>
                            <option value="">Select Barangay</option>
                        </select>
                    </div>

                    <div class="col-md-6">
                        <label for="zip_code" class="form-label">Zip Code <span class="text-danger">*</span></label>
                        <input type="text" class="form-control shadow-sm-sm" id="zip_code" name="zip_code" required placeholder="e.g. 4109">
                    </div>

                    <div class="col-12 d-flex justify-content-between">
                        <a href="{{ route('user.index') }}" class="btn btn-outline-danger px-3 rounded-pill shadow-sm">
                            <i class="bi bi-arrow-left"></i> Cancel
                        </a>
                        <button type="submit" class="btn btn-primary px-4 rounded-pill shadow-sm">
                            <i class="bi bi-save me-1"></i> Save Address
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
    const municipalities = @json($municipalities);
    const barangays = @json($barangays);

    document.getElementById('province_id').addEventListener('change', function() {
        // Get the province id and select element
        const provinceId = this.value;
        const municipalitySelect = document.getElementById('municipality_id');

        // Reset Municipality
        municipalitySelect.innerHTML = 
            '<option class="fw-light" value="">Select Municipality</option>';
        municipalitySelect.disabled = !provinceId;

        // Reset Barangay 
        document.getElementById('barangay_id').innerHTML = 
            '<option class="fw-light" value="">Select Barangay</option>';
        document.getElementById('barangay_id').disabled = true;
        
        // Loop through available municipalities
        if (provinceId) {
            municipalities.filter(m => m.province_id == provinceId).forEach(m => {
                municipalitySelect.innerHTML += 
                    `<option class="fw-light" value="${m.id}">${m.name}</option>`;
            });
        }
    });

    document.getElementById('municipality_id').addEventListener('change', function() {
        // Get the municipality id and select element
        const municipalityId = this.value;
        const barangaySelect = document.getElementById('barangay_id');

        // Reset Barangay
        barangaySelect.innerHTML = 
            '<option class="fw-light" value="">Select Barangay</option>';
        barangaySelect.disabled = !municipalityId;

        // Loop through available barangays
        if (municipalityId) {
            barangays.filter(b => b.municipality_id == municipalityId).forEach(b => {
                barangaySelect.innerHTML += 
                    `<option class="fw-light" value="${b.id}">${b.name}</option>`;
            });
        }
    });
</script>
@endsection