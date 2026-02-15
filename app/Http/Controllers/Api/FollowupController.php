<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreFollowupRequest;
use App\Http\Resources\FollowupResource;
use App\Models\Application;
use App\Models\Followup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FollowupController extends Controller
{
    /**
     * GET /api/applications/{application}/followups
     */
    public function index(Application $application): AnonymousResourceCollection
    {
        return FollowupResource::collection(
            $application->followups()->latest()->get()
        );
    }

    /**
     * POST /api/applications/{application}/followups
     */
    public function store(StoreFollowupRequest $request, Application $application): JsonResponse
    {
        $followup = $application->followups()->create($request->validated());

        return (new FollowupResource($followup))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * DELETE /api/followups/{followup}
     */
    public function destroy(Followup $followup): JsonResponse
    {
        $followup->delete();

        return response()->json(null, 204);
    }
}
