@extends('admin.components.default')
@section('title', 'Edit Product')

@section('content')
<div class="container-fluid py-4">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h1 class="h3">Edit Product</h1>
        <a href="{{ route('admin.products') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Back
        </a>
    </div>

    <form method="POST" action="{{ route('products.update', $product->id) }}" enctype="multipart/form-data" class="card shadow-sm p-4">
        @csrf 
        @method('PUT')

        {{-- Title --}}
        <div class="mb-3">
            <label for="title" class="form-label">Product Title</label>
            <input type="text" name="title" id="title" 
                    value="{{ old('title', $product->title) }}"
                    class="form-control @error('title') is-invalid @enderror" required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Description --}}
        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
                <textarea name="description" id="description" rows="4"
                        class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
            @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Price & Quantity --}}
        <div class="row g-3">
            <div class="col-md-6">
                <label for="price" class="form-label">Price (₱)</label>
                <input type="number" step="0.01" min="0" name="price" id="price" 
                        value="{{ old('price', $product->price) }}"
                        class="form-control @error('price') is-invalid @enderror" required>
                @error('price')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="col-md-6">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" min="0" name="quantity" id="quantity" 
                        value="{{ old('quantity', $product->quantity) }}"
                        class="form-control @error('quantity') is-invalid @enderror" required>
                @error('quantity')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Categories --}}
        <div class="mb-3 mt-3">
            <label for="categories" class="form-label fw-semibold">Categories</label>
            
            {{-- Hidden input to hold selected categories --}}
            <div id="categories-hidden-inputs">
                @foreach(old('categories', $product->categories->pluck('id')->toArray()) as $catId)
                    <input type="hidden" name="categories[]" value="{{ $catId }}" data-id="{{ $catId }}">
                @endforeach
            </div>

            {{-- Dropdown --}}
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" id="categoryDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    Select Categories
                </button>
                <ul class="dropdown-menu" aria-labelledby="categoryDropdown">
                    @foreach ($categories as $category)
                        <li>
                            <a href="#" class="dropdown-item category-option" data-id="{{ $category->id }}">
                                {{ $category->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Selected badges --}}
            <div id="selected-categories" class="mt-2">
                @foreach(old('categories', $product->categories->pluck('id')->toArray()) as $catId)
                    @php $cat = $categories->find($catId); @endphp
                    @if($cat)
                        <span class="badge bg-danger me-1 category-badge" data-id="{{ $cat->id }}">
                            {{ $cat->name }} <i class="bi bi-x ms-1 remove-category" style="cursor:pointer"></i>
                        </span>
                    @endif
                @endforeach
            </div>
        </div>

        {{-- Images --}}
        <div class="mb-3">
            <label for="images" class="form-label">Product Images</label>
            <input type="file" name="images[]" id="images" multiple class="form-control">
            <div class="form-text">Upload new images to replace or add more.</div>

            {{-- Preview container --}}
            <div id="image-preview" class="d-flex flex-wrap gap-2 mt-3">
                @foreach($product->images as $image)
                    <div class="position-relative existing-image" style="width:120px; height:120px;" data-id="{{ $image->id }}">
                        <img src="{{ asset('storage/' . $image->image_path) }}" 
                            class="img-thumbnail w-100 h-100 object-fit-cover rounded" alt="Product Image">
                        <button type="button" class="btn btn-sm btn-danger position-absolute top-0 end-0 px-1 py-0 m-1 remove-existing">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                @endforeach
            </div>

            {{-- Hidden inputs for deleted images --}}
            <div id="deleted-images"></div>
        </div>

        {{-- Submit --}}
        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> Update Product
        </button>
    </form>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
    const hiddenInputsWrapper = document.getElementById("categories-hidden-inputs");
    const selectedWrapper = document.getElementById("selected-categories");
    let selected = Array.from(hiddenInputsWrapper.querySelectorAll("input")).map(inp => parseInt(inp.value));

    // Add category
    document.querySelectorAll(".category-option").forEach(option => {
        option.addEventListener("click", function (e) {
            e.preventDefault();
            const id = parseInt(this.dataset.id);
            const text = this.textContent.trim();

            if (!selected.includes(id)) {
                selected.push(id);
                addHiddenInput(id);
                addBadge(id, text);
            }
        });
    });

    // Add Hidden input from HiddenInputWrapper
    function addHiddenInput(id) {
        const hidden = document.createElement("input");
        hidden.type = "hidden";
        hidden.name = "categories[]";
        hidden.value = id;
        hidden.dataset.id = id;
        hiddenInputsWrapper.appendChild(hidden);
    }

    // Add badge
    function addBadge(id, text) {
        const badge = document.createElement("span");
        badge.className = "badge bg-danger me-1 category-badge";
        badge.dataset.id = id;
        badge.innerHTML = `${text} <i class="bi bi-x ms-1 remove-category" style="cursor:pointer"></i>`;

        attachRemoveHandler(badge.querySelector(".remove-category"), id, badge);
        selectedWrapper.appendChild(badge);
    }

    // Attach remove handler (used for both existing + new badges)
    function attachRemoveHandler(element, id, badgeElement) {
        element.addEventListener("click", () => {
            selected = selected.filter(item => item !== id);
            hiddenInputsWrapper.querySelector(`input[data-id="${id}"]`)?.remove();
            badgeElement.remove();
        });
    }

    // 🔑 Attach to already-rendered badges from Blade
    selectedWrapper.querySelectorAll(".category-badge").forEach(badge => {
        const id = parseInt(badge.dataset.id);
        const removeBtn = badge.querySelector(".remove-category");
        attachRemoveHandler(removeBtn, id, badge);
    });

    const imagesInput = document.getElementById("images");
    const preview = document.getElementById("image-preview");
    const deletedImagesWrapper = document.getElementById("deleted-images");

    // Remove existing images
    document.querySelectorAll(".remove-existing").forEach(btn => {
        btn.addEventListener("click", function () {
            const wrapper = this.closest(".existing-image");
            const id = wrapper.dataset.id;

            // Add hidden input to track deletion
            const hidden = document.createElement("input");
            hidden.type = "hidden";
            hidden.name = "remove_images[]";
            hidden.value = id;
            deletedImagesWrapper.appendChild(hidden);
            console.log(deletedImagesWrapper);
            wrapper.remove();
        });
    });

    // Preview new uploads
    imagesInput.addEventListener("change", function () {
        // Clear previews of *new* images (not existing ones)
        preview.querySelectorAll(".new-image").forEach(el => el.remove());

        Array.from(this.files).forEach(file => {
            if (file.type.startsWith("image/")) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const imgWrapper = document.createElement("div");
                    imgWrapper.className = "position-relative new-image";
                    imgWrapper.style.width = "120px";
                    imgWrapper.style.height = "120px";

                    const img = document.createElement("img");
                    img.src = e.target.result;
                    img.className = "img-thumbnail w-100 h-100 object-fit-cover rounded";

                    const removeBtn = document.createElement("button");
                    removeBtn.type = "button";
                    removeBtn.className = "btn btn-sm btn-danger position-absolute top-0 end-0 px-1 py-0 m-1";
                    removeBtn.innerHTML = '<i class="bi bi-x"></i>';

                    removeBtn.addEventListener("click", () => {
                        const dt = new DataTransfer();
                        Array.from(imagesInput.files)
                            .filter(f => f !== file)
                            .forEach(f => dt.items.add(f));
                        imagesInput.files = dt.files;
                        imgWrapper.remove();
                    });

                    imgWrapper.appendChild(img);
                    imgWrapper.appendChild(removeBtn);
                    preview.appendChild(imgWrapper);
                };
                reader.readAsDataURL(file);
            }
        });
    });
});
</script>
@endsection
