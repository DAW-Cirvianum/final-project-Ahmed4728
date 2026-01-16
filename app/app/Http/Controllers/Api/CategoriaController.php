<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Categoria;

class CategoriaController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/categories",
     *     summary="Llista totes les categories",
     *     tags={"Categories"},
     *     @OA\Response(
     *         response=200,
     *         description="Llista de categories obtinguda correctament"
     *     )
     * )
     */
    public function index()
    {
        $categories = Categoria::all();

        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/categories",
     *     summary="Crea una nova categoria",
     *     tags={"Categories"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="nom",
     *                 type="string",
     *                 maxLength=100,
     *                 description="Nom de la categoria"
     *             ),
     *             @OA\Property(
     *                 property="descripcio",
     *                 type="string",
     *                 maxLength=255,
     *                 description="Descripció de la categoria"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Categoria creada correctament"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validació"
     *     )
     * )
     */
    public function store(Request $request)
    {
        $request->validate([
            'nom' => 'required|string|max:100',
            'descripcio' => 'nullable|string|max:255'
        ]);

        $categoria = Categoria::create([
            'nom' => $request->nom,
            'descripcio' => $request->descripcio
        ]);

        return response()->json([
            'exit' => true,
            'dades' => $categoria,
            'missatge' => 'Categoria creada correctament'
        ], 201);
    }

    /**
     * @OA\Get(
     *     path="/api/categories/{id}",
     *     summary="Obté una categoria pel seu identificador",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Identificador de la categoria",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Categoria trobada"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Categoria no trobada"
     *     )
     * )
     */
    public function show(string $id)
    {
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json([
                'exit' => false,
                'dades' => null,
                'missatge' => 'Categoria no trobada'
            ], 404);
        }

        return response()->json([
            'exit' => true,
            'dades' => $categoria,
            'missatge' => 'Categoria trobada'
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/categories/{id}",
     *     summary="Actualitza una categoria existent",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Identificador de la categoria",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="nom",
     *                 type="string",
     *                 maxLength=100,
     *                 description="Nom de la categoria"
     *             ),
     *             @OA\Property(
     *                 property="descripcio",
     *                 type="string",
     *                 maxLength=255,
     *                 description="Descripció de la categoria"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Categoria actualitzada correctament"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Categoria no trobada"
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Error de validació"
     *     )
     * )
     */
    public function update(Request $request, string $id)
    {
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json([
                'exit' => false,
                'dades' => null,
                'missatge' => 'Categoria no trobada'
            ], 404);
        }

        $request->validate([
            'nom' => 'sometimes|required|string|max:100',
            'descripcio' => 'nullable|string|max:255'
        ]);

        $categoria->update($request->only(['nom', 'descripcio']));

        return response()->json([
            'exit' => true,
            'dades' => $categoria,
            'missatge' => 'Categoria actualitzada correctament'
        ]);
    }

    /**
     * @OA\Delete(
     *     path="/api/categories/{id}",
     *     summary="Elimina una categoria",
     *     tags={"Categories"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Identificador de la categoria",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Categoria eliminada correctament"
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Categoria no trobada"
     *     )
     * )
     */
    public function destroy(string $id)
    {
        $categoria = Categoria::find($id);

        if (!$categoria) {
            return response()->json([
                'exit' => false,
                'dades' => null,
                'missatge' => 'Categoria no trobada'
            ], 404);
        }

        $categoria->delete();

        return response()->json([
            'exit' => true,
            'dades' => null,
            'missatge' => 'Categoria eliminada correctament'
        ]);
    }
}
