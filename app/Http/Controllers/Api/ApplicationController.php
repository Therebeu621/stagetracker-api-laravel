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
     * GET /api/applications
     *
     * Paginated list with optional filters and sorting.
     * ?status=applied&sort=applied_at&direction=desc&per_page=15
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $query = Application::query()->withCount('followups');

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
     * POST /api/applications
     */
    public function store(StoreApplicationRequest $request): JsonResponse
    {
        $application = Application::create($request->validated());

        return (new ApplicationResource($application))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * GET /api/applications/{application}
     */
    public function show(Application $application): ApplicationResource
    {
        $application->loadCount('followups')->load('followups');

        return new ApplicationResource($application);
    }

    /**
     * PATCH /api/applications/{application}
     */
    public function update(UpdateApplicationRequest $request, Application $application): ApplicationResource
    {
        $application->update($request->validated());

        return new ApplicationResource($application);
    }

    /**
     * DELETE /api/applications/{application}
     */
    public function destroy(Application $application): JsonResponse
    {
        $application->delete();

        return response()->json(null, 204);
    }

    /**
     * GET /api/applications/export.csv
     *
     * Stream all applications as a CSV download.
     */
    public function exportCsv(): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="applications.csv"',
        ];

        return response()->stream(function () {
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
            Application::orderBy('id')->chunk(200, function ($applications) use ($handle) {
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
}
