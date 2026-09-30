<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cotisation extends Model
{
    protected $table = 'cotisations';

    protected $primaryKey = 'id_coti';

    public $timestamps = false;

    protected $fillable = [
        'mois',
        'montant',
        'date_versement',
        'id_memb',
    ];

    protected $casts = [
        'mois' => 'date',
        'date_versement' => 'datetime',
        'montant' => 'decimal:2',
    ];

    public function membre(): BelongsTo
    {
        return $this->belongsTo(
            Membre::class,
            'id_memb',
            'id_memb'
        );
    }
}