<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categorias extends Model
{
    use HasFactory;

    protected $table = 'categorias';

    public $timestamps = false;

    public $fillable = [
        'nome',
        'cor'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ProdutosCategorias()
    {
        return $this->hasMany(\App\Models\ProdutosCategorias::class, 'categoria_id');
    }
}
