<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'availability_status',
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

    // Role constants
    public const ROLE_ADMIN = 'admin';
    public const ROLE_TECHNICIAN = 'technician';
    public const ROLE_USER = 'user';

    // Technician availability status constants
    public const TECH_STATUS_AVAILABLE = 'Available';
    public const TECH_STATUS_NOT_AVAILABLE = 'Not available';
    public const TECH_STATUS_BUSY = 'Busy';

    public function reports()
    {
        return $this->hasMany(Report::class, 'technician_id');
    }

    public function assignedRequests()
    {
        return $this->hasMany(Request::class, 'technician_id');
    }

    public function submittedRequests()
    {
        return $this->hasMany(Request::class, 'user_id');
    }

    public function managedPcs()
    {
        return $this->hasMany(PC::class, 'technician_id');
    }

    public function managedAccessories()
    {
        return $this->hasMany(Accessory::class, 'technician_id');
    }

    public function managedNetworkDevices()
    {
        return $this->hasMany(NetworkDevice::class, 'technician_id');
    }
}
