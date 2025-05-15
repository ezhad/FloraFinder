<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plant_Category extends Model
{
    /** @use HasFactory<\Database\Factories\PlantCategoryFactory> */
    use HasFactory;

    protected $table = 'plant_categories';
    protected $fillable = [
        'name',
    ];
}
