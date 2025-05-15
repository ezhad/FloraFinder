<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plant extends Model
{
    /** @use HasFactory<\Database\Factories\PlantFactory> */
    use HasFactory;
    protected $fillable = [
        'common_name',
        'scientific_name',
        'family',
        'habitat',
        'lifespan',
        'care_tips',
    ];

    public function sightings()
    {
        return $this->hasMany(Sighting::class);
    }
   
}
