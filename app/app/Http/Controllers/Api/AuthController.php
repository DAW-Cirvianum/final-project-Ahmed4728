<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Inici de sessió d’usuari",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="login",
     *                 type="string",
     *                 description="Correu electrònic o nom d’usuari"
     *             ),
     *             @OA\Property(
     *                 property="password",
     *                 type="string",
     *                 description="Contrasenya de l’usuari"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Inici de sessió correcte",
     *         @OA\JsonContent(
     *             @OA\Property(property="exit", type="boolean"),
     *             @OA\Property(property="token", type="string"),
     *             @OA\Property(property="tipus", type="string")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credencials incorrectes"
     *     )
     * )
     */
    public function login(Request $request)
    {
        $request->validate([
            'login' => 'required|string',
            'password' => 'required|string',
        ]);

        $login = $request->input('login');

        $field = filter_var($login, FILTER_VALIDATE_EMAIL)
            ? 'email'
            : 'name';

        $credentials = [
            $field => $login,
            'password' => $request->password,
        ];

        if (! $token = Auth::guard('api')->attempt($credentials)) {
            return response()->json([
                'exit' => false,
                'missatge' => 'Credencials incorrectes'
            ], 401);
        }

        return response()->json([
            'exit' => true,
            'token' => $token,
            'tipus' => 'bearer'
        ]);
    }


    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Registre d’un nou usuari",
     *     tags={"Auth"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(
     *                 property="nom",
     *                 type="string",
     *                 maxLength=255,
     *                 description="Nom de l’usuari"
     *             ),
     *             @OA\Property(
     *                 property="email",
     *                 type="string",
     *                 format="email",
     *                 maxLength=255,
     *                 description="Correu electrònic únic"
     *             ),
     *             @OA\Property(
     *                 property="password",
     *                 type="string",
     *                 minLength=6,
     *                 description="Contrasenya de l’usuari"
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuari registrat correctament",
     *         @OA\JsonContent(
     *             @OA\Property(property="exit", type="boolean"),
     *             @OA\Property(property="missatge", type="string"),
     *             @OA\Property(property="user", type="object")
     *         )
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
            'nom' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create([
            'name' => $request->nom,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'exit' => true,
            'missatge' => 'Usuari registrat correctament',
            'user' => $user
        ], 201);
    }
}
