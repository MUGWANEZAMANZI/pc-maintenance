<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Building extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'location',
        'description',
    ];

    public function computerLabs()
    {
        return $this->hasMany(ComputerLab::class);
    }

    public function pcs()
    {
        return $this->hasMany(PC::class);
    }

    public function accessories()
    {
        return $this->hasMany(Accessory::class);
    }

    public function networkDevices()
    {
        return $this->hasMany(NetworkDevice::class);
    }
}
