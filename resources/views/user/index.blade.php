@extends('components.default')

@section('title', Auth::user()->name . "'s Profile")

@section('nav')
@include('includes.profile')
@endsection

@section('sidebar')
@include('includes.user')
@endsection

@section('mainbar')
<div class="container-fluid px-0">

    {{-- Contact Card --}}
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-header bg-light d-flex align-items-center gap-2">
            <i class="bi bi-telephone text-primary"></i>
            <h5 class="mb-0">Contact</h5>
        </div>
        <div class="card-body">
            @if(Auth::user()->profile && Auth::user()->profile->contact_number ?? false)
                <p class="mb-0 fs-6">{{ Auth::user()->profile->contact_number }}</p>
            @else
                <p class="text-muted mb-0">No contact information available.</p>
            @endif
        </div>
    </div>

    {{-- Address Card --}}
    <div class="card shadow-sm border-0 mb-3">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center gap-2">
                <i class="bi bi-geo-alt text-primary"></i>
                <h5 class="mb-0">Address</h5>
            </div>
            <a href="{{ route('address.create') }}" class="btn btn-sm btn-outline-primary">
                <i class="bi bi-plus-circle"></i> Add New
            </a>
        </div>
        <div class="card-body">
            @if(Auth::user()->addresses->count())
                <ul class="list-group list-group-flush">
                    @foreach(Auth::user()->addresses as $address)
                        <li class="list-group-item d-flex flex-wrap justify-content-between align-items-center gap-3 px-0 py-3 border-0 border-bottom">
                            <div class="d-flex flex-column">
                                <span class="fw-semibold">{{ $address->house_number ?? '' }} {{ $address->street ?? '' }}</span>
                                <small class="text-muted">
                                    {{ $address->subdivision ?? '' }} 
                                    {{ $address->barangay->name ?? '' }},
                                    {{ $address->barangay->municipality->name ?? '' }},
                                    {{ $address->barangay->municipality->province->name ?? '' }}  
                                    {{ $address->zip_code ?? '' }}
                                </small>
                            </div>
                            <form action="{{ route('address.destroy', $address) }}" method="POST" class="m-0">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="text-muted mb-0">No addresses found. Add one to make shopping easier.</p>
            @endif
        </div>
    </div>
</div>
@endsection

@section('content')
@endsection