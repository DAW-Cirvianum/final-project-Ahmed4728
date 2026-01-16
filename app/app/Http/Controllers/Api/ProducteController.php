<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Producte;

class ProducteController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/productes",
     *     summary="Llistar tots els productes",
     *     tags={"Productes"},
     *     @OA\Response(
     *         response=200,
     *         description="Llistat de productes",
     *         @OA\JsonContent(
     *             @OA\Property(property="exit", type="boolean"),
     *             @OA\Property(property="dades", type="array", items={"type": "object"}),
     *             @OA\Property(property="missatge", type="string")
     *         )
     *     )
     * )
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
     * @OA\Get(
     *     path="/api/productes/{id}",
     *     summary="Obtenir un producte per ID",
     *     tags={"Productes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Producte trobat",
     *         @OA\JsonContent(
     *             @OA\Property(property="exit", type="boolean"),
     *             @OA\Property(property="dades", type="object"),
     *             @OA\Property(property="missatge", type="string")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Producte no trobat")
     * )
     */
    public function show($id)
    {
        $producte = Producte::find($id);

        if (!$producte) {
            return response()->json([
                'exit' => false,
                'missatge' => 'Producte no trobat'
            ], 404);
        }

        return response()->json([
            'exit' => true,
            'dades' => $producte,
            'missatge' => 'Producte trobat'
        ]);
    }

    // =========================
    // CREAR UN PRODUCTO
    // =========================
    /**
     * @OA\Post(
     *     path="/api/productes",
     *     summary="Crear un producte",
     *     tags={"Productes"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="nom", type="string", maxLength=255, description="Nom del producte"),
     *             @OA\Property(property="referencia", type="string", maxLength=255, description="Referència única"),
     *             @OA\Property(property="descripcio", type="string", nullable=true, description="Descripció"),
     *             @OA\Property(property="quantitat", type="integer", minimum=0, description="Quantitat inicial"),
     *             @OA\Property(property="categoria_id", type="integer", description="ID de la categoria")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Producte creat correctament",
     *         @OA\JsonContent(
     *             @OA\Property(property="exit", type="boolean"),
     *             @OA\Property(property="dades", type="object"),
     *             @OA\Property(property="missatge", type="string")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Validació fallida")
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:255',
            'referencia' => 'required|string|max:255|unique:productes',
            'descripcio' => 'nullable|string',
            'quantitat' => 'required|integer|min:0',
            'categoria_id' => 'required|exists:categories,id'
        ]);

        $producte = Producte::create($request->all());

        return response()->json([
            'exit' => true,
            'dades' => $producte,
            'missatge' => 'Producte creat correctament'
        ], 201);
    }


    /**
     * @OA\Put(
     *     path="/api/productes/{id}",
     *     summary="Actualitzar un producte",
     *     tags={"Productes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="nom", type="string", maxLength=255),
     *             @OA\Property(property="referencia", type="string", maxLength=255),
     *             @OA\Property(property="descripcio", type="string", nullable=true),
     *             @OA\Property(property="quantitat", type="integer", minimum=0),
     *             @OA\Property(property="categoria_id", type="integer")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Producte actualitzat correctament",
     *         @OA\JsonContent(
     *             @OA\Property(property="exit", type="boolean"),
     *             @OA\Property(property="dades", type="object"),
     *             @OA\Property(property="missatge", type="string")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Producte no trobat"),
     *     @OA\Response(response=422, description="Validació fallida")
     * )
     */
    public function update(Request $request, $id)
    {
        $producte = Producte::find($id);

        if (!$producte) {
            return response()->json([
                'exit' => false,
                'missatge' => 'Producte no trobat'
            ], 404);
        }

        $request->validate([
            'nom' => 'sometimes|required|string|max:255',
            'referencia' => "sometimes|required|string|max:255|unique:productes,referencia,$id",
            'descripcio' => 'nullable|string',
            'quantitat' => 'sometimes|required|integer|min:0',
            'categoria_id' => 'sometimes|required|exists:categories,id'
        ]);

        $producte->update($request->all());

        return response()->json([
            'exit' => true,
            'dades' => $producte,
            'missatge' => 'Producte actualitzat correctament'
        ]);
    }


    /**
     * @OA\Delete(
     *     path="/api/productes/{id}",
     *     summary="Eliminar un producte",
     *     tags={"Productes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Producte eliminat correctament",
     *         @OA\JsonContent(
     *             @OA\Property(property="exit", type="boolean"),
     *             @OA\Property(property="missatge", type="string")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Producte no trobat"),
     *     @OA\Response(response=400, description="No es pot eliminar: hi ha comandes associades")
     * )
     */
    public function destroy($id)
    {
        $producte = Producte::find($id);

        if (!$producte) {
            return response()->json([
                'exit' => false,
                'missatge' => 'Producte no trobat'
            ], 404);
        }

        if ($producte->detalls_comanda()->count() > 0) {
            return response()->json([
                'exit' => false,
                'missatge' => 'No es pot eliminar aquest producte, hi ha comandes associades'
            ], 400);
        }

        try {
            $producte->delete();
            return response()->json([
                'exit' => true,
                'missatge' => 'Producte eliminat correctament'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'exit' => false,
                'missatge' => 'Error eliminant el producte',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
