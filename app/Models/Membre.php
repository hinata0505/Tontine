<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membre extends Model
{
    // Indiquer à Laravel le nom exact de la clé primaire
    protected $primaryKey = 'id_memb';

    // Autoriser le Mass Assignment pour la méthode create()
    protected $fillable = [
        'nom_memb',
        'telephone',
        'ordre_tour',
        'frequence_cotisation',
        'montant_cotisation',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}