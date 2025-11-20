<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PC extends Model
{
    use HasFactory;

    // Explicitly define table name because Laravel infers 'p_c_s' from 'PC'.
    protected $table = 'pcs';

    protected $fillable = [
        'specifications', 'hdd', 'ram', 'os', 'brand', 'registration_year', 'technician_id'
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
