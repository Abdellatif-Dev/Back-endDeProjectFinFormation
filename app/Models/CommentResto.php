<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommentResto extends Model
{
    protected $table='comments_resto';
     protected $guarded=[];
     public function user()
    {
        return $this->belongsTo(User::class);
    }
}
