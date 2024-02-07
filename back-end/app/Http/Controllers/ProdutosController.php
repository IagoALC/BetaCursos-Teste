<?php

namespace App\Http\Controllers;

use App\Models\Categorias;
use App\Models\Produtos;
use App\Models\ProdutosCategorias;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProdutosController extends Controller
{
    public function lists(Request $request)
    {
        $input = $request->all();
        $orderBy = $input['orderBy'] ?? 'id';
        $orderMode = $input['orderMode'] ?? 'desc';
        $produtos = Produtos::with('categorias')
        ->select('produtos.*')
        ->addSelect(['categoria_nome' => Categorias::select('nome')
            ->join('produtos_categorias', 'categorias.id', '=', 'produtos_categorias.categoria_id')
            ->whereColumn('produtos_categorias.produto_id', 'produtos.id')
            ->orderBy('nome', 'asc')
            ->limit(1)
        ])
        ->where('produtos.nome', 'like', '%' . $input['search'] . '%')
        ->orderByRaw($orderBy === 'category' ? 'categoria_nome ' . $orderMode : 'produtos.' . $orderBy . ' ' . $orderMode)
        ->paginate(6);
    
        return $produtos;
    }

    public function create(Request $request)
    {
        try {
        DB::beginTransaction();
        $input = $request->all();
        $categorias = $input['categorias'];
        unset($input['categorias']);
        $produto = Produtos::create($input);
        foreach ($categorias as $categoria) {
            ProdutosCategorias::create([
                'produto_id' => $produto->id,
                'categoria_id' => $categoria['id']
            ]);
        }
        DB::commit();
        return $produto;
        } catch (\Exception $e) {
            DB::rollBack();
            return response($e->getMessage(), 500);
        }
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
        $produto = Produtos::find($id);
        $oldCategorias = ProdutosCategorias::where('produto_id', $id)->get()->pluck('categoria_id')->toArray();
        $newCategorias = array_column($input['categorias'], 'id');
        unset($input['categorias']);
        $categoriasToDelete = array_diff($oldCategorias, $newCategorias);
        $categoriasToInsert = array_diff($newCategorias, $oldCategorias);
        foreach ($categoriasToDelete as $categoria) {
            ProdutosCategorias::where('produto_id', $id)->where('categoria_id', $categoria)->delete();
        }
        foreach ($categoriasToInsert as $categoria) {
            ProdutosCategorias::create([
                'produto_id' => $id,
                'categoria_id' => $categoria
            ]);
        }
        $produto->update($input);
        return $produto;
    }

    public function delete($id)
    {
        $produto = Produtos::find($id);
        ProdutosCategorias::where('produto_id', $id)->delete();
        $produto->delete();
        return $produto;
    }
}
