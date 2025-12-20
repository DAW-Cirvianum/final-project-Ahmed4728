<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comanda extends Model
{
    use HasFactory;
    protected $table = 'comandes';

    protected $fillable = [
        'data',
        'tipus',
        'user_id',
        'client_id',
        'proveidor_id'
    ];

    public function usuari()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function proveidor()
    {
        return $this->belongsTo(Proveidor::class, 'proveidor_id');
    }

    public function linies()
    {
        return $this->hasMany(DetallComanda::class, 'comanda_id');
    }
}
