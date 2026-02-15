<?php

namespace App\Http\Controllers;

/**
 * @OA\Info(
 *   title="StageTracker API",
 *   version="1.0.0",
 *   description="API REST pour gérer les candidatures de stage"
 * )
 *
 * @OA\SecurityScheme(
 *   securityScheme="bearerAuth",
 *   type="http",
 *   scheme="bearer",
 *   bearerFormat="Sanctum",
 *   description="Token obtenu via POST /api/login"
 * )
 *
 * @OA\Server(url="http://localhost:8000", description="Local server")
 */
abstract class Controller
{
    //
}
