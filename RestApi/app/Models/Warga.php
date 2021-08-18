<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warga extends Model
{
    protected $table = 'warga';
    protected $primaryKey = 'warga_id';
    protected $guarded = [
        'warga_id'
    ];
    protected $with = ['dusun'];

    public function dusun()
    {
        return $this->belongsTo(Dusun::class, 'dusun_id', 'dusun_id');
    }
}
