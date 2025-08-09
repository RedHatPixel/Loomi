<div>
    <nav class="d-md-block sidebar collapse" id="filterSidebar">
        <a href="{{ route('products.index') }}" 
            class="d-flex align-items-center text-decoration-none text-dark border-bottom gap-3 p-3">
            <i class="bi bi-filter fs-3"></i>
            <span class="fs-5 fw-bold">Search Filter</span>
        </a>

        <div class="accordion accordion-flush mt-3" id="sidebarFilters">
            <!-- Sorting -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingSort">
                    <button class="accordion-button gap-2" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseSort" aria-expanded="false">
                        <i class="bi bi-sort-alpha-up"></i> Sorting
                    </button>
                </h2>
                <div id="collapseSort" class="accordion-collapse collapse show" data-bs-parent="#sidebarFilters">
                    <div class="accordion-body ps-2">
                        <div class="list-group list-group-flush">
                            <a href="{{ 
                                    route('products.index', 
                                    array_merge(request()->all(), ['sort' => null])) 
                                    }}"
                                class="list-group-item list-group-item-action px-3 py-2">Relevance</a>
                            <a href="{{ 
                                    route('products.index', 
                                    array_merge(request()->all(), ['sort' => 'latest'])) 
                                    }}" 
                                    class="list-group-item list-group-item-action px-3 py-2">Latest</a>
                            <a href="{{ 
                                    route('products.index', 
                                    array_merge(request()->all(), ['sort' => 'top_sales'])) 

                                    
                                    }}" 
                                    class="list-group-item list-group-item-action px-3 py-2">Top Sales</a>
                            <a href="{{ 
                                    route('products.index', 
                                    array_merge(request()->all(), ['sort' => 'high_ratings'])) 
                                    }}" 
                                    class="list-group-item list-group-item-action px-3 py-2">High Ratings</a>
                            <a href="{{ 
                                    route('products.index', 
                                    array_merge(request()->all(), ['sort' => 'price_low'])) 
                                    }}" 
                                    class="list-group-item list-group-item-action px-3 py-2">Price: Low to High</a>
                            <a href="{{ 
                                    route('products.index', 
                                    array_merge(request()->all(), ['sort' => 'price_high'])) 
                                    }}"
                                    class="list-group-item list-group-item-action px-3 py-2">Price: High to Low</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Category -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingCategory">
                    <button class="accordion-button gap-2" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseCategory" aria-expanded="false">
                        <i class="bi bi-view-list"></i> Category
                    </button>
                </h2>
                <div id="collapseCategory" class="accordion-collapse collapse" data-bs-parent="#sidebarFilters">
                    <div class="accordion-body ps-2">
                        <div class="list-group list-group-flush">
                            @isset($categories)
                                @foreach($categories as $category)
                                    <a 
                                        href="{{ route('products.index', array_merge(
                                        request()->all(), ['category' => $category->category])) }}"
                                        class="list-group-item list-group-item-action px-3 py-2">
                                        {{ $category->category }}
                                    </a>
                                @endforeach
                            @endisset
                        </div>
                    </div>
                </div>
            </div>

            <!-- Price Range -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingPrice">
                    <button class="accordion-button gap-2" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapsePrice" aria-expanded="false">
                        <i class="bi bi-tag"></i> Price Range
                    </button>
                </h2>
                <div id="collapsePrice" class="accordion-collapse collapse" data-bs-parent="#sidebarFilters">
                    <div class="accordion-body ps-4">
                        <form action="{{ route('products.index', array_merge(request()->all())) }}" 
                            method="GET" class="d-flex flex-column gap-2">
                            <div class="d-flex align-items-center gap-2">
                                <label for="minPrice" class="form-label mb-0">Min:</label>
                                <input type="number" name="min_price" id="minPrice" class="form-control form-control-sm" placeholder="₱0" min="0" max="999999" value="{{ old('min_price') }}" required>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <label for="maxPrice" class="form-label mb-0">Max:</label>
                                <input type="number" name="max_price" id="maxPrice" class="form-control form-control-sm" placeholder="₱10000" min="0" max="999999" value="{{ old('max_price') }}" required>
                            </div>
                            <button type="submit" class="btn btn-sm btn-outline-primary mt-2 w-100">Apply</button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Rating -->
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingRating">
                    <button class="accordion-button gap-2" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseRating" aria-expanded="false">
                        <i class="bi bi-star"></i> Rating
                    </button>
                </h2>
                <div id="collapseRating" class="accordion-collapse collapse" data-bs-parent="#sidebarFilters">
                    <div class="accordion-body ps-2">
                        <div class="list-group list-group-flush">
                            @for ($i = 4; $i >= 1; $i--)
                                <a href="{{ route('products.index', array_merge(
                                    request()->all(), ['rating' => $i])) }}"
                                class="list-group-item list-group-item-action px-3 py-2">
                                    @for ($j = 1; $j <= 5; $j++)
                                        @if ($j <= $i)
                                            <i class="bi bi-star-fill text-warning"></i>
                                        @else
                                            <i class="bi bi-star text-warning"></i>
                                        @endif
                                    @endfor
                                </a>
                            @endfor
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="d-block d-md-none m-2">
        <button class="w-100 btn btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#filterSidebar">
            <i class="bi bi-funnel"></i> Filters
        </button>
    </div>
</div>
