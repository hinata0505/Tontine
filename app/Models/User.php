<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    protected $primaryKey = 'id_user';

    public $timestamps = true;

    protected $fillable = [
        'code_connexion',
        'mot_de_passe',
        'role',
        'id_memb',
    ];

    protected $hidden = [
        'mot_de_passe',
    ];

    protected $casts = [
        'mot_de_passe' => 'hashed',
    ];

    public function membre(): BelongsTo
    {
        return $this->belongsTo(
            Membre::class,
            'id_memb',
            'id_memb'
        );
    }

    public function getAuthPassword()
    {
        return $this->mot_de_passe;
    }
}