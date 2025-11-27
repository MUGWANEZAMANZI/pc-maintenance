<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;

    protected $fillable = [
        'first_name','last_name','email','telephone','date','unit','status','request_type','technician_id','user_id','description',
        'department_id','computer_lab_id','pc_id','accessory_id','network_device_id'
    ];

    public const STATUS_PENDING = 'Pending';
    public const STATUS_ASSIGNED = 'Technician Assigned';
    public const STATUS_FIXED = 'Fixed';
    public const STATUS_NOT_FIXED = 'Not fixed';

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function computerLab()
    {
        return $this->belongsTo(ComputerLab::class);
    }

    public function pc()
    {
        return $this->belongsTo(PC::class);
    }

    public function accessory()
    {
        return $this->belongsTo(Accessory::class);
    }

    public function networkDevice()
    {
        return $this->belongsTo(NetworkDevice::class);
    }
}
