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
        'user_id',
        'status_id',
    ];

    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function sighted()
    {
        return $this->hasMany(Sighted::class, 'pet_id');
    }
}
