@if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show position-fixed top-0 end-0 m-3 shadow rounded-2 small" 
        style="z-index: 9999; max-width: 500px; min-width: 350px;">
        <ul class="list-unstyled">
            @foreach ($errors->all() as $error)
                <li class="pt-1 lead fs-6">{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show position-fixed top-0 end-0 m-3 shadow rounded-3 small" 
        style="z-index: 9999; max-width: 500px; min-width: 350px;">
        {{ session('success') }}
        <button type="button" class="btn-close btn-sm" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif