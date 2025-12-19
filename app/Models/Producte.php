<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Producte extends Model
{
    use HasFactory;
     protected $table = 'productes';

    protected $fillable = [
        'nom',
        'referencia',
        'descripcio',
        'quantitat',
        'categoria_id'
    ];

    public function categoria()
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }
}
