<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Accessory extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'brand', 'device_name', 'registration_year', 'health',
        'technician_id', 'computer_lab_id', 'building_id'
    ];

    // Health status constants
    public const HEALTH_HEALTHY = 'healthy';
    public const HEALTH_MALFUNCTIONING = 'malfunctioning';
    public const HEALTH_DEAD = 'dead';

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function computerLab()
    {
        return $this->belongsTo(ComputerLab::class, 'computer_lab_id');
    }

    public function building()
    {
        return $this->belongsTo(Building::class, 'building_id');
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }

    public function requests()
    {
        return $this->hasMany(Request::class, 'accessory_id');
    }
}
