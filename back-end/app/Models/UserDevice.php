<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserDevice extends Model
{
    use HasFactory;

    public $table = 'user_devices';

    public $fillable = [
        'user_id',
        'device_type',
        'os_player_id',
        'is_active',
    ];
}
