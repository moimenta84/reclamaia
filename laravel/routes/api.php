<?php

use App\Http\Controllers\PipelineController;
use App\Http\Controllers\WebhookController;
use Illuminate\Support\Facades\Route;

// Stripe webhook — no CSRF, no auth
Route::post('/stripe/webhook', [WebhookController::class, 'handleStripe'])
    ->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);

// n8n pipeline log endpoint
Route::post('/pipeline/log', [PipelineController::class, 'log']);
