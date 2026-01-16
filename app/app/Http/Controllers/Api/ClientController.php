<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Client;

class ClientController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/clients",
     *     summary="Llistar tots els clients",
     *     tags={"Clients"},
     *     @OA\Response(response=200, description="OK")
     * )
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
     * @OA\Post(
     *     path="/api/clients",
     *     summary="Crear un client",
     *     tags={"Clients"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="nom", type="string", maxLength=100),
     *             @OA\Property(property="email", type="string", format="email", maxLength=150),
     *             @OA\Property(property="telefon", type="string", maxLength=20)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Client creat"),
     *     @OA\Response(response=422, description="Validació fallida")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:100',
            'correu' => 'nullable|string|email|max:150',
            'telefon' => 'nullable|string|max:20'
        ]);

        $client = Client::create($request->only([
            'nom', 'correu', 'telefon'
        ]));

        return response()->json([
            'exit' => true,
            'correu' => $client,
            'missatge' => 'Client creat correctament'
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/clients/{id}",
     *     summary="Obtenir un client per ID",
     *     tags={"Clients"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Client trobat"),
     *     @OA\Response(response=404, description="Client no trobat")
     * )
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
     * @OA\Put(
     *     path="/api/clients/{id}",
     *     summary="Actualitzar un client",
     *     tags={"Clients"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="nom", type="string", maxLength=100),
     *             @OA\Property(property="email", type="string", format="email", maxLength=150),
     *             @OA\Property(property="telefon", type="string", maxLength=20)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Client actualitzat"),
     *     @OA\Response(response=404, description="Client no trobat"),
     *     @OA\Response(response=422, description="Validació fallida")
     * )
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
            'correu' => 'nullable|string|email|max:150',
            'telefon' => 'nullable|string|max:20'
        ]);

        $client->update($request->only([
            'nom', 'correu', 'telefon'
        ]));

        return response()->json([
            'exit' => true,
            'dades' => $client,
            'missatge' => 'Client actualitzat correctament'
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/clients/{id}",
     *     summary="Eliminar un client",
     *     tags={"Clients"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Client eliminat"),
     *     @OA\Response(response=404, description="Client no trobat")
     * )
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
