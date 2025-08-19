<nav class="d-md-block sidebar collapse border rounded shadow-sm bg-white" id="filterSidebar" 
    style="min-height: 100vh;" data-aos="fade-right">
    <!-- Header -->
    <a href="{{ route('products.index') }}" 
        class="d-flex align-items-center text-decoration-none text-dark border-bottom gap-3 p-3 bg-light rounded-top">
        <i class="bi bi-filter fs-4 text-primary"></i>
        <span class="fs-5 fw-semibold">Search Filter</span>
    </a>

    <!-- Accordion Filters -->
    <div class="accordion accordion-flush mt-2" id="sidebarFilters">
        
        <!-- Category -->
        <div class="accordion-item border-0">
            <h2 class="accordion-header" id="headingCategory">
                <button class="accordion-button collapsed bg-white fw-semibold text-dark shadow-none" 
                    type="button" data-bs-toggle="collapse" data-bs-target="#collapseCategory">
                    <i class="bi bi-view-list me-2 text-success"></i> Category
                </button>
            </h2>
            <div id="collapseCategory" class="accordion-collapse show" data-bs-parent="#sidebarFilters">
                <div class="accordion-body p-2">
                    <div class="list-group list-group-flush small">
                        @isset($categories)
                            @foreach($categories as $category)
                                <a href="{{ route('products.index', array_merge(request()->all(), ['category' => $category->name])) }}"
                                    class="list-group-item list-group-item-action px-3 py-2">
                                    {{ $category->name }}
                                </a>
                            @endforeach
                        @endisset
                    </div>
                </div>
            </div>
        </div>

        <!-- Price Range -->
        <div class="accordion-item border-0">
            <h2 class="accordion-header" id="headingPrice">
                <button class="accordion-button collapsed bg-white fw-semibold text-dark shadow-none" 
                    type="button" data-bs-toggle="collapse" data-bs-target="#collapsePrice">
                    <i class="bi bi-tag me-2 text-danger"></i> Price Range
                </button>
            </h2>
            <div id="collapsePrice" class="accordion-collapse collapse" data-bs-parent="#sidebarFilters">
                <div class="accordion-body p-3">
                    <form action="{{ route('products.index', array_merge(request()->all())) }}" method="GET" class="d-flex flex-column gap-2">
                        <div class="d-flex align-items-center gap-2">
                            <label for="minPrice" class="form-label mb-0 small fw-semibold">Min:</label>
                            <input type="number" name="min_price" id="minPrice" class="form-control form-control-sm" placeholder="₱0" min="0" max="999999" value="{{ old('min_price') }}" required>
                        </div>
                        <div class="d-flex align-items-center gap-2">
                            <label for="maxPrice" class="form-label mb-0 small fw-semibold">Max:</label>
                            <input type="number" name="max_price" id="maxPrice" class="form-control form-control-sm" placeholder="₱10000" min="0" max="999999" value="{{ old('max_price') }}" required>
                        </div>
                        <button type="submit" class="btn btn-sm btn-outline-primary mt-2">Apply</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Rating -->
        <div class="accordion-item border-0">
            <h2 class="accordion-header" id="headingRating">
                <button class="accordion-button collapsed bg-white fw-semibold text-dark shadow-none" 
                    type="button" data-bs-toggle="collapse" data-bs-target="#collapseRating">
                    <i class="bi bi-star me-2 text-warning"></i> Rating
                </button>
            </h2>
            <div id="collapseRating" class="accordion-collapse collapse" data-bs-parent="#sidebarFilters">
                <div class="accordion-body p-2">
                    <div class="list-group list-group-flush small">
                        @for ($i = 4; $i >= 1; $i--)
                            <a href="{{ route('products.index', array_merge(request()->all(), ['rating' => $i])) }}"
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

<!-- Mobile Toggle -->
<div class="d-block d-md-none m-2">
    <button class="w-100 btn btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#filterSidebar">
        <i class="bi bi-funnel"></i> Filters
    </button>
</div>
