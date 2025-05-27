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

    // ✅ ينتمي إلى طبق
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }

    // ✅ ينتمي إلى مطعم
    public function restaurant()
    {
        return $this->belongsTo(User::class, 'restaurant_id');
    }
}
