<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Membre extends Model
{
    protected $table = 'membres';

    protected $primaryKey = 'id_memb';

    public $timestamps = false;

    protected $fillable = [
        'nom_memb',
        'telephone',
        'ordre_tour',
        'frequence_cotisation',
        'montant_cotisation',
    ];

    protected $casts = [
        'montant_cotisation' => 'decimal:2',
        'ordre_tour' => 'integer',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id_memb', 'id_memb');
    }

    public function cotisations(): HasMany
    {
        return $this->hasMany(
            Cotisation::class,
            'id_memb',
            'id_memb'
        );
    }

    public function distributions(): HasMany
    {
        return $this->hasMany(
            Distribution::class,
            'id_memb',
            'id_memb'
        );
    }
}