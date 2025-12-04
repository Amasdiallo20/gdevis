<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;

// Routes d'authentification (publiques)
Route::middleware(['guest'])->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
});

Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Routes protégées par authentification
Route::middleware(['auth'])->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');

    // Routes pour les clients
    Route::resource('clients', ClientController::class);

    // Routes pour les produits
    Route::resource('products', ProductController::class);

    // Routes pour les devis
    Route::resource('quotes', QuoteController::class);
    Route::post('quotes/{quote}/lines', [QuoteController::class, 'addLine'])->name('quotes.lines.store');
    Route::get('quotes/{quote}/lines/{line}/edit', [QuoteController::class, 'editLine'])->name('quotes.lines.edit');
    Route::put('quotes/{quote}/lines/{line}', [QuoteController::class, 'updateLine'])->name('quotes.lines.update');
    Route::post('quotes/{quote}/lines/{line}/duplicate', [QuoteController::class, 'duplicateLine'])->name('quotes.lines.duplicate');
    Route::delete('quotes/{quote}/lines/{line}', [QuoteController::class, 'removeLine'])->name('quotes.lines.destroy');
    Route::post('quotes/{quote}/update-all-prices', [QuoteController::class, 'updateAllPrices'])->name('quotes.update-all-prices');
    Route::post('quotes/{quote}/update-status', [QuoteController::class, 'updateStatus'])->name('quotes.update-status');
    Route::get('quotes/{quote}/validate', [QuoteController::class, 'showValidation'])->name('quotes.show-validation');
    Route::post('quotes/{quote}/validate', [QuoteController::class, 'validateQuote'])->name('quotes.validate');
    Route::get('quotes/{quote}/print', [QuoteController::class, 'print'])->name('quotes.print');

    // Routes pour les paramètres
    Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    Route::put('settings', [SettingController::class, 'update'])->name('settings.update');

    // Routes pour le profil utilisateur
    Route::get('profile', [AuthController::class, 'showProfile'])->name('profile');
    Route::put('profile/password', [AuthController::class, 'updatePassword'])->name('profile.password.update');

    // Routes pour les paiements
    Route::get('payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('payments/pending-quotes', [PaymentController::class, 'pendingQuotes'])->name('payments.pending-quotes');
    Route::get('quotes/{quote}/payments/create', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('quotes/{quote}/payments', [PaymentController::class, 'store'])->name('payments.store');
    Route::get('payments/{payment}', [PaymentController::class, 'show'])->name('payments.show');
    Route::get('quotes/{quote}/payments/{payment}/edit', [PaymentController::class, 'edit'])->name('payments.edit');
    Route::put('quotes/{quote}/payments/{payment}', [PaymentController::class, 'update'])->name('payments.update');
    Route::delete('quotes/{quote}/payments/{payment}', [PaymentController::class, 'destroy'])->name('payments.destroy');
    Route::get('payments/{payment}/print', [PaymentController::class, 'print'])->name('payments.print');
});

