<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Planting_Reccomendation extends Model
{
    /** @use HasFactory<\Database\Factories\PlantingReccomendationFactory> */
    use HasFactory;

    protected $table = 'planting_reccomendations';
    protected $fillable = [
        'soil_type',
        'water_per_week'
        'sunlight_per_week'
        'temperature_range',
        'fertilizer_type',
        'humidity_level',
    ];
}
