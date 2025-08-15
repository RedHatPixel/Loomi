@extends('components.default')

@section('title', Auth::user()->name . "'s Profile")

@section('nav')
@include('includes.profile')
@endsection

@section('sidebar')
@include('includes.user')
@endsection

@section('mainbar')
<div class="card shadow-sm border-0 my-1">
    <div class="card-header bg-white">
        <h5 class="mb-0">Contact</h5>
    </div>
    <div class="card-body">
        
        @if(Auth::user()->profile && Auth::user()->profile->contact_number ?? false)
            <p class="mb-1">{{ Auth::user()->profile->contact_number ?? '' }}</p>
        @else
            <p class="text-muted mb-1">No contact information.</p>
        @endif
    </div>
</div>

<div class="card shadow-sm border-0 my-1">
    <div class="card-header bg-white d-flex justify-content-between">
        <h5 class="mb-0">Address</h5>
        <a href="">create</a>
    </div>
    <div class="card-body">
        @if(Auth::user()->profile && Auth::user()->profile->addresses->count())
            <ul class="list-group list-group-flush">
                @foreach(Auth::user()->profile->addresses as $address)
                    <li class="list-group-item px-0 py-2">
                        <div class="d-flex flex-column">
                            <span class="fw-bold">{{ $address->street ?? '' }}</span>
                            <span class="text-muted small">
                                {{ $address->barangay->name ?? '' }},
                                {{ $address->barangay->municipality->name ?? '' }},
                                {{ $address->barangay->municipality->province->name ?? '' }}
                            </span>
                        </div>
                    </li>
                @endforeach
            </ul>
        @else
            <p class="text-muted">No address found.</p>
        @endif
    </div>
</div>
@endsection
