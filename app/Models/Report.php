<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'status', 'date', 'location', 'technician_id', 'notes'
    ];

    public const STATUS_WORKING = 'Working';
    public const STATUS_NOT_WORKING = 'Not working';
    public const STATUS_DAMAGED = 'Damaged';
    public const STATUS_OLD = 'Old';

    public function technician()
    {
        return $this->belongsTo(User::class, 'technician_id');
    }

    public function reportable()
    {
        return $this->morphTo();
    }
}
