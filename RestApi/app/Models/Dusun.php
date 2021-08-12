<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Dusun extends Model
{
    protected $primaryKey = 'dusun_id';
    protected $guarded = [
        'dusun_id'
    ];
}
