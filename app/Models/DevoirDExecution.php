<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DevoirDExecution extends Model
{
    protected $table = 'devoir_d_execution';
    protected $guarded = [];
    public function restaurant()
    {
        return $this->belongsTo(User::class, 'restaurant_id');
    }
}
