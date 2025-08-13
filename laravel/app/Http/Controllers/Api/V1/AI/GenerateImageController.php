<?php

namespace App\Http\Controllers\Api\V1\AI;

use App\Http\Controllers\Controller;
use App\Http\Requests\GenerateImageRequest;
use App\Services\AI\ImageGeneratorService;

class GenerateImageController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(GenerateImageRequest $request, ImageGeneratorService $service)
    {
        $validated = $request->validated();

        $result = $service->generate($validated["prompt"]);

        return response()->json($result, 200);
    }
}
