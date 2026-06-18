<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_PRIORITY = [
        'cancelled' => 0,
        'pending'   => 1,
        'confirmed' => 2,
        'shipped'   => 3,
        'delivered' => 4,
    ];

    protected $fillable = [
        'user_id',
        'address_id',
        'status',
        'total',
        'notes',
        'payment_method',
        'payment_details',
    ];

    protected function casts(): array
    {
        return [
            'total' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Derive the overall order status from a collection of item statuses.
     * Returns the lowest active (non-cancelled) status, or 'cancelled' if all are cancelled.
     *
     * @param  \Illuminate\Support\Collection<int, string>|string[]  $statuses
     * @return string
     */
    public static function deriveStatus($statuses): string
    {
        $unique = collect($statuses)->unique();

        $allCancelled = $unique->every(fn ($s) => $s === 'cancelled');
        if ($allCancelled) {
            return 'cancelled';
        }

        $active = $unique->filter(fn ($s) => $s !== 'cancelled');
        $lowest = $active->sortBy(fn ($s) => self::STATUS_PRIORITY[$s] ?? 0)->first();

        return $lowest ?? 'pending';
    }
}
