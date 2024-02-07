<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produtos extends Model
{
    use HasFactory;

    protected $table = 'produtos';

    public $fillable = [
        'nome',
        'descricao',
        'preco'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     **/
    public function ProdutosCategorias()
    {
        return $this->hasMany(\App\Models\ProdutosCategorias::class, 'produto_id');
    }

    //Relation belongsToMany
    function categorias() {
        return $this->belongsToMany(\App\Models\Categorias::class, 'produtos_categorias', 'produto_id', 'categoria_id')
            ->withPivot([
                'id',
                'categoria_id',
                'produto_id'
            ]);
    }
}
