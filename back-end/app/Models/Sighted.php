<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sighted extends Model
{
    use HasFactory;

    protected $fillable = ['last_seen', 'data_sighted', 'fk_user', 'fk_pet'];
}
