<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Proveidor extends Model
{
    use HasFactory;
    protected $table = 'proveidors';

    protected $fillable = [
        'nom',
        'correu',
        'telefon'
    ];

    public function comandes()
    {
        return $this->hasMany(Comanda::class, 'proveidor_id');
    }
}
