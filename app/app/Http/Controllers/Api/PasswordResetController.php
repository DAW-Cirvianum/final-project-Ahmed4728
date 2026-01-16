<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class PasswordResetController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/password/email",
     *     summary="Enviar enllaç de recuperació de contrasenya",
     *     tags={"Password Reset"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="email", type="string", format="email", description="Email de l'usuari")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Email de recuperació enviat",
     *         @OA\JsonContent(
     *             @OA\Property(property="exit", type="boolean"),
     *             @OA\Property(property="missatge", type="string")
     *         )
     *     ),
     *     @OA\Response(response=400, description="No s'ha pogut enviar el correu")
     * )
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        return $status === Password::RESET_LINK_SENT
            ? response()->json([
                'exit' => true,
                'missatge' => 'Email de recuperació enviat'
            ])
            : response()->json([
                'exit' => false,
                'missatge' => 'No s’ha pogut enviar el correu'
            ], 400);
    }
    /**
     * @OA\Post(
     *     path="/api/password/reset",
     *     summary="Restablir contrasenya",
     *     tags={"Password Reset"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             type="object",
     *             @OA\Property(property="email", type="string", format="email", description="Email de l'usuari"),
     *             @OA\Property(property="token", type="string", description="Token de recuperació"),
     *             @OA\Property(property="password", type="string", minLength=6, description="Nova contrasenya"),
     *             @OA\Property(property="password_confirmation", type="string", minLength=6, description="Confirmació de la contrasenya")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Contrasenya actualitzada correctament",
     *         @OA\JsonContent(
     *             @OA\Property(property="exit", type="boolean"),
     *             @OA\Property(property="missatge", type="string")
     *         )
     *     ),
     *     @OA\Response(response=400, description="Token invàlid o caducat")
     * )
     */
    public function reset(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'token' => 'required|string',
            'password' => 'required|string|min:6|confirmed'
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = bcrypt($password);
                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? response()->json([
                'exit' => true,
                'missatge' => 'Contrasenya actualitzada correctament'
            ])
            : response()->json([
                'exit' => false,
                'missatge' => 'Token invàlid o caducat'
            ], 400);
    }
}
