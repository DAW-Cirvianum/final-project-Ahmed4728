<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clients = Client::all();
        return response()->json([
            'exit' => true,
            'dades' => $clients,
            'missatge' => 'Llistat de clients'
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

        $client = Client::create($request->only([
            'nom', 'email', 'telefon'
        ]));

        return response()->json([
            'exit' => true,
            'dades' => $client,
            'missatge' => 'Client creat correctament'
        ], 201);
    }
    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $client = Client::find($id);

        if (!$client) {
            return response()->json([
                'exit' => false,
                'dades' => null,
                'missatge' => 'Client no trobat'
            ], 404);
        }

        return response()->json([
            'exit' => true,
            'dades' => $client,
            'missatge' => 'Client trobat'
        ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $client = Client::find($id);

        if (!$client) {
            return response()->json([
                'exit' => false,
                'dades' => null,
                'missatge' => 'Client no trobat'
            ], 404);
        }

        $request->validate([
            'nom' => 'sometimes|required|string|max:100',
            'email' => 'nullable|string|email|max:150',
            'telefon' => 'nullable|string|max:20'
        ]);

        $client->update($request->only([
            'nom', 'email', 'telefon'
        ]));

        return response()->json([
            'exit' => true,
            'dades' => $client,
            'missatge' => 'Client actualitzat correctament'
        ]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $client = Client::find($id);

        if (!$client) {
            return response()->json([
                'exit' => false,
                'dades' => null,
                'missatge' => 'Client no trobat'
            ], 404);
        }

        $client->delete();

        return response()->json([
            'exit' => true,
            'dades' => null,
            'missatge' => 'Client eliminat correctament'
        ]);
    }
}
