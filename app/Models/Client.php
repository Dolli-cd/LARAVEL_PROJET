<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon; // IMPORTANT importer la classe carbon
class Client extends Model
{
    protected $fillable=[
        'user_id',
        'birth_date',
        'gender',
    ];
        protected function casts(): array
    {
        return [
            'birth_date' => 'date',
        ];
    }

     // Accesseur pour l'âge
    public function getAgeAttribute()
    {//Avoir l'âge de l'utilisateur grâce à sa date de naissance sans créer un attribut age 
        return $this->birth_date ? 
            Carbon::parse($this->birth_date)->age : null;
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
    // pour hériter de user
    public function user(){
        return $this->belongsTo(User::class);
    }

        // un client fait plusieurs réservations
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
//Un client fait plusieurs commandes

    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }
    // il a plusieurs ordonnances
    public function ordonnances()
    {
        return $this->hasMany(Ordonnance::class);
    }
    public function paiements()
    {
        return $this->hasMany(Paiement::class);
    }
    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

     public function geolocalisation(){
        return $this->hasOne(Geolocalisation::class);
    }
}
