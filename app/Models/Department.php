<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'location',
    ];

    public function computerLabs()
    {
        return $this->hasMany(ComputerLab::class);
    }

    public function requests()
    {
        return $this->hasMany(Request::class);
    }
}
