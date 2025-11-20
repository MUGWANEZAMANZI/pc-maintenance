<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NetworkDevice extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'brand', 'registration_year', 'technician_id'
    ];

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function reports()
    {
        return $this->morphMany(Report::class, 'reportable');
    }
}
