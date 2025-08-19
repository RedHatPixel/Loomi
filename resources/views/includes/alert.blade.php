{{-- Error Alert --}}
@if($errors->any())
    <div class="alert alert-danger custom-alert alert-dismissible fade show" role="alert">
        <div class="d-flex align-items-center me-3 my-1">
            <i class="bi bi-exclamation-triangle-fill fs-5 me-2"></i>
            <div>
                <ul class="mb-0 ps-0 list-unstyled">
                    @foreach ($errors->all() as $error)
                        <li class="small">{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
        <button type="button" class="btn-close"  data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- Success Alert --}}
@if(session('success'))
    <div class="alert alert-success custom-alert alert-dismissible fade show" role="alert">
        <div class="d-flex align-items-center me-3 my-1">
            <i class="bi bi-check-circle-fill fs-5 me-2"></i>
            <div class="small text-break">{{ session('success') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

{{-- Info Alert --}}
@if(session('info'))
    <div class="alert alert-info custom-alert alert-dismissible fade show" role="alert">
        <div class="d-flex align-items-center me-3 my-1">
            <i class="bi bi-info-circle-fill fs-5 me-2"></i>
            <div class="small text-break">{{ session('info') }}</div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

