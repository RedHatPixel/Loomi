<tr>
    <td style="width: 80px;">
        <img src="{{ $item->product->primaryImage ? 
            asset('storage/' . $item->product->primaryImage->image_path) :
            'https://via.placeholder.com/60x60?text=No+Image' }}" 
            alt="{{ $item->product->title }}"
            class="rounded-circle border"
            style="object-fit: cover; height: 60px; width: 60px;">
    </td>
    <td style="min-width: 300px;">
        <strong>{{ ucfirst($item->product->title) }}</strong><br>
        <small class="text-muted">{{ Str::limit($item->product->description, 50) }}</small>
    </td>
    <td class="fw-semibold text-success"  style="min-width: 120px;">
        ₱ {{ number_format($item->product->price, 2) }}
    </td>
    <td style="min-width: 100px;">
        {{ $item->quantity }}
    </td>
    <td style="min-width: 120px;">
        ₱ {{ number_format($item->quantity * $item->product->price, 2) }}
    </td>
</tr>