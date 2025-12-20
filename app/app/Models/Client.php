<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Client extends Model
{
    use HasFactory;
    protected $table = 'clients';

    protected $fillable = [
        'nom',
        'correu',
        'telefon'
    ];

    public function comandes()
    {
        return $this->hasMany(Comanda::class, 'client_id');
    }
}
