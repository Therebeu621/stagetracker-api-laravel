<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *   path="/api/login",
     *   tags={"Auth"},
     *   summary="Connexion utilisateur",
     *   description="Authentifie l'utilisateur et retourne un token Sanctum",
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"email","password"},
     *       @OA\Property(property="email", type="string", format="email", example="admin@test.com"),
     *       @OA\Property(property="password", type="string", format="password", example="secret123")
     *     )
     *   ),
     *   @OA\Response(
     *     response=200,
     *     description="Authentification réussie",
     *     @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Authenticated"),
     *       @OA\Property(property="token", type="string", example="1|abc123...")
     *     )
     *   ),
     *   @OA\Response(response=422, description="Credentials invalides")
     * )
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'message' => 'Authenticated',
            'token' => $token,
        ]);
    }

    /**
     * @OA\Post(
     *   path="/api/logout",
     *   tags={"Auth"},
     *   summary="Déconnexion utilisateur",
     *   description="Révoque le token actuel",
     *   security={{"bearerAuth":{}}},
     *   @OA\Response(response=204, description="Token révoqué"),
     *   @OA\Response(response=401, description="Non authentifié")
     * )
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(null, 204);
    }
}
