<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConservationStatus extends Model
{
    /** @use HasFactory<\Database\Factories\ConservationStatusFactory> */
    use HasFactory;

    protected $table = 'conservation_statuses';
    protected $fillable = [
        'name',
        'description',
    ];
    
}
