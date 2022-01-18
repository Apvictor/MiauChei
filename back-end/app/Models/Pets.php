<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pets extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'species',
        'sex',
        'breed',
        'size',
        'predominant_color',
        'secondary_color',
        'physical_details',
        'date_disappearance',
        'photo',
        'uuid',
        'fk_user',
        'fk_status',
    ];
}
