@extends('components.default')

@section('title', "Checkout")

@section('nav') 
@include('includes.profile')
@endsection

@section('content')
<div class="py-5 text-center"> 
    <i class="d-block mx-auto mb-4 bi bi-ui-checks text-primary" style="font-size: 3rem;"></i>
    <h1 class="h2">Complete your order</h1> 
    <p class="lead">
        Fill in your details to proceed with the checkout. 
        Please ensure all information is correct before submitting.
    </p> 
</div> 
<div class="row g-5"> 
    <div class="col-md-5 col-lg-4 order-md-last"> 
        <h4 class="d-flex justify-content-between align-items-center mb-3"> 
            <span class="text-primary">Your cart</span> 
            <span class="badge bg-primary rounded-pill">{{ $collections['total_product'] }}</span> 
        </h4> 
        <ul class="list-group mb-3"> 
            @foreach ($collections['products'] as $item)
                <li class="list-group-item lh-sm"> 
                    <div> 
                        <h6 class="my-1 text-capitalize text-truncate text-ellipsis-2">
                            <span class="badge bg-secondary rounded-pill">{{ $item['quantity'] }}</span> 
                            {{ $item['product']->title }}
                        </h6> 
                        <small class="d-block text-body-secondary text-truncate text-ellipsis-2">
                            {{ $item['product']->description }}
                        </small> 
                    </div> 
                    <span class="d-block mt-2 text-success">
                        ₱ {{ number_format($item['total_price'], 2) ?? '0' }}
                    </span>
                </li> 
            @endforeach
            <li class="list-group-item d-flex justify-content-between"> 
                <span class="col-6">Total</span> 
                <strong class="col-6 text-end">₱ {{ number_format($collections['total_amount'], 2) ?? '0' }}</strong> 
            </li> 
        </ul> 
    </div> 
    <div class="col-md-7 col-lg-8"> 
        <h4 class="mb-3">Billing address</h4> 
        <form class="needs-validation" novalidate=""> 
            <div class="row g-3"> 
                <div class="col-sm-6"> 
                    <label for="firstName" class="form-label">First name</label> 
                    <input type="text" class="form-control" id="firstName" placeholder="" value="" required=""> 
                    <div class="invalid-feedback">
                        Valid first name is required.
                    </div> 
                </div> 
                <div class="col-sm-6"> 
                    <label for="lastName" class="form-label">Last name</label> 
                    <input type="text" class="form-control" id="lastName" placeholder="" value="" required=""> 
                    <div class="invalid-feedback">
                        Valid last name is required.
                    </div> 
                </div> 
                <div class="col-12"> 
                    <label for="username" class="form-label">Username</label> 
                    <div class="input-group has-validation"> 
                        <span class="input-group-text">@</span> 
                        <input type="text" class="form-control" id="username" placeholder="Username" required=""> 
                        <div class="invalid-feedback">
                            Your username is required.
                        </div> 
                    </div> 
                </div> 
                <div class="col-12"> 
                    <label for="email" class="form-label"> 
                        Email <span class="text-body-secondary">(Optional)</span>
                    </label> 
                    <input type="email" class="form-control" id="email" placeholder="you@example.com"> 
                    <div class="invalid-feedback">
                        Please enter a valid email address for shipping updates.
                    </div> 
                </div> 
                <div class="col-12"> 
                    <label for="address" class="form-label">Address</label> 
                    <input type="text" class="form-control" id="address" placeholder="1234 Main St" required=""> 
                    <div class="invalid-feedback">
                        Please enter your shipping address.
                    </div> 
                </div> 
                <div class="col-md-5"> 
                    <label for="country" class="form-label">Country</label> 
                    <select class="form-select" id="country" required=""> 
                        <option value="">Choose...</option> 
                        <option>United States</option> 
                    </select> 
                    <div class="invalid-feedback">
                        Please select a valid country.
                    </div> 
                </div> 
                <div class="col-md-4"> 
                    <label for="state" class="form-label">State</label> 
                    <select class="form-select" id="state" required=""> 
                        <option value="">Choose...</option> 
                        <option>California</option> </select> 
                    <div class="invalid-feedback">
                        Please provide a valid state.
                    </div> 
                </div> 
                <div class="col-md-3"> 
                    <label for="zip" class="form-label">Zip</label> 
                    <input type="text" class="form-control" id="zip" placeholder="" required=""> 
                    <div class="invalid-feedback">
                        Zip code required.
                    </div> 
                </div> 
            </div> 
            <hr class="my-4"> 
            <div class="form-check"> 
                <input type="checkbox" class="form-check-input" id="same-address"> 
                <label class="form-check-label" for="same-address">
                    Shipping address is the same as my billing address
                </label> 
            </div> 
            <div class="form-check"> 
                <input type="checkbox" class="form-check-input" id="save-info"> 
                <label class="form-check-label" for="save-info">Save this information for next time</label> 
            </div> 
            <hr class="my-4"> 
            <h4 class="mb-3">Payment</h4> 
            <div class="my-3"> 
                <div class="form-check"> 
                    <input id="credit" name="paymentMethod" type="radio" class="form-check-input" checked="" required=""> 
                    <label class="form-check-label" for="credit">Credit card</label> 
                </div> 
                <div class="form-check"> 
                    <input id="debit" name="paymentMethod" type="radio" class="form-check-input" required=""> 
                    <label class="form-check-label" for="debit">Debit card</label> 
                </div> 
                <div class="form-check"> 
                    <input id="paypal" name="paymentMethod" type="radio" class="form-check-input" required=""> 
                    <label class="form-check-label" for="paypal">PayPal</label> 
                </div> 
            </div> 
            <div class="row gy-3"> 
                <div class="col-md-6"> 
                    <label for="cc-name" class="form-label">Name on card</label> 
                    <input type="text" class="form-control" id="cc-name" placeholder="" required=""> 
                    <small class="text-body-secondary">Full name as displayed on card</small> 
                    <div class="invalid-feedback">
                        Name on card is required
                    </div> 
                </div> 
                <div class="col-md-6"> 
                    <label for="cc-number" class="form-label">Credit card number</label> 
                    <input type="text" class="form-control" id="cc-number" placeholder="" required=""> 
                    <div class="invalid-feedback">
                        Credit card number is required
                    </div> 
                </div> 
                <div class="col-md-3"> 
                    <label for="cc-expiration" class="form-label">Expiration</label> 
                    <input type="text" class="form-control" id="cc-expiration" placeholder="" required=""> 
                    <div class="invalid-feedback">
                        Expiration date required
                    </div> 
                </div> 
                <div class="col-md-3"> 
                    <label for="cc-cvv" class="form-label">CVV</label> 
                    <input type="text" class="form-control" id="cc-cvv" placeholder="" required=""> 
                    <div class="invalid-feedback">
                        Security code required
                    </div> 
                </div> 
            </div> 
            <hr class="my-4"> 
            <button class="w-100 btn btn-primary btn-lg" type="submit">Continue to checkout</button> 
        </form> 
    </div> 
</div> 
@endsection