<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class comment extends Model
{
    protected $guarded=[];
     public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Chaque commentaire est lié à un plat
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}
