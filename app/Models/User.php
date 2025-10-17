<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;   
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Laravel\Sanctum\HasApiTokens;  

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */

    use HasFactory, Notifiable, HasApiTokens;
    
    // Disable timestamps vì database không có created_at, updated_at
    public $timestamps = false;


    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * The roles that belong to the user.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'user_roles')
                    ->withTimestamps();
    }

    /**
     * Get the employee record for the user.
     */
    public function employee(): HasOne
    {
        return $this->hasOne(Employee::class);
    }

    /**
     * Get the bookings for the user.
     */
    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    /**
     * Get the reviews for the user.
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Get the support tickets for the user.
     */
    public function supportTickets(): HasMany
    {
        return $this->hasMany(SupportTicket::class);
    }

    /**
     * Get the notifications for the user.
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class);
    }

    /**
     * Get the user history for the user.
     */
    public function userHistory(): HasMany
    {
        return $this->hasMany(UserHistory::class);
    }

    /**
     * Get the wishlist for the user.
     */
    public function wishlist(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    /**
     * The tours that the user has wishlisted.
     */
    public function wishlistedTours(): BelongsToMany
    {
        return $this->belongsToMany(Tour::class, 'wishlists')
                    ->withTimestamps();
    }

    /**
     * Get the chat messages sent by the user.
     */
    public function chatMessages(): HasMany
    {
        return $this->hasMany(ChatMessage::class, 'sender_id');
    }

    /**
     * Check if user has a specific role.
     */
    public function hasRole(string $role): bool
    {
        return $this->roles()->where('name', $role)->exists();
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if user is staff.
     */
    public function isStaff(): bool
    {
        return $this->hasRole('staff');
    }

    /**
     * Check if user is customer.
     */
    public function isCustomer(): bool
    {
        return $this->hasRole('customer');
    }

    /**
     * Check if user has a specific permission.
     */
    public function hasPermission(string $permission): bool
    {
        foreach ($this->roles as $role) {
            if ($role->permissions()->where('name', $permission)->exists()) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if user has any of the given permissions.
     */
    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Check if user has all of the given permissions.
     */
    public function hasAllPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Get user's permissions.
     */
    public function getPermissions(): \Illuminate\Database\Eloquent\Collection
    {
        $permissions = collect();
        foreach ($this->roles as $role) {
            $permissions = $permissions->merge($role->permissions);
        }
        return $permissions->unique('id');
    }
}
