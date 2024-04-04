<?php

use App\Http\Controllers\BotController;
use App\Http\Controllers\InvoiceGenerationController;
use Illuminate\Support\Facades\Route;
use Telegram\Bot\Laravel\Facades\Telegram;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [InvoiceGenerationController::class, 'index'])->name('invoice.index');

Route::post('/generate-invoice', [InvoiceGenerationController::class, 'generate'])->name('invoice.generate');


$webhookUrl = config('telegram.bots.invoice_bot.webhook_url');
$response = Telegram::setWebhook(['url' => $webhookUrl]);

// Обработка входящих запросов от телеграм
Route::post('/telegram/webhook', [BotController::class, 'webhook'])->name('telegram.webhook_update');
