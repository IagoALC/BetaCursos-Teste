<?php

namespace App\Http\Controllers;

use App\Models\Categorias;
use App\Models\ProdutosCategorias;
use Illuminate\Http\Request;

class CategoriasController extends Controller
{
    public function lists(Request $request)
    {
        $input = $request->all();
        $orderBy = $input['orderBy'] ?? 'id';
        $orderMode = $input['orderMode'] ?? 'desc';
        $input['search'] = $input['search'] ?? '';
        $categorias = Categorias::where('nome', 'like', '%' . $input['search'] . '%')
        ->orderBy($orderBy, $orderMode)
        ->paginate(6);
        return $categorias;
    }

    public function create(Request $request)
    {
        $input = $request->all();
        $categoria = Categorias::create([
            'nome' => $input['nome'],
            'cor' => $input['cor']
        ]);
        return $categoria;
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();
        $categoria = Categorias::find($id);
        $categoria->update($input);
        return $categoria;
    }

    public function delete($id)
    {
        $categoria = Categorias::find($id);
        ProdutosCategorias::where('categoria_id', $id)->delete();
        $categoria->delete();
        return $categoria;
    }
}
