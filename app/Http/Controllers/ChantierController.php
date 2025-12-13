<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Chantier;
use App\Models\Tache;
use App\Models\MateriauChantier;
use App\Models\PhotoChantier;
use App\Models\PhotoTache;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ChantierController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        if (!$user->hasPermission('chantiers.view')) {
            abort(403, 'Vous n\'avez pas la permission de voir les chantiers.');
        }

        $query = Chantier::with(['quote.client', 'creator']);

        // Filtre par statut
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtre par client
        if ($request->filled('client_id')) {
            $query->whereHas('quote', function($q) use ($request) {
                $q->where('client_id', $request->client_id);
            });
        }

        // Recherche par numéro de chantier ou devis
        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('chantier_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('quote', function($q2) use ($request) {
                      $q2->where('quote_number', 'like', '%' . $request->search . '%');
                  });
            });
        }

        $chantiers = $query->latest()->paginate(15)->withQueryString();
        $clients = \App\Models\Client::orderBy('name')->get();

        return view('chantiers.index', compact('chantiers', 'clients'));
    }

    public function show(Chantier $chantier)
    {
        $user = Auth::user();
        if (!$user->hasPermission('chantiers.view')) {
            abort(403, 'Vous n\'avez pas la permission de voir les chantiers.');
        }

        $chantier->load([
            'quote.client',
            'taches.techniciens',
            'materiaux',
            'photos.uploader',
            'creator'
        ]);

        $techniciens = User::where('role', '!=', 'admin')->orderBy('name')->get();
        $settings = \App\Models\Setting::getSettings();

        return view('chantiers.show', compact('chantier', 'techniciens', 'settings'));
    }

    public function updateStatus(Request $request, Chantier $chantier)
    {
        $user = Auth::user();
        if (!$user->hasPermission('chantiers.edit')) {
            abort(403, 'Vous n\'avez pas la permission de modifier les chantiers.');
        }

        $validated = $request->validate([
            'status' => 'required|in:planifié,en_cours,suspendu,terminé,facturé',
        ]);

        $chantier->update(['status' => $validated['status']]);

        $statusLabels = [
            'planifié' => 'Planifié',
            'en_cours' => 'En cours',
            'suspendu' => 'Suspendu',
            'terminé' => 'Terminé',
            'facturé' => 'Facturé',
        ];

        return redirect()->route('chantiers.show', $chantier)
            ->with('success', 'Statut du chantier changé en "' . $statusLabels[$validated['status']] . '" avec succès.');
    }

    public function updateProgress(Request $request, Chantier $chantier)
    {
        $user = Auth::user();
        if (!$user->hasPermission('chantiers.edit')) {
            abort(403, 'Vous n\'avez pas la permission de modifier les chantiers.');
        }

        $validated = $request->validate([
            'progress' => 'required|integer|min:0|max:100',
        ]);

        $chantier->update(['progress' => $validated['progress']]);

        return redirect()->route('chantiers.show', $chantier)
            ->with('success', 'Avancement mis à jour avec succès.');
    }

    public function updateDates(Request $request, Chantier $chantier)
    {
        $user = Auth::user();
        if (!$user->hasPermission('chantiers.edit')) {
            abort(403, 'Vous n\'avez pas la permission de modifier les chantiers.');
        }

        $validated = $request->validate([
            'date_debut' => 'nullable|date',
            'date_fin_prevue' => 'nullable|date|after_or_equal:date_debut',
            'date_fin_reelle' => 'nullable|date',
        ]);

        $chantier->update($validated);

        return redirect()->route('chantiers.show', $chantier)
            ->with('success', 'Dates mises à jour avec succès.');
    }

    public function updateNotes(Request $request, Chantier $chantier)
    {
        $user = Auth::user();
        if (!$user->hasPermission('chantiers.edit')) {
            abort(403, 'Vous n\'avez pas la permission de modifier les chantiers.');
        }

        $validated = $request->validate([
            'notes' => 'nullable|string',
        ]);

        $chantier->update($validated);

        return redirect()->route('chantiers.show', $chantier)
            ->with('success', 'Notes mises à jour avec succès.');
    }

    // Gestion des tâches
    public function storeTache(Request $request, Chantier $chantier)
    {
        $user = Auth::user();
        if (!$user->hasPermission('chantiers.edit')) {
            abort(403, 'Vous n\'avez pas la permission de modifier les chantiers.');
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:coupe,assemblage,decoupe_vitres,pose,finitions,autre',
            'date_debut_prevue' => 'nullable|date',
            'date_fin_prevue' => 'nullable|date|after_or_equal:date_debut_prevue',
            'ordre' => 'nullable|integer',
        ]);

        $maxOrdre = $chantier->taches()->max('ordre') ?? 0;
        $validated['chantier_id'] = $chantier->id;
        $validated['status'] = 'a_faire';
        $validated['progress'] = 0;
        $validated['ordre'] = $validated['ordre'] ?? ($maxOrdre + 1);

        $tache = Tache::create($validated);

        return redirect()->route('chantiers.show', $chantier)
            ->with('success', 'Tâche créée avec succès.');
    }

    public function updateTache(Request $request, Chantier $chantier, Tache $tache)
    {
        $user = Auth::user();
        if (!$user->hasPermission('chantiers.edit')) {
            abort(403, 'Vous n\'avez pas la permission de modifier les chantiers.');
        }

        if ($tache->chantier_id !== $chantier->id) {
            abort(404);
        }

        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:coupe,assemblage,decoupe_vitres,pose,finitions,autre',
            'status' => 'required|in:a_faire,en_cours,termine,bloque',
            'progress' => 'required|integer|min:0|max:100',
            'date_debut_prevue' => 'nullable|date',
            'date_fin_prevue' => 'nullable|date|after_or_equal:date_debut_prevue',
            'date_debut_reelle' => 'nullable|date',
            'date_fin_reelle' => 'nullable|date',
            'ordre' => 'nullable|integer',
        ]);

        $tache->update($validated);

        return redirect()->route('chantiers.show', $chantier)
            ->with('success', 'Tâche mise à jour avec succès.');
    }

    public function deleteTache(Chantier $chantier, Tache $tache)
    {
        $user = Auth::user();
        if (!$user->hasPermission('chantiers.edit')) {
            abort(403, 'Vous n\'avez pas la permission de modifier les chantiers.');
        }

        if ($tache->chantier_id !== $chantier->id) {
            abort(404);
        }

        $tache->delete();

        return redirect()->route('chantiers.show', $chantier)
            ->with('success', 'Tâche supprimée avec succès.');
    }

    public function assignTechniciens(Request $request, Chantier $chantier, Tache $tache)
    {
        $user = Auth::user();
        if (!$user->hasPermission('chantiers.edit')) {
            abort(403, 'Vous n\'avez pas la permission de modifier les chantiers.');
        }

        if ($tache->chantier_id !== $chantier->id) {
            abort(404);
        }

        $validated = $request->validate([
            'techniciens' => 'nullable|array',
            'techniciens.*' => 'exists:users,id',
        ]);

        $tache->techniciens()->sync($validated['techniciens'] ?? []);

        return redirect()->route('chantiers.show', $chantier)
            ->with('success', 'Techniciens assignés avec succès.');
    }

    // Gestion des matériaux
    public function storeMateriau(Request $request, Chantier $chantier)
    {
        $user = Auth::user();
        if (!$user->hasPermission('chantiers.edit')) {
            abort(403, 'Vous n\'avez pas la permission de modifier les chantiers.');
        }

        $validated = $request->validate([
            'nom_materiau' => 'required|string|max:255',
            'unite' => 'required|string|max:50',
            'quantite_prevue' => 'required|numeric|min:0',
            'quantite_utilisee' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $validated['chantier_id'] = $chantier->id;
        $validated['quantite_utilisee'] = $validated['quantite_utilisee'] ?? 0;

        MateriauChantier::create($validated);

        return redirect()->route('chantiers.show', $chantier)
            ->with('success', 'Matériau ajouté avec succès.');
    }

    public function updateMateriau(Request $request, Chantier $chantier, MateriauChantier $materiau)
    {
        $user = Auth::user();
        if (!$user->hasPermission('chantiers.edit')) {
            abort(403, 'Vous n\'avez pas la permission de modifier les chantiers.');
        }

        if ($materiau->chantier_id !== $chantier->id) {
            abort(404);
        }

        $validated = $request->validate([
            'nom_materiau' => 'required|string|max:255',
            'unite' => 'required|string|max:50',
            'quantite_prevue' => 'required|numeric|min:0',
            'quantite_utilisee' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        $validated['quantite_utilisee'] = $validated['quantite_utilisee'] ?? 0;

        $materiau->update($validated);

        return redirect()->route('chantiers.show', $chantier)
            ->with('success', 'Matériau mis à jour avec succès.');
    }

    public function deleteMateriau(Chantier $chantier, MateriauChantier $materiau)
    {
        $user = Auth::user();
        if (!$user->hasPermission('chantiers.edit')) {
            abort(403, 'Vous n\'avez pas la permission de modifier les chantiers.');
        }

        if ($materiau->chantier_id !== $chantier->id) {
            abort(404);
        }

        $materiau->delete();

        return redirect()->route('chantiers.show', $chantier)
            ->with('success', 'Matériau supprimé avec succès.');
    }

    // Gestion des photos
    public function storePhoto(Request $request, Chantier $chantier)
    {
        $user = Auth::user();
        if (!$user->hasPermission('chantiers.edit')) {
            abort(403, 'Vous n\'avez pas la permission de modifier les chantiers.');
        }

        $validated = $request->validate([
            'photo' => 'required|image|max:10240', // 10MB max
            'commentaire' => 'nullable|string',
        ]);

        $path = $request->file('photo')->store('chantiers/photos', 'public');
        
        PhotoChantier::create([
            'chantier_id' => $chantier->id,
            'chemin_fichier' => $path,
            'commentaire' => $validated['commentaire'] ?? null,
            'uploaded_by' => Auth::id(),
        ]);

        return redirect()->route('chantiers.show', $chantier)
            ->with('success', 'Photo ajoutée avec succès.');
    }

    public function updatePhoto(Request $request, Chantier $chantier, PhotoChantier $photo)
    {
        $user = Auth::user();
        if (!$user->hasPermission('chantiers.edit')) {
            abort(403, 'Vous n\'avez pas la permission de modifier les chantiers.');
        }

        if ($photo->chantier_id !== $chantier->id) {
            abort(404);
        }

        $validated = $request->validate([
            'commentaire' => 'nullable|string',
        ]);

        $photo->update($validated);

        return redirect()->route('chantiers.show', $chantier)
            ->with('success', 'Commentaire mis à jour avec succès.');
    }

    public function deletePhoto(Chantier $chantier, PhotoChantier $photo)
    {
        $user = Auth::user();
        if (!$user->hasPermission('chantiers.edit')) {
            abort(403, 'Vous n\'avez pas la permission de modifier les chantiers.');
        }

        if ($photo->chantier_id !== $chantier->id) {
            abort(404);
        }

        // Supprimer le fichier
        if (Storage::disk('public')->exists($photo->chemin_fichier)) {
            Storage::disk('public')->delete($photo->chemin_fichier);
        }

        $photo->delete();

        return redirect()->route('chantiers.show', $chantier)
            ->with('success', 'Photo supprimée avec succès.');
    }

    // Vue pour les techniciens - leurs tâches assignées
    public function mesTaches(Request $request)
    {
        $user = Auth::user();
        
        $query = Tache::whereHas('techniciens', function($q) use ($user) {
            $q->where('users.id', $user->id);
        })->with(['chantier.quote.client', 'photos' => function($q) {
            $q->orderBy('created_at', 'desc');
        }, 'photos.uploader']);

        // Filtre par statut
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filtre par chantier
        if ($request->filled('chantier_id')) {
            $query->where('chantier_id', $request->chantier_id);
        }

        $taches = $query->latest()->paginate(15)->withQueryString();
        $chantiers = Chantier::whereHas('taches.techniciens', function($q) use ($user) {
            $q->where('users.id', $user->id);
        })->with('quote.client')->get();

        return view('chantiers.mes-taches', compact('taches', 'chantiers'));
    }

    // Mise à jour de la progression par le technicien
    public function updateTacheProgress(Request $request, Tache $tache)
    {
        $user = Auth::user();
        
        // Vérifier que l'utilisateur est assigné à cette tâche
        if (!$tache->techniciens->contains($user->id)) {
            abort(403, 'Vous n\'êtes pas assigné à cette tâche.');
        }

        $validated = $request->validate([
            'progress' => 'required|integer|min:0|max:100',
            'status' => 'nullable|in:a_faire,en_cours,termine,bloque',
        ]);

        $oldProgress = $tache->progress;
        $oldStatus = $tache->status;

        $tache->update($validated);

        // Enregistrer les activités
        if ($oldProgress != $validated['progress']) {
            Activity::create([
                'user_id' => $user->id,
                'tache_id' => $tache->id,
                'chantier_id' => $tache->chantier_id,
                'type' => 'progress_updated',
                'description' => "{$user->name} a mis à jour la progression de la tâche \"{$tache->nom}\" de {$oldProgress}% à {$validated['progress']}%",
                'data' => [
                    'old_progress' => $oldProgress,
                    'new_progress' => $validated['progress'],
                    'tache_nom' => $tache->nom,
                ],
            ]);
        }

        if (isset($validated['status']) && $oldStatus != $validated['status']) {
            $statusLabels = [
                'a_faire' => 'À faire',
                'en_cours' => 'En cours',
                'termine' => 'Terminé',
                'bloque' => 'Bloqué',
            ];
            
            Activity::create([
                'user_id' => $user->id,
                'tache_id' => $tache->id,
                'chantier_id' => $tache->chantier_id,
                'type' => 'status_changed',
                'description' => "{$user->name} a changé le statut de la tâche \"{$tache->nom}\" de \"{$statusLabels[$oldStatus]}\" à \"{$statusLabels[$validated['status']]}\"",
                'data' => [
                    'old_status' => $oldStatus,
                    'new_status' => $validated['status'],
                    'old_status_label' => $statusLabels[$oldStatus],
                    'new_status_label' => $statusLabels[$validated['status']],
                    'tache_nom' => $tache->nom,
                ],
            ]);
        }

        return redirect()->route('chantiers.mes-taches')
            ->with('success', 'Progression mise à jour avec succès.');
    }

    // Upload de photo pour une tâche
    public function storePhotoTache(Request $request, Tache $tache)
    {
        $user = Auth::user();
        
        // Vérifier que l'utilisateur est assigné à cette tâche
        if (!$tache->techniciens->contains($user->id)) {
            abort(403, 'Vous n\'êtes pas assigné à cette tâche.');
        }

        $validated = $request->validate([
            'photo' => 'required|image|max:10240', // 10MB max
            'commentaire' => 'nullable|string',
        ]);

        $path = $request->file('photo')->store('taches/photos', 'public');
        
        $photo = PhotoTache::create([
            'tache_id' => $tache->id,
            'chemin_fichier' => $path,
            'commentaire' => $validated['commentaire'] ?? null,
            'uploaded_by' => Auth::id(),
        ]);

        // Enregistrer l'activité
        $description = "{$user->name} a uploadé une photo pour la tâche \"{$tache->nom}\"";
        if (!empty($validated['commentaire'])) {
            $description .= " avec le commentaire : \"{$validated['commentaire']}\"";
        }

        Activity::create([
            'user_id' => $user->id,
            'tache_id' => $tache->id,
            'chantier_id' => $tache->chantier_id,
            'type' => 'photo_uploaded',
            'description' => $description,
            'data' => [
                'photo_id' => $photo->id,
                'tache_nom' => $tache->nom,
                'commentaire' => $validated['commentaire'] ?? null,
            ],
        ]);

        return redirect()->route('chantiers.mes-taches')
            ->with('success', 'Photo ajoutée avec succès.');
    }

    // Supprimer une photo de tâche
    public function deletePhotoTache(Tache $tache, PhotoTache $photo)
    {
        $user = Auth::user();
        
        // Vérifier que l'utilisateur est assigné à cette tâche
        if (!$tache->techniciens->contains($user->id)) {
            abort(403, 'Vous n\'êtes pas assigné à cette tâche.');
        }

        if ($photo->tache_id !== $tache->id) {
            abort(404);
        }

        // Vérifier que c'est le propriétaire de la photo ou un admin
        if ($photo->uploaded_by !== $user->id && $user->role !== 'admin') {
            abort(403, 'Vous ne pouvez supprimer que vos propres photos.');
        }

        // Supprimer le fichier
        if (Storage::disk('public')->exists($photo->chemin_fichier)) {
            Storage::disk('public')->delete($photo->chemin_fichier);
        }

        $photo->delete();

        return redirect()->route('chantiers.mes-taches')
            ->with('success', 'Photo supprimée avec succès.');
    }

    // Vue des activités pour l'administrateur
    public function activities(Request $request)
    {
        $user = Auth::user();
        
        // Seuls les admins peuvent voir les activités
        if ($user->role !== 'admin') {
            abort(403, 'Vous n\'avez pas la permission de voir les activités.');
        }

        $query = Activity::with(['user', 'tache', 'chantier.quote.client'])
            ->with(['tache.photos' => function($q) {
                $q->latest()->limit(1);
            }])
            ->latest();

        // Filtre par type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filtre par technicien
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filtre par chantier
        if ($request->filled('chantier_id')) {
            $query->where('chantier_id', $request->chantier_id);
        }

        // Filtre par date
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $activities = $query->paginate(30)->withQueryString();
        
        $techniciens = User::where('role', '!=', 'admin')->orderBy('name')->get();
        $chantiers = Chantier::with('quote.client')->orderBy('created_at', 'desc')->get();
        $settings = \App\Models\Setting::getSettings();

        return view('chantiers.activities', compact('activities', 'techniciens', 'chantiers', 'settings'));
    }

    // Ajouter/modifier un commentaire sur une photo
    public function updatePhotoTacheComment(Request $request, Tache $tache, PhotoTache $photo)
    {
        $user = Auth::user();
        
        // Vérifier que l'utilisateur est assigné à cette tâche
        if (!$tache->techniciens->contains($user->id)) {
            abort(403, 'Vous n\'êtes pas assigné à cette tâche.');
        }

        if ($photo->tache_id !== $tache->id) {
            abort(404);
        }

        $validated = $request->validate([
            'commentaire' => 'nullable|string|max:1000',
        ]);

        $oldComment = $photo->commentaire;
        $photo->update($validated);

        // Enregistrer l'activité si le commentaire a changé
        if ($oldComment != $validated['commentaire']) {
            $description = "{$user->name} a ";
            if (empty($oldComment) && !empty($validated['commentaire'])) {
                $description .= "ajouté un commentaire à une photo de la tâche \"{$tache->nom}\"";
            } elseif (!empty($oldComment) && !empty($validated['commentaire'])) {
                $description .= "modifié le commentaire d'une photo de la tâche \"{$tache->nom}\"";
            } else {
                $description .= "supprimé le commentaire d'une photo de la tâche \"{$tache->nom}\"";
            }

            Activity::create([
                'user_id' => $user->id,
                'tache_id' => $tache->id,
                'chantier_id' => $tache->chantier_id,
                'type' => 'photo_comment_added',
                'description' => $description,
                'data' => [
                    'photo_id' => $photo->id,
                    'tache_nom' => $tache->nom,
                    'old_commentaire' => $oldComment,
                    'new_commentaire' => $validated['commentaire'] ?? null,
                ],
            ]);
        }

        return redirect()->route('chantiers.mes-taches')
            ->with('success', 'Commentaire mis à jour avec succès.');
    }
}

