<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Distribution extends Model
{
    protected $table = 'distributions';

    protected $primaryKey = 'id_distrib';

    public $timestamps = false;

    protected $fillable = [
        'mois',
        'montant_remis',
        'date_distribution',
        'id_memb',
    ];

    protected $casts = [
        'mois' => 'date',
        'date_distribution' => 'datetime',
        'montant_remis' => 'decimal:2',
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