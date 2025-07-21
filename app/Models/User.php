<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon; // IMPORTANT importer la classe carbon
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
        'phone',
        'address',
        'avatar',
        'password',
        'role',
        
    ];

    public function getRoleLabelAttribute()
    {
        return match ($this->role) {
            'client' => 'Client',
            'pharmacie' => 'Pharmacie',
            'admin' => 'Administrateur',
            default => 'Rôle inconnu'
        };
    }


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

    // Mutateur pour formater le téléphone
    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = preg_replace('/[^0-9+]/', '', $value);
    }
    public function client(){
        return $this->hasOne(Client::class);
    }
    public function pharmacie(){
        return $this->hasOne(Pharmacie::class);
    }
      
}
