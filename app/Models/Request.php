<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name','last_name','email','telephone','date','unit','status','request_type','technician_id'
    ];

    public const STATUS_PENDING = 'Pending';
    public const STATUS_ASSIGNED = 'Technician Assigned';
    public const STATUS_FIXED = 'Fixed';
    public const STATUS_NOT_FIXED = 'Not fixed';

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }
}
