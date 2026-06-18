<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected static function booted(): void
    {
        static::created(function (User $user) {
            $user->notify(new \App\Notifications\WelcomeNotification());
        });
    }

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // ── Relationships ──────────────────────────────────────────

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles');
    }

    public function stores(): HasMany
    {
        return $this->hasMany(Store::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function cart(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Cart::class);
    }

    // ── Role helpers ───────────────────────────────────────────

    public function hasRole(string $role): bool
    {
        return $this->roles()->where('name', $role)->exists();
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    public function isSeller(): bool
    {
        return $this->hasRole('seller');
    }

    public function isCustomer(): bool
    {
        return $this->hasRole('customer');
    }

    public function assignRole(string $role): void
    {
        $roleModel = Role::firstOrCreate(['name' => $role]);
        $this->roles()->syncWithoutDetaching($roleModel->id);
    }

    public function removeRole(string $role): void
    {
        $roleModel = Role::where('name', $role)->first();
        if ($roleModel) {
            $this->roles()->detach($roleModel->id);
        }
    }
}
