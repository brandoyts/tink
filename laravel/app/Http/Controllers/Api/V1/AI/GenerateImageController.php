<?php

namespace App\Http\Controllers\Api\V1\AI;

use App\Contracts\ImageStorageContract;
use App\Contracts\ImageValidatorContract;
use App\Http\Controllers\Controller;
use App\Http\Requests\GenerateImageRequest;
use App\Services\AI\ImageGeneratorService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use RuntimeException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class GenerateImageController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(
        GenerateImageRequest $request, 
        ImageGeneratorService $service,
        ImageValidatorContract $imageValidator,
        ImageStorageContract $imageStorage
    ): JsonResponse {

        $validated = $request->validated();

        $generationResult = $service->generate($validated["prompt"]);


        [$binaryImage, $mime] = $imageValidator->validate(
            $generationResult["data"],
            $generationResult["mimeType"]
        );

        $imageUrl = $imageStorage->store($binaryImage, $mime);

        return response()->json(['image_url' => $imageUrl], Response::HTTP_OK);
    }

  
}
