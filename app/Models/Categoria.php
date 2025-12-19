<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class Categoria extends Model
{
    use HasFactory;
    protected $table = 'categories';

    protected $fillable = ['nom', 'descripcio'];

    public function productes()
    {
        return $this->hasMany(Producte::class, 'categoria_id');
    }
}
