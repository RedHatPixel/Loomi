@extends('components.default')

@section('title', "Checkout")

@section('nav') 
@include('includes.profile')
@endsection

@section('content')
<div class="py-5 text-center bg-light rounded shadow-sm mb-5"
    style="background: linear-gradient(135deg, #ffb731, #fff785);">
    <h1 class="fw-bold">
        <i class="bi bi-cart-check-fill"></i>
        Complete Your Order
    </h1> 
    <p class="lead text-muted">
        Fill in your details to proceed with the checkout. 
        Please ensure all information is correct before submitting.
    </p> 
</div> 

<div class="row g-5">
    {{-- CART SUMMARY --}}
    <div class="col-md-6 col-lg-5 order-md-last">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <span>Your Cart</span>
                <span class="badge bg-white text-primary rounded-pill">{{ $collections['total_product'] }}</span>
            </div>
            <ul class="list-group list-group-flush">
                @foreach ($collections['products'] as $item)
                    <li class="list-group-item d-flex flex-column justify-content-between align-items-start">
                        <div class="p-2 w-100">
                            <div class="fw-bold text-capitalize text-truncate">
                                <span class="badge bg-secondary rounded-pill me-2">{{ $item['quantity'] }}</span> 
                                {{ $item['product']->title }}
                            </div>
                            <samll class="d-block text-muted text-truncate">
                                {{ $item['product']->description }}
                            </samll>
                        </div>
                        <span class="text-success fw-bold">₱ {{ number_format($item['total_price'], 2) }}</span>
                    </li>
                @endforeach
                <li class="list-group-item d-flex justify-content-between fw-bold">
                    <span>Total</span>
                    <span>₱ {{ number_format($collections['total_amount'], 2) }}</span>
                </li>
            </ul>
        </div>
    </div>

    {{-- BILLING FORM --}}
    <div class="col-md-6 col-lg-7">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Billing Address</h5>
            </div>
            <div class="card-body">
                <form class="needs-validation" action="{{ route('orders.store') }}" method="POST">
                    @csrf
                    @foreach ($collections['products'] as $i => $item)
                        <input type="hidden" name="items[{{ $i }}][product_id]" value="{{ $item['product']->id }}">
                        <input type="hidden" name="items[{{ $i }}][quantity]" value="{{ $item['quantity'] }}">
                        @if ($item['cart_id'] ?? false)
                            <input type="hidden" name="items[{{ $i }}][cart_id]" value="{{ $item['cart_id'] }}">
                        @endif
                    @endforeach

                    <div class="row g-3">
                        <div class="col-sm-6">
                            <label for="firstName" class="form-label"><strong>First Name</strong></label>
                            <input type="text" class="form-control" id="firstName" name="first_name"
                                value="{{ old('first_name', Auth::user()->profile->last_name ?? '') }}" required>
                        </div>
                        <div class="col-sm-6">
                            <label for="lastName" class="form-label"><strong>Last Name</strong></label>
                            <input type="text" class="form-control" id="lastName" name="last_name"
                                value="{{ old('last_name', Auth::user()->profile->last_name ?? '') }}" required>
                        </div>

                        <div class="col-12">
                            <label for="address" class="form-label"><strong>Shipping Address</strong></label>
                            <select class="form-select" id="address" name="address" required>
                                @foreach($addresses as $address)
                                    <option value="
                                        {{ $address->house_number ?? '' }}
                                        {{ $address->subdivision ?? '' }}
                                        {{ $address->barangay->name ?? '' }}
                                        {{ $address->street ?? '' }}
                                        {{ $address->barangay->municipality->name ?? '' }},
                                        {{ $address->barangay->municipality->province->name ?? '' }}
                                        {{ $address->zip_code ?? '' }}
                                    ">
                                        {{ $address->house_number ?? '' }}
                                        {{ $address->subdivision ?? '' }}
                                        {{ $address->barangay->name ?? '' }}
                                        {{ $address->street ?? '' }}
                                        {{ $address->barangay->municipality->name ?? '' }},
                                        {{ $address->barangay->municipality->province->name ?? '' }}
                                        {{ $address->zip_code ?? '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-12">
                            <label for="contactNumber" class="form-label"><strong>Contact Number</strong></label>
                            <input type="tel" class="form-control" id="contactNumber" name="contact_number"
                                value="{{ old('contact_number', Auth::user()->profile->contact_number ?? '') }}" required>
                            <div class="invalid-feedback">Please provide a valid contact number.</div>
                        </div>

                        <div class="col-12">
                            <label for="notes" class="form-label"><strong>Order Notes</strong> (Optional)</label>
                            <textarea class="form-control" id="notes" name="notes" rows="3"
                                placeholder="Any special instructions for the delivery?">{{ old('notes') }}</textarea>
                        </div>
                    </div>

                    <div class="alert alert-info mt-4 d-flex align-items-center">
                        <span>
                            <i class="bi bi-truck me-2 fs-4"></i>
                            Orders are typically delivered within
                            <strong>3 – 5 business days.</strong>
                            Payment will be collected upon delivery.
                        </span>
                    </div>

                    <button class="w-100 btn btn-primary mt-3" type="submit">
                        Checkout My Order
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
