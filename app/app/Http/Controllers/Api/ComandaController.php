<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Comanda;
use App\Models\Producte;

class ComandaController extends Controller
{

    /**
     * @OA\Get(
     *     path="/api/comandes",
     *     summary="Llista totes les comandes",
     *     tags={"Comandes"},
     *     @OA\Response(
     *         response=200,
     *         description="Llistat de comandes obtingut correctament"
     *     )
     * )
     */
    public function index()
    {
        $comandes = Comanda::with([
            'client',
            'proveidor',
            'detallsComanda.producte'
        ])->get();

        return response()->json([
            'exit' => true,
            'dades' => $comandes
        ]);
    }


    /**
     * @OA\Get(
     *     path="/api/comandes/{id}",
     *     summary="Obté una comanda pel seu identificador",
     *     tags={"Comandes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Identificador de la comanda",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Comanda trobada correctament"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Comanda no trobada"
     *     )
     * )
     */
    public function show(Comanda $comande)
    {
        $comande->load('client', 'proveidor', 'detallsComanda.producte');

        return response()->json($comande);
    }


    /**
     * @OA\Post(
     *     path="/api/comandes",
     *     summary="Crea una nova comanda",
     *     tags={"Comandes"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             required={"data","tipus","detalls"},
     *             @OA\Property(
     *                 property="data",
     *                 type="string",
     *                 format="date",
     *                 description="Data de la comanda"
     *             ),
     *             @OA\Property(
     *                 property="tipus",
     *                 type="string",
     *                 enum={"entrada","sortida"},
     *                 description="Tipus de comanda"
     *             ),
     *             @OA\Property(
     *                 property="client_id",
     *                 type="integer",
     *                 nullable=true,
     *                 description="Identificador del client (opcional)"
     *             ),
     *             @OA\Property(
     *                 property="proveidor_id",
     *                 type="integer",
     *                 nullable=true,
     *                 description="Identificador del proveïdor (opcional)"
     *             ),
     *             @OA\Property(
     *                 property="detalls",
     *                 type="array",
     *                 description="Detalls de la comanda",
     *                 @OA\Items(
     *                     type="object",
     *                     required={"producte_id","quantitat"},
     *                     @OA\Property(
     *                         property="producte_id",
     *                         type="integer",
     *                         description="Identificador del producte"
     *                     ),
     *                     @OA\Property(
     *                         property="quantitat",
     *                         type="integer",
     *                         minimum=1,
     *                         description="Quantitat del producte"
     *                     )
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Comanda creada correctament"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validació o falta d’estoc"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'data' => 'required|date',
            'tipus' => 'required|in:entrada,sortida',
            'client_id' => 'nullable|exists:clients,id',
            'proveidor_id' => 'nullable|exists:proveidors,id',
            'detalls' => 'required|array|min:1',
            'detalls.*.producte_id' => 'required|exists:productes,id',
            'detalls.*.quantitat' => 'required|integer|min:1',
        ]);

        return DB::transaction(function () use ($request) {

            $comanda = Comanda::create([
                'data' => $request->data,
                'tipus' => $request->tipus,
                'client_id' => $request->client_id,
                'proveidor_id' => $request->proveidor_id,
                'user_id' => Auth::id(),
            ]);

            foreach ($request->detalls as $detall) {
                $producte = Producte::findOrFail($detall['producte_id']);

                if ($request->tipus === 'sortida' && $detall['quantitat'] > $producte->quantitat) {
                    abort(422, "No hi ha prou estoc del producte {$producte->nom}");
                }

                $comanda->detallsComanda()->create([
                    'producte_id' => $detall['producte_id'],
                    'quantitat' => $detall['quantitat'],
                ]);

                if ($request->tipus === 'sortida') {
                    $producte->quantitat -= $detall['quantitat'];
                } else {
                    $producte->quantitat += $detall['quantitat'];
                }

                $producte->save();
            }

            return response()->json([
                'exit' => true,
                'dades' => $comanda->load('detallsComanda.producte'),
                'missatge' => 'Comanda creada correctament'
            ], 201);
        });
    }


    /**
     * @OA\Delete(
     *     path="/api/comandes/{id}",
     *     summary="Elimina una comanda",
     *     tags={"Comandes"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Identificador de la comanda",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Comanda eliminada correctament"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Comanda no trobada"
     *     )
     * )
     */
    public function destroy($id)
    {
        $comanda = Comanda::find($id);

        if (!$comanda) {
            return response()->json([
                'exit' => false,
                'missatge' => 'Comanda no trobada'
            ], 404);
        }

        $comanda->delete();

        return response()->json([
            'exit' => true,
            'missatge' => 'Comanda eliminada correctament'
        ]);
    }
}
