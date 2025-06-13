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
        'birth_date',
        'gender',
        'address',
        'password',
        
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
            'birth_date' => 'date',
        ];
    }
     // Accesseur pour l'âge
    public function getAgeAttribute()
    {//Avoir l'âge de l'utilisateur grâce à sa date de naissance sans créer un attribut age 
        return $this->birth_date ? 
            Carbon::parse($this->birth_date)->age : null;
    }

    // Mutateur pour formater le téléphone
    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = preg_replace('/[^0-9+]/', '', $value);
    }
        //pour modifier l'affichage en anglais des options
     public function getGenderLabelAttribute()
    {
        return match($this->gender){
            'male' => 'Masculin',
            'female' => 'Féminin',
            default => 'Non spécifié'
        };
    }
}
