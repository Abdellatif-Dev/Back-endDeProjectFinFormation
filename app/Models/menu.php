<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class menu extends Model
{
    protected $guarded=[];
     public function restaurant()
    {
        return $this->belongsTo(User::class, 'restaurant_id');
    }

    // ✅ كل طبق يمكن أن يكون موجودًا في تفاصيل طلبات
    public function commandeDetails()
    {
        return $this->hasMany(CommandesDetail::class);
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
