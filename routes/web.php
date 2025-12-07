<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CutPlanController;
use App\Http\Controllers\ModeleController;
use App\Http\Controllers\MaterialController;

// Routes d'authentification (publiques)
Route::middleware(['guest'])->group(function () {
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
    // Route d'inscription accessible publiquement uniquement si aucun utilisateur n'existe
    Route::get('register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
});

// Route d'inscription pour les admins (création d'utilisateurs)
Route::middleware(['auth'])->group(function () {
    // Cette route sera gérée par UserController pour la création d'utilisateurs par les admins
});

Route::post('logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Route d'accueil publique - redirige vers le catalogue
Route::get('/', [ModeleController::class, 'index'])->name('home');

// Routes publiques pour le catalogue de modèles
Route::get('catalogue', [ModeleController::class, 'index'])->name('modeles.index');
Route::get('catalogue/{modele}', [ModeleController::class, 'show'])->name('modeles.show');
Route::get('catalogue/{modele}/ajouter-devis', [ModeleController::class, 'addToQuote'])->name('modeles.add-to-quote');

// Routes protégées par authentification
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/index', [DashboardController::class, 'index'])->name('dashboard.index');

    // Routes pour les clients
    Route::resource('clients', ClientController::class);

    // Routes pour les produits
    Route::resource('products', ProductController::class);

    // Routes pour les matériaux
    Route::resource('materials', MaterialController::class);

    // Routes pour les devis
    // Route spécifique AVANT la route resource pour éviter les conflits
    Route::get('quotes/calculate-materials', [QuoteController::class, 'calculateMaterials'])->name('quotes.calculate-materials');
    Route::get('quotes/export-materials', [QuoteController::class, 'exportMaterials'])->name('quotes.export-materials');
    Route::get('quotes/{quote}/print-materials', [QuoteController::class, 'printMaterials'])->name('quotes.print-materials');
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
    Route::post('quotes/{quote}/cancel', [QuoteController::class, 'cancelQuote'])->name('quotes.cancel');
    Route::get('quotes/{quote}/print', [QuoteController::class, 'print'])->name('quotes.print');
    
    // Routes pour l'optimisation des coupes
    Route::post('quotes/{quote}/cut-optimize', [CutPlanController::class, 'generate'])->name('quotes.cut-optimize');
    Route::get('cut-plans/{cutPlan}', [CutPlanController::class, 'show'])->name('cut-plans.show');
    Route::get('cut-plans/{cutPlan}/pdf', [CutPlanController::class, 'downloadPdf'])->name('cut-plans.pdf');

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

    // Routes pour la gestion des utilisateurs (admin seulement)
    Route::resource('users', UserController::class);
    
    // Routes admin pour la gestion des modèles
    Route::middleware(['auth'])->group(function () {
        Route::get('modeles/create', [ModeleController::class, 'create'])->name('modeles.create');
        Route::post('modeles', [ModeleController::class, 'store'])->name('modeles.store');
        Route::get('modeles/{modele}/edit', [ModeleController::class, 'edit'])->name('modeles.edit');
        Route::put('modeles/{modele}', [ModeleController::class, 'update'])->name('modeles.update');
        Route::delete('modeles/{modele}', [ModeleController::class, 'destroy'])->name('modeles.destroy');
        Route::delete('modeles/{modele}/image', [ModeleController::class, 'deleteImage'])->name('modeles.delete-image');
        Route::post('modeles/{modele}/toggle-status', [ModeleController::class, 'toggleStatus'])->name('modeles.toggle-status');
    });
});

