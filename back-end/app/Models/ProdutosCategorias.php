<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProdutosCategorias extends Model
{
    use HasFactory;

    protected $table = 'produtos_categorias';

    public $timestamps = false;

    public $fillable = [
        'produto_id',
        'categoria_id'
    ];

        /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function produtos()
    {
        return $this->belongsTo(\App\Models\Produtos::class, 'produto_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     **/
    public function categorias()
    {
        return $this->belongsTo(\App\Models\Categorias::class, 'categoria_id');
    }
}
