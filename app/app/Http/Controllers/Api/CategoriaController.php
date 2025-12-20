<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categoria;


class CategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Categoria::all();
        return response()->json([
        'success' => true,
        'data' => $categories
    ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
            $request->validate([
            'nom' => 'required|string|max:100',
            'descripcio' => 'nullable|string|max:255'
        ]);

        $categoria = Categoria::create([
            'nom' => $request->nom,
            'descripcio' => $request->descripcio
        ]);

        return response()->json([
            'exit' => true,
            'dades' => $categoria,
            'missatge' => 'Categoria creada correctament'
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
            
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json([
                'exit' => false,
                'dades' => null,
                'missatge' => 'Categoria no trobada'
            ], 404);
        }

        return response()->json([
            'exit' => true,
            'dades' => $categoria,
            'missatge' => 'Categoria trobada'
        ]);
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json([
                'exit' => false,
                'dades' => null,
                'missatge' => 'Categoria no trobada'
            ], 404);
        }

        $request->validate([
            'nom' => 'sometimes|required|string|max:100',
            'descripcio' => 'nullable|string|max:255'
        ]);

        $categoria->update($request->only(['nom', 'descripcio']));

        return response()->json([
            'exit' => true,
            'dades' => $categoria,
            'missatge' => 'Categoria actualitzada correctament'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json([
                'exit' => false,
                'dades' => null,
                'missatge' => 'Categoria no trobada'
            ], 404);
        }

        $categoria->delete();

        return response()->json([
            'exit' => true,
            'dades' => null,
            'missatge' => 'Categoria eliminada correctament'
        ]);
    }
}
