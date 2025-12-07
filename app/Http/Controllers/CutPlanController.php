<?php

namespace App\Http\Controllers;

use App\Models\Quote;
use App\Models\CutPlan;
use App\Services\CutOptimizationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CutPlanController extends Controller
{
    protected $optimizationService;

    public function __construct(CutOptimizationService $optimizationService)
    {
        $this->optimizationService = $optimizationService;
    }

    /**
     * Génère un plan de coupe optimisé pour un devis
     * 
     * @param Quote $quote
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse
     */
    public function generate(Quote $quote)
    {
        try {
            // Charger les lignes du devis
            $quote->load('lines');

            // Extraire toutes les longueurs nécessaires
            $lengths = $this->optimizationService->extractLengthsFromQuote($quote->lines);

            if (empty($lengths)) {
                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Aucune dimension trouvée dans les lignes du devis. Veuillez ajouter des lignes avec largeur et/ou hauteur.',
                    ], 400);
                }

                return back()->withErrors([
                    'error' => 'Aucune dimension trouvée dans les lignes du devis. Veuillez ajouter des lignes avec largeur et/ou hauteur.',
                ]);
            }

            // Optimiser les coupes
            $optimizedBars = $this->optimizationService->optimize($lengths);
            $statistics = $this->optimizationService->calculateStatistics($optimizedBars);

            // Enregistrer dans la base de données
            DB::beginTransaction();

            try {
                // Créer le plan de coupe
                $cutPlan = CutPlan::create([
                    'quote_id' => $quote->id,
                    'total_bars_used' => $statistics['total_bars'],
                    'total_waste' => $statistics['total_waste'],
                ]);

                // Créer les détails
                foreach ($optimizedBars as $bar) {
                    $cutPlan->details()->create([
                        'bar_number' => $bar['bar_number'],
                        'sections' => $bar['sections'],
                        'used_length' => $bar['used_length'],
                        'waste' => $bar['waste'],
                    ]);
                }

                DB::commit();

                if (request()->expectsJson()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Plan de coupe généré avec succès.',
                        'cut_plan_id' => $cutPlan->id,
                        'redirect_url' => route('cut-plans.show', $cutPlan),
                    ]);
                }

                return redirect()->route('cut-plans.show', $cutPlan)
                    ->with('success', 'Plan de coupe généré avec succès.');

            } catch (\Exception $e) {
                DB::rollBack();
                throw $e;
            }

        } catch (\Exception $e) {
            Log::error('Erreur lors de la génération du plan de coupe: ' . $e->getMessage());

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Une erreur est survenue lors de la génération du plan de coupe.',
                ], 500);
            }

            return back()->withErrors([
                'error' => 'Une erreur est survenue lors de la génération du plan de coupe.',
            ]);
        }
    }

    /**
     * Affiche un plan de coupe
     * 
     * @param CutPlan $cutPlan
     * @return \Illuminate\View\View
     */
    public function show(CutPlan $cutPlan)
    {
        $cutPlan->load(['quote.client', 'details']);
        $settings = \App\Models\Setting::getSettings();

        return view('cut_plans.show', compact('cutPlan', 'settings'));
    }

    /**
     * Télécharge le PDF du plan de coupe
     * 
     * @param CutPlan $cutPlan
     * @return \Illuminate\Http\Response
     */
    public function downloadPdf(CutPlan $cutPlan)
    {
        $cutPlan->load(['quote.client', 'details']);
        $settings = \App\Models\Setting::getSettings();

        try {
            if (class_exists('\Barryvdh\DomPDF\Facade\Pdf')) {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('cut_plans.pdf', compact('cutPlan', 'settings'));
                $pdf->setPaper('a4', 'portrait');
                $pdf->setOption('enable-local-file-access', true);
                $pdf->setOption('isHtml5ParserEnabled', true);
                $pdf->setOption('isRemoteEnabled', true);
                $pdf->setOption('defaultFont', 'DejaVu Sans');
                $pdf->setOption('enable_php', true);

                $filename = 'plan-coupe-' . $cutPlan->quote->quote_number . '.pdf';

                if (request()->has('download')) {
                    return $pdf->download($filename);
                }

                return $pdf->stream($filename);
            }
        } catch (\Exception $e) {
            Log::error('Erreur DOMPDF: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Erreur lors de la génération du PDF.']);
        }

        return view('cut_plans.pdf', compact('cutPlan', 'settings'));
    }
}
