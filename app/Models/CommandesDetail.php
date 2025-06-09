<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommandesDetail extends Model
{
     protected $table='commandes_detail';
     protected $guarded=[];
     public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    public function restaurant()
    {
        return $this->belongsTo(User::class, 'restaurant_id');
    }
}
