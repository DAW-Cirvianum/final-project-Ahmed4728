<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producte;

class ProducteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productes = Producte::all();
        return response()->json([
            'exit' => true,
            'dades' => $productes,
            'missatge' => 'Llistat de productes'
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:100',
            'referencia' => 'nullable|string|max:100',
            'descripcio' => 'nullable|string|max:255',
            'quantitat' => 'required|integer',
            'categoria_id' => 'nullable|exists:categories,id'
        ]);

        $producte = Producte::create($request->only([
            'nom', 'referencia', 'descripcio', 'quantitat', 'categoria_id'
        ]));

        return response()->json([
            'exit' => true,
            'dades' => $producte,
            'missatge' => 'Producte creat correctament'
        ], 201);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $producte = Producte::find($id);

        if (!$producte) {
            return response()->json([
                'exit' => false,
                'dades' => null,
                'missatge' => 'Producte no trobat'
            ], 404);
        }

        return response()->json([
            'exit' => true,
            'dades' => $producte,
            'missatge' => 'Producte trobat'
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $producte = Producte::find($id);

        if (!$producte) {
            return response()->json([
                'exit' => false,
                'dades' => null,
                'missatge' => 'Producte no trobat'
            ], 404);
        }

        $request->validate([
            'nom' => 'sometimes|required|string|max:100',
            'referencia' => 'nullable|string|max:100',
            'descripcio' => 'nullable|string|max:255',
            'quantitat' => 'sometimes|required|integer',
            'categoria_id' => 'nullable|exists:categories,id'
        ]);

        $producte->update($request->only([
            'nom', 'referencia', 'descripcio', 'quantitat', 'categoria_id'
        ]));

        return response()->json([
            'exit' => true,
            'dades' => $producte,
            'missatge' => 'Producte actualitzat correctament'
        ]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $producte = Producte::find($id);

        if (!$producte) {
            return response()->json([
                'exit' => false,
                'dades' => null,
                'missatge' => 'Producte no trobat'
            ], 404);
        }

        $producte->delete();

        return response()->json([
            'exit' => true,
            'dades' => null,
            'missatge' => 'Producte eliminat correctament'
        ]);
    }
}
