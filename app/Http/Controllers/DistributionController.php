<?php

namespace App\Http\Controllers;

use App\Models\Cotisation;
use App\Models\Distribution;
use App\Models\Membre;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DistributionController extends Controller
{
    /**
     * Historique des distributions
     */
    public function index()
    {
        $distributions = Distribution::with('membre')
            ->orderByDesc('date_distribution')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $distributions
        ]);
    }

    /**
     * Vérifier si la distribution est possible
     */
    public function etat(Request $request)
    {
        $mois = $request->input('mois', now()->format('Y-m-d'));

        $membres = Membre::orderBy('ordre_tour')->get();

        $etat = [];

        foreach ($membres as $membre) {

            $totalPaye = Cotisation::where('id_memb', $membre->id_memb)
                ->whereDate('mois', $mois)
                ->sum('montant');

            $reste = max(
                0,
                $membre->montant_cotisation - $totalPaye
            );

            $etat[] = [
                'id_memb' => $membre->id_memb,
                'nom_memb' => $membre->nom_memb,
                'ordre_tour' => $membre->ordre_tour,
                'montant_attendu' => $membre->montant_cotisation,
                'total_paye' => $totalPaye,
                'reste' => $reste,
                'paye' => $totalPaye >= $membre->montant_cotisation
            ];
        }

        $tousOntPaye = collect($etat)->every(
            fn ($membre) => $membre['paye']
        );

        return response()->json([
            'success' => true,
            'mois' => $mois,
            'distribution_autorisee' => $tousOntPaye,
            'membres' => $etat
        ]);
    }

    /**
     * Effectuer une distribution
     */
    public function store(Request $request)
    {
        $mois = $request->input(
            'mois',
            now()->format('Y-m-d')
        );

        return DB::transaction(function () use ($mois) {

            $dejaDistribue = Distribution::whereDate(
                'mois',
                $mois
            )->exists();

            if ($dejaDistribue) {
                return response()->json([
                    'success' => false,
                    'message' => 'Une distribution existe déjà pour ce cycle.'
                ], 422);
            }

            $membres = Membre::orderBy('ordre_tour')->get();

            if ($membres->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Aucun membre dans la tontine.'
                ], 422);
            }

            foreach ($membres as $membre) {

                $totalPaye = Cotisation::where('id_memb', $membre->id_memb)
                    ->whereDate('mois', $mois)
                    ->sum('montant');

                if ($totalPaye < $membre->montant_cotisation) {

                    return response()->json([
                        'success' => false,
                        'message' => 'Distribution bloquée.',
                        'membre_non_a_jour' => $membre->nom_memb,
                        'montant_attendu' => $membre->montant_cotisation,
                        'total_paye' => $totalPaye,
                        'reste' => $membre->montant_cotisation - $totalPaye
                    ], 422);
                }
            }

            /*
             * Déterminer le bénéficiaire.
             *
             * Pour l'instant, on détermine le prochain bénéficiaire
             * en fonction de l'historique des distributions.
             */

            $nombreDistributions = Distribution::count();

            $index = $nombreDistributions % $membres->count();

            $beneficiaire = $membres[$index];

            $montantTotal = Cotisation::whereDate(
                'mois',
                $mois
            )->sum('montant');

            $distribution = Distribution::create([
                'mois' => $mois,
                'montant_remis' => $montantTotal,
                'id_memb' => $beneficiaire->id_memb,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Distribution effectuée avec succès.',
                'beneficiaire' => $beneficiaire,
                'montant_remis' => $montantTotal,
                'distribution' => $distribution
            ], 201);
        });
    }
}