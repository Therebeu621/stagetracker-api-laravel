<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreApplicationRequest;
use App\Http\Requests\UpdateApplicationRequest;
use App\Http\Resources\ApplicationResource;
use App\Models\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ApplicationController extends Controller
{
    /**
     * @OA\Get(
     *   path="/api/applications",
     *   tags={"Applications"},
     *   summary="Liste des candidatures",
     *   description="Retourne une liste paginée avec filtres et tri",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(
     *     name="status",
     *     in="query",
     *     description="Filtrer par statut",
     *     required=false,
     *     @OA\Schema(type="string", enum={"applied","interview","offer","rejected"})
     *   ),
     *   @OA\Parameter(
     *     name="sort",
     *     in="query",
     *     description="Champ de tri",
     *     required=false,
     *     @OA\Schema(type="string", enum={"applied_at"})
     *   ),
     *   @OA\Parameter(
     *     name="direction",
     *     in="query",
     *     description="Direction du tri",
     *     required=false,
     *     @OA\Schema(type="string", enum={"asc","desc"}, default="desc")
     *   ),
     *   @OA\Parameter(
     *     name="per_page",
     *     in="query",
     *     description="Nombre d'éléments par page (max 100)",
     *     required=false,
     *     @OA\Schema(type="integer", default=15)
     *   ),
     *   @OA\Response(response=200, description="Liste paginée"),
     *   @OA\Response(response=401, description="Non authentifié")
     * )
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Application::query()
            ->where('user_id', $request->user()->id)
            ->withCount('followups');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // Sort by applied_at (default: desc)
        if ($request->filled('sort') && $request->input('sort') === 'applied_at') {
            $direction = $request->input('direction', 'desc');
            $query->orderBy('applied_at', $direction === 'asc' ? 'asc' : 'desc');
        } else {
            $query->latest();
        }

        $perPage = min((int) $request->input('per_page', 15), 100);

        return ApplicationResource::collection($query->paginate($perPage));
    }

    /**
     * @OA\Post(
     *   path="/api/applications",
     *   tags={"Applications"},
     *   summary="Créer une candidature",
     *   security={{"bearerAuth":{}}},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"company","position"},
     *       @OA\Property(property="company", type="string", example="Google"),
     *       @OA\Property(property="position", type="string", example="Backend Developer"),
     *       @OA\Property(property="location", type="string", example="Paris"),
     *       @OA\Property(property="status", type="string", enum={"applied","interview","offer","rejected"}, default="applied"),
     *       @OA\Property(property="applied_at", type="string", format="date", example="2026-02-01"),
     *       @OA\Property(property="next_followup_at", type="string", format="date", example="2026-02-15"),
     *       @OA\Property(property="notes", type="string", example="Applied via website")
     *     )
     *   ),
     *   @OA\Response(response=201, description="Candidature créée"),
     *   @OA\Response(response=422, description="Validation échouée")
     * )
     */
    public function store(StoreApplicationRequest $request): JsonResponse
    {
        $application = Application::create([
            ...$request->validated(),
            'user_id' => $request->user()->id,
        ]);

        return (new ApplicationResource($application))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * @OA\Get(
     *   path="/api/applications/{id}",
     *   tags={"Applications"},
     *   summary="Détails d'une candidature",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Response(response=200, description="Détails avec followups"),
     *   @OA\Response(response=404, description="Non trouvée")
     * )
     */
    public function show(Request $request, Application $application): ApplicationResource
    {
        $application = $this->findOwnedApplication($request, $application);
        $application->loadCount('followups')->load('followups');

        return new ApplicationResource($application);
    }

    /**
     * @OA\Patch(
     *   path="/api/applications/{id}",
     *   tags={"Applications"},
     *   summary="Modifier une candidature",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\RequestBody(
     *     @OA\JsonContent(
     *       @OA\Property(property="status", type="string", enum={"applied","interview","offer","rejected"}),
     *       @OA\Property(property="next_followup_at", type="string", format="date"),
     *       @OA\Property(property="notes", type="string")
     *     )
     *   ),
     *   @OA\Response(response=200, description="Candidature mise à jour"),
     *   @OA\Response(response=404, description="Non trouvée")
     * )
     */
    public function update(UpdateApplicationRequest $request, Application $application): ApplicationResource
    {
        $application = $this->findOwnedApplication($request, $application);
        $application->update($request->validated());

        return new ApplicationResource($application);
    }

    /**
     * @OA\Delete(
     *   path="/api/applications/{id}",
     *   tags={"Applications"},
     *   summary="Supprimer une candidature",
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(
     *     name="id",
     *     in="path",
     *     required=true,
     *     @OA\Schema(type="integer")
     *   ),
     *   @OA\Response(response=204, description="Supprimée"),
     *   @OA\Response(response=404, description="Non trouvée")
     * )
     */
    public function destroy(Request $request, Application $application): JsonResponse
    {
        $application = $this->findOwnedApplication($request, $application);
        $application->delete();

        return response()->json(null, 204);
    }

    /**
     * @OA\Get(
     *   path="/api/applications/export.csv",
     *   tags={"Applications"},
     *   summary="Export CSV des candidatures",
     *   security={{"bearerAuth":{}}},
     *   @OA\Response(
     *     response=200,
     *     description="Fichier CSV téléchargé",
     *     @OA\MediaType(mediaType="text/csv")
     *   )
     * )
     */
    public function exportCsv(Request $request): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="applications.csv"',
        ];

        $userId = $request->user()->id;

        return response()->stream(function () use ($userId) {
            $handle = fopen('php://output', 'w');

            // Header row
            fputcsv($handle, [
                'id',
                'company',
                'position',
                'location',
                'status',
                'applied_at',
                'next_followup_at',
                'notes',
                'created_at',
                'updated_at',
            ]);

            // Data rows (chunked for memory efficiency)
            Application::where('user_id', $userId)
                ->orderBy('id')
                ->chunk(200, function ($applications) use ($handle) {
                foreach ($applications as $app) {
                    fputcsv($handle, [
                        $app->id,
                        $app->company,
                        $app->position,
                        $app->location,
                        $app->status,
                        $app->applied_at?->toDateString(),
                        $app->next_followup_at?->toDateString(),
                        $app->notes,
                        $app->created_at?->toIso8601String(),
                        $app->updated_at?->toIso8601String(),
                    ]);
                }
            });

            fclose($handle);
        }, 200, $headers);
    }

    private function findOwnedApplication(Request $request, Application $application): Application
    {
        if ((int) $application->user_id !== (int) $request->user()->id) {
            abort(404);
        }

        return $application;
    }
}
