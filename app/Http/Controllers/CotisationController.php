<?php

namespace App\Http\Controllers;

use App\Models\Cotisation;
use App\Models\Membre;
use Illuminate\Http\Request;

class CotisationController extends Controller
{
    /**
     * Liste des cotisations
     */
    public function index()
    {
        $cotisations = Cotisation::with('membre')
            ->orderByDesc('date_versement')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $cotisations
        ]);
    }

    /**
     * Enregistrer un paiement
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_memb' => 'required|exists:membres,id_memb',
            'mois' => 'required|date',
            'montant' => 'required|numeric|min:0.01',
        ]);

        $cotisation = Cotisation::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Cotisation enregistrée avec succès.',
            'data' => $cotisation->load('membre')
        ], 201);
    }

    /**
     * Cotisations d'un membre
     */
    public function membre($id)
    {
        $membre = Membre::find($id);

        if (!$membre) {
            return response()->json([
                'success' => false,
                'message' => 'Membre introuvable.'
            ], 404);
        }

        $cotisations = Cotisation::where('id_memb', $id)
            ->orderByDesc('date_versement')
            ->get();

        $totalPaye = $cotisations->sum('montant');

        return response()->json([
            'success' => true,
            'membre' => $membre,
            'cotisations' => $cotisations,
            'total_paye' => $totalPaye,
            'montant_attendu' => $membre->montant_cotisation,
            'reste' => max(
                0,
                $membre->montant_cotisation - $totalPaye
            )
        ]);
    }
}