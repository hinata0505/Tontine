<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $table = 'users';

    protected $primaryKey = 'id_user';

    public $timestamps = false;

    protected $fillable = [
        'code_connexion',
        'mot_de_passe',
        'role',
        'id_memb',
    ];

    protected $hidden = [
        'mot_de_passe',
    ];

    public function membre()
    {
        return $this->belongsTo(Membre::class, 'id_memb', 'id_memb');
    }
}