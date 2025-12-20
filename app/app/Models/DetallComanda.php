<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DetallComanda extends Model
{
    use HasFactory;
    protected $table = 'detalls_comanda';

    protected $fillable = [
        'comanda_id',
        'producte_id',
        'quantitat'
    ];

    public function comanda()
    {
        return $this->belongsTo(Comanda::class, 'comanda_id');
    }

    public function producte()
    {
        return $this->belongsTo(Producte::class, 'producte_id');
    }
}
