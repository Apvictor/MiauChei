<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sighted extends Model
{
    use HasFactory;

    protected $table = 'sighted';

    protected $fillable = ['last_seen', 'data_sighted', 'user_id', 'pet_id', 'user_pet'];

    public function pets()
    {
        return $this->belongsTo(Pets::class);
    }
}
