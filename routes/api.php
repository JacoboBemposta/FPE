<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuscripcionController;

Route::post('stripe/webhook', [SuscripcionController::class, 'handleWebhook']);