<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DetallComanda;

class DetallComandaController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/detalls-comandes",
     *     summary="Llistar tots els detalls de comandes",
     *     tags={"Detalls Comandes"},
     *     @OA\Response(
     *         response=200,
     *         description="Llistat de detalls",
     *         @OA\JsonContent(
     *             @OA\Property(property="exit", type="boolean"),
     *             @OA\Property(property="dades", type="array", items={"type": "object"})
     *         )
     *     )
     * )
     */
    public function index()
    {
        $detalls = DetallComanda::with('producte')->get();

        return response()->json([
            'exit' => true,
            'dades' => $detalls
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/detalls-comandes",
     *     summary="Crear un detall de comanda",
     *     tags={"Detalls Comandes"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="comanda_id", type="integer", description="ID de la comanda"),
     *             @OA\Property(property="producte_id", type="integer", description="ID del producte"),
     *             @OA\Property(property="quantitat", type="integer", minimum=1, description="Quantitat del producte")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Detall creat correctament",
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
            'comanda_id' => 'required|exists:comandes,id',
            'producte_id' => 'required|exists:productes,id',
            'quantitat' => 'required|integer|min:1'
        ]);

        $detall = DetallComanda::create([
            'comanda_id' => $request->comanda_id,
            'producte_id' => $request->producte_id,
            'quantitat' => $request->quantitat
        ]);

        return response()->json([
            'exit' => true,
            'dades' => $detall,
            'missatge' => 'Detall de comanda creat correctament'
        ], 201);
    }

    /**
     * @OA\Delete(
     *     path="/api/detalls-comandes/{id}",
     *     summary="Eliminar un detall de comanda",
     *     tags={"Detalls Comandes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detall eliminat correctament",
     *         @OA\JsonContent(
     *             @OA\Property(property="exit", type="boolean"),
     *             @OA\Property(property="missatge", type="string")
     *         )
     *     ),
     *     @OA\Response(response=404, description="Detall no trobat")
     * )
     */
    public function destroy($id)
    {
        $detall = DetallComanda::find($id);

        if (!$detall) {
            return response()->json([
                'exit' => false,
                'missatge' => 'Detall de comanda no trobat'
            ], 404);
        }

        $detall->delete();

        return response()->json([
            'exit' => true,
            'missatge' => 'Detall de comanda eliminat correctament'
        ]);
    }
}
