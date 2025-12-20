<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proveidor;

class ProveidorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $proveidors = Proveidor::all();
        return response()->json([
            'exit' => true,
            'dades' => $proveidors,
            'missatge' => 'Llistat de proveidors'
        ]);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:100',
            'email' => 'nullable|string|email|max:150',
            'telefon' => 'nullable|string|max:20'
        ]);

        $proveidor = Proveidor::create($request->only([
            'nom', 'email', 'telefon'
        ]));

        return response()->json([
            'exit' => true,
            'dades' => $proveidor,
            'missatge' => 'Proveidor creat correctament'
        ], 201);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $proveidor = Proveidor::find($id);

        if (!$proveidor) {
            return response()->json([
                'exit' => false,
                'dades' => null,
                'missatge' => 'Proveidor no trobat'
            ], 404);
        }

        return response()->json([
            'exit' => true,
            'dades' => $proveidor,
            'missatge' => 'Proveidor trobat'
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $proveidor = Proveidor::find($id);

        if (!$proveidor) {
            return response()->json([
                'exit' => false,
                'dades' => null,
                'missatge' => 'Proveidor no trobat'
            ], 404);
        }

        $request->validate([
            'nom' => 'sometimes|required|string|max:100',
            'email' => 'nullable|string|email|max:150',
            'telefon' => 'nullable|string|max:20'
        ]);

        $proveidor->update($request->only([
            'nom', 'email', 'telefon'
        ]));

        return response()->json([
            'exit' => true,
            'dades' => $proveidor,
            'missatge' => 'Proveidor actualitzat correctament'
        ]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $proveidor = Proveidor::find($id);

        if (!$proveidor) {
            return response()->json([
                'exit' => false,
                'dades' => null,
                'missatge' => 'Proveidor no trobat'
            ], 404);
        }

        $proveidor->delete();

        return response()->json([
            'exit' => true,
            'dades' => null,
            'missatge' => 'Proveidor eliminat correctament'
        ]);
    }
}
