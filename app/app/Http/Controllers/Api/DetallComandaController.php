<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DetallComanda;

class DetallComandaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $detalls = DetallComanda::all();
        return response()->json([
            'exit' => true,
            'dades' => $detalls,
            'missatge' => 'Llistat de detalls de comanda'
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'comanda_id' => 'required|exists:comandes,id',
            'producte_id' => 'required|exists:productes,id',
            'quantitat' => 'required|integer'
        ]);

        $detall = DetallComanda::create($request->only([
            'comanda_id', 'producte_id', 'quantitat'
        ]));

        return response()->json([
            'exit' => true,
            'dades' => $detall,
            'missatge' => 'Detall de comanda creat correctament'
        ], 201);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $detall = DetallComanda::find($id);

        if (!$detall) {
            return response()->json([
                'exit' => false,
                'dades' => null,
                'missatge' => 'Detall de comanda no trobat'
            ], 404);
        }

        return response()->json([
            'exit' => true,
            'dades' => $detall,
            'missatge' => 'Detall de comanda trobat'
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $detall = DetallComanda::find($id);

        if (!$detall) {
            return response()->json([
                'exit' => false,
                'dades' => null,
                'missatge' => 'Detall de comanda no trobat'
            ], 404);
        }

        $request->validate([
            'comanda_id' => 'sometimes|required|exists:comandes,id',
            'producte_id' => 'sometimes|required|exists:productes,id',
            'quantitat' => 'sometimes|required|integer'
        ]);

        $detall->update($request->only([
            'comanda_id', 'producte_id', 'quantitat'
        ]));

        return response()->json([
            'exit' => true,
            'dades' => $detall,
            'missatge' => 'Detall de comanda actualitzat correctament'
        ]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $detall = DetallComanda::find($id);

        if (!$detall) {
            return response()->json([
                'exit' => false,
                'dades' => null,
                'missatge' => 'Detall de comanda no trobat'
            ], 404);
        }

        $detall->delete();

        return response()->json([
            'exit' => true,
            'dades' => null,
            'missatge' => 'Detall de comanda eliminat correctament'
        ]);
    }
}
