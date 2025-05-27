<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'image',
        'image_resto'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }

    // ✅ إذا كان صاحب مطعم، فالمستخدم لديه عدة أطباق (menus)
    public function menus()
    {
        return $this->hasMany(Menu::class, 'restaurant_id');
    }

    // ✅ إذا كان صاحب مطعم، فله تفاصيل عدة طلبات (commande_details)
    public function commandeDetails()
    {
        return $this->hasMany(CommandesDetail::class, 'restaurant_id');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    // Un utilisateur peut faire plusieurs commentaires sur les restaurants
    public function commentsResto()
    {
        return $this->hasMany(CommentResto::class);
    }
    public function devoirs()
    {
        return $this->hasMany(DevoirDExecution::class, 'restaurant_id');
    }
}
