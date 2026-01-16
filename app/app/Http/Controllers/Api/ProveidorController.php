<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Proveidor;

class ProveidorController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/proveidors",
     *     summary="Llistar tots els proveidors",
     *     tags={"Proveidors"},
     *     @OA\Response(response=200, description="OK")
     * )
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
     * @OA\Post(
     *     path="/api/proveidors",
     *     summary="Crear un proveidor",
     *     tags={"Proveidors"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="nom", type="string", maxLength=100),
     *             @OA\Property(property="email", type="string", format="email", maxLength=150),
     *             @OA\Property(property="telefon", type="string", maxLength=20)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Proveidor creat"),
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

        $proveidor = Proveidor::create($request->only([
            'nom', 'correu', 'telefon'
        ]));

        return response()->json([
            'exit' => true,
            'dades' => $proveidor,
            'missatge' => 'Proveidor creat correctament'
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/proveidors/{id}",
     *     summary="Obtenir un proveidor per ID",
     *     tags={"Proveidors"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Proveidor trobat"),
     *     @OA\Response(response=404, description="Proveidor no trobat")
     * )
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
     * @OA\Put(
     *     path="/api/proveidors/{id}",
     *     summary="Actualitzar un proveidor",
     *     tags={"Proveidors"},
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
     *     @OA\Response(response=200, description="Proveidor actualitzat"),
     *     @OA\Response(response=404, description="Proveidor no trobat"),
     *     @OA\Response(response=422, description="Validació fallida")
     * )
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
            'correu' => 'nullable|string|email|max:150',
            'telefon' => 'nullable|string|max:20'
        ]);

        $proveidor->update($request->only([
            'nom', 'correu', 'telefon'
        ]));

        return response()->json([
            'exit' => true,
            'dades' => $proveidor,
            'missatge' => 'Proveidor actualitzat correctament'
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/proveidors/{id}",
     *     summary="Eliminar un proveidor",
     *     tags={"Proveidors"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Proveidor eliminat"),
     *     @OA\Response(response=404, description="Proveidor no trobat")
     * )
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
