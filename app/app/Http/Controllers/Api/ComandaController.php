<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Comanda;

class ComandaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $comandes = Comanda::all();
        return response()->json([
            'exit' => true,
            'dades' => $comandes,
            'missatge' => 'Llistat de comandes'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'data' => 'required|date',
            'tipus' => 'required|in:purchase,sale',
            'user_id' => 'required|exists:users,id',
            'client_id' => 'nullable|exists:clients,id',
            'proveidor_id' => 'nullable|exists:proveidors,id'
        ]);

        $comanda = Comanda::create($request->only([
            'data', 'tipus', 'user_id', 'client_id', 'proveidor_id'
        ]));

        return response()->json([
            'exit' => true,
            'dades' => $comanda,
            'missatge' => 'Comanda creada correctament'
        ], 201);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $comanda = Comanda::find($id);

        if (!$comanda) {
            return response()->json([
                'exit' => false,
                'dades' => null,
                'missatge' => 'Comanda no trobada'
            ], 404);
        }

        return response()->json([
            'exit' => true,
            'dades' => $comanda,
            'missatge' => 'Comanda trobada'
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $comanda = Comanda::find($id);

        if (!$comanda) {
            return response()->json([
                'exit' => false,
                'dades' => null,
                'missatge' => 'Comanda no trobada'
            ], 404);
        }

        $request->validate([
            'data' => 'sometimes|required|date',
            'tipus' => 'sometimes|required|in:purchase,sale',
            'user_id' => 'sometimes|required|exists:users,id',
            'client_id' => 'nullable|exists:clients,id',
            'proveidor_id' => 'nullable|exists:proveidors,id'
        ]);

        $comanda->update($request->only([
            'data', 'tipus', 'user_id', 'client_id', 'proveidor_id'
        ]));

        return response()->json([
            'exit' => true,
            'dades' => $comanda,
            'missatge' => 'Comanda actualitzada correctament'
        ]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $comanda = Comanda::find($id);

        if (!$comanda) {
            return response()->json([
                'exit' => false,
                'dades' => null,
                'missatge' => 'Comanda no trobada'
            ], 404);
        }

        $comanda->delete();

        return response()->json([
            'exit' => true,
            'dades' => null,
            'missatge' => 'Comanda eliminada correctament'
        ]);
    }
}
