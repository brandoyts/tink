<?php

use App\Http\Controllers\Api\V1\AI\GenerateTextController;
use Illuminate\Support\Facades\Route;

Route::prefix("v1")->group(function() {
    Route::post("/generate-text", GenerateTextController::class);
});
