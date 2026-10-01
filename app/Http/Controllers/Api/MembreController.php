<?php

namespace App\Http\Controllers\Api;

use App\Models\Membre;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;


class MembreController extends Controller
{
    /**
     * Liste des membres
     */
    public function index()
    {
        $membres = Membre::with('user')
            ->orderBy('ordre_tour')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $membres
        ]);
    }

    /**
     * Afficher un membre
     */
    public function show($id)
    {
        $membre = Membre::with([
            'user',
            'cotisations',
            'distributions'
        ])->find($id);

        if (!$membre) {
            return response()->json([
                'success' => false,
                'message' => 'Membre introuvable.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $membre
        ]);
    }

    /**
     * Ajouter un membre
     */
    public function store(Request $request)
{
    try {
        $validated = $request->validate([
            'nom_memb' => 'required|string|max:100',
            'telephone' => 'required|string|max:20',
            'ordre_tour' => 'required|integer|unique:membres,ordre_tour',
            'frequence_cotisation' => 'required|in:jour,semaine,mois',
            'montant_cotisation' => 'required|numeric|min:0',
        ]);

        $membre = Membre::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Membre créé avec succès.',
            'data' => $membre
        ], 201);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur de validation',
            'errors' => $e->errors()
        ], 422);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Erreur serveur : ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * Modifier un membre
     */
    public function update(Request $request, $id)
    {
        $membre = Membre::find($id);

        if (!$membre) {
            return response()->json([
                'success' => false,
                'message' => 'Membre introuvable.'
            ], 404);
        }

        $validated = $request->validate([
            'nom_memb' => 'sometimes|string|max:100',
            'telephone' => 'sometimes|string|max:20',
            'ordre_tour' => 'sometimes|integer|unique:membres,ordre_tour,' . $id . ',id_memb',
            'frequence_cotisation' => 'sometimes|in:jour,semaine,mois',
            'montant_cotisation' => 'sometimes|numeric|min:0',
        ]);

        $membre->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Membre modifié avec succès.',
            'data' => $membre
        ]);
    }

    /**
     * Supprimer un membre
     */
    public function destroy($id)
    {
        $membre = Membre::find($id);

        if (!$membre) {
            return response()->json([
                'success' => false,
                'message' => 'Membre introuvable.'
            ], 404);
        }

        $membre->delete();

        return response()->json([
            'success' => true,
            'message' => 'Membre supprimé avec succès.'
        ]);
    }
}