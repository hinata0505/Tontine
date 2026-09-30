<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\MembreController;
use App\Http\Controllers\Api\CotisationController;
use App\Http\Controllers\Api\DistributionController;

Route::apiResource('membres', MembreController::class);
Route::apiResource('cotisations', CotisationController::class);
Route::apiResource('distributions', DistributionController::class);