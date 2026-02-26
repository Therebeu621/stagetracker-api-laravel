<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFollowupRequest;
use App\Http\Resources\FollowupResource;
use App\Models\Application;
use App\Models\Followup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FollowupController extends Controller
{
    /**
     * @OA\Get(
     *   path="/api/applications/{application_id}/followups",
     *   tags={"Followups"},
     *   summary="Liste des suivis d'une candidature",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(
     *     name="application_id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Response(response=200, description="Liste des suivis"),
     *   @OA\Response(response=404, description="Candidature non trouvée")
     * )
     */
    public function index(Request $request, Application $application): AnonymousResourceCollection
    {
        $application = $this->findOwnedApplication($request, $application);

        return FollowupResource::collection(
            $application->followups()->latest()->get()
        );
    }

    /**
     * @OA\Post(
     *   path="/api/applications/{application_id}/followups",
     *   tags={"Followups"},
     *   summary="Créer un suivi",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(
     *     name="application_id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"type"},
     *       @OA\Property(property="type", type="string", enum={"email","call","linkedin"}),
     *       @OA\Property(property="done_at", type="string", format="date", example="2026-02-15"),
     *       @OA\Property(property="notes", type="string", example="Relance envoyée")
     *     )
     *   ),
     *   @OA\Response(response=201, description="Suivi créé"),
     *   @OA\Response(response=422, description="Validation échouée")
     * )
     */
    public function store(StoreFollowupRequest $request, Application $application): JsonResponse
    {
        $application = $this->findOwnedApplication($request, $application);

        $followup = $application->followups()->create($request->validated());

        return (new FollowupResource($followup))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * @OA\Delete(
     *   path="/api/followups/{id}",
     *   tags={"Followups"},
     *   summary="Supprimer un suivi",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Response(response=204, description="Suivi supprimé"),
     *   @OA\Response(response=404, description="Suivi non trouvé")
     * )
     */
    public function destroy(Request $request, Followup $followup): JsonResponse
    {
        if (
            !$followup->application()
                ->where('user_id', $request->user()->id)
                ->exists()
        ) {
            abort(404);
        }

        $followup->delete();

        return response()->json(null, 204);
    }

    private function findOwnedApplication(Request $request, Application $application): Application
    {
        if ((int) $application->user_id !== (int) $request->user()->id) {
            abort(404);
        }

        return $application;
    }
}
