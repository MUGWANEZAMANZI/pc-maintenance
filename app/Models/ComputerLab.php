<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ComputerLab extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'building_id',
        'location',
        'capacity',
        'description',
    ];

    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    public function pcs()
    {
        return $this->hasMany(PC::class, 'computer_lab_id');
    }

    public function requests()
    {
        return $this->hasMany(Request::class);
    }
}
