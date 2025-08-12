<div class="container">
    <footer class="py-5">
        <div class="row">
            <div class="col-6 col-md-2 mb-3">
                <h5>Loomi</h5>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <a href="{{ route('home') }}" class="nav-link p-0 text-body-secondary">Home</a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="{{ route('products.index') }}" class="nav-link p-0 text-body-secondary">Products</a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="#" class="nav-link p-0 text-body-secondary">Pricing</a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="#" class="nav-link p-0 text-body-secondary">FAQs</a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="#" class="nav-link p-0 text-body-secondary">About</a>
                    </li>
                </ul>
            </div>
            <div class="col-6 col-md-2 mb-3">
                <h5>Sorting</h5>
                <ul class="nav flex-column">
                    <li class="nav-item mb-2">
                        <a href="{{ 
                                route('products.index', 
                                array_merge(request()->all(), ['sort' => null])) 
                                }}"
                            class="nav-link p-0 text-body-secondary">Relevance</a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="{{ 
                                route('products.index', 
                                array_merge(request()->all(), ['sort' => 'latest'])) 
                                }}" 
                                class="nav-link p-0 text-body-secondary">Latest</a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="{{ 
                            route('products.index', 
                            array_merge(request()->all(), ['sort' => 'top_sales'])) 
                            }}" 
                            class="nav-link p-0 text-body-secondary">Top Sales</a>
                        </li>
                    <li class="nav-item mb-2">
                        <a href="{{ 
                            route('products.index', 
                            array_merge(request()->all(), ['sort' => 'high_ratings'])) 
                            }}" 
                            class="nav-link p-0 text-body-secondary">High Ratings</a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="{{ 
                            route('products.index', 
                            array_merge(request()->all(), ['sort' => 'price_low'])) 
                            }}" 
                            class="nav-link p-0 text-body-secondary">Price: Low to High</a>
                    </li>
                    <li class="nav-item mb-2">
                        <a href="{{ 
                            route('products.index', 
                            array_merge(request()->all(), ['sort' => 'price_high'])) 
                            }}"
                            class="nav-link p-0 text-body-secondary">Price: High to Low</a>
                    </li>
                    
                </ul>
            </div>
            <div class="col-6 col-md-2 mb-3">
                <h5>About</h5>
                <ul class="nav flex-column">
                </ul>
            </div>
            <div class="col-md-5 offset-md-1 mb-3">
                <form>
                    <h5>Subscribe to our newsletter</h5>
                    <p>Monthly digest of what's new and exciting from us.</p>
                    <div class="d-flex flex-column flex-sm-row w-100 gap-2">
                        <label for="newsletter1" class="visually-hidden">Email address</label>
                        <input id="newsletter1" type="email" class="form-control" placeholder="Email address">
                        <button class="btn btn-primary" type="button">Subscribe</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="d-flex flex-column flex-sm-row justify-content-between py-4 my-4 border-top">
            <p>© 2025 Company, Inc. All rights reserved.</p>
            <ul class="list-unstyled d-flex">
                <li class="ms-3">
                    <a class="link-body-emphasis" href="https://www.instagram.com/" aria-label="Instagram">
                        <i class="bi bi-instagram"></i>
                    </a>
                </li>
                <li class="ms-3">
                    <a class="link-body-emphasis" href="https://www.facebook.com/" aria-label="Facebook">
                        <i class="bi bi-facebook"></i>
                    </a>
                </li>
            </ul>
        </div>
    </footer>
</div>