<?php

namespace App\Http\Controllers\Api\V1\AI;

use App\Http\Controllers\Controller;
use App\Http\Requests\GenerateTextRequest;
use App\Services\AI\TextGeneratorService;

class GenerateTextController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(GenerateTextRequest $request, TextGeneratorService $service)
    {
        $validated = $request->validated();

        $result = $service->generate($validated["prompt"]);

        return response()->json($result, 200);
    }
}
