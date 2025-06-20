<?php

namespace App\Http\Controllers;

use App\Models\commande;
use App\Models\CommandesDetail;
use App\Models\DevoirDExecution;
use App\Models\User;
use App\Notifications\CommandeLivreeNotification;
use App\Notifications\NewCommandeNotification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\FuncCall;

class CommandeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:users,id',
            'tele' => 'required|digits_between:8,15',
        ]);

        $user = User::find($request->id);

        if ($user->tele == null) {
            $user->tele = $request->tele;
            $user->save();
        }
        $commande = commande::create([
            'user_id' => $request->id,
            'total_price' => $request->total,
            'date' => Carbon::now()->toDateString(),
            'address' =>  $request->adresse
        ]);
        foreach ($request->command as $detail) {
            $commandDetail = CommandesDetail::create([
                'commande_id' => $commande->id,
                'menu_id' => $detail['id'],
                'restaurant_id' => $detail['restaurant_id'],
                'quantity' => $detail['quantity'],
                'total_price' => $detail['prix'] * $detail['quantity'],
                'status' => 'en attente',
            ]);
            $restaurant = User::find($detail['restaurant_id']);
            if ($restaurant) {
                $restaurant->notify(new NewCommandeNotification($commandDetail));
            }
        }

        return response()->json(
            'Commande et détails créés avec succès',
            201
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(commande $commande) {}

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(commande $commande)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, commande $commande)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(commande $commande)
    {
        //
    }
    public function showCommande($id)
    {
        $lesCommandes = CommandesDetail::with(['menu', 'commande'])
            ->where('restaurant_id', $id)
            ->get();
        return response()->json($lesCommandes, 201);
    }
    public function showCommandeClient($id)
    {
        $lesCommandes = commande::with(['details.menu'])
            ->where('user_id', $id)
            ->get();
        return response()->json($lesCommandes, 201);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:en attente,acceptée,refusée,livrée'
        ]);

        $detail = CommandesDetail::find($id);
        if (!$detail) {
            return response()->json(['message' => 'Commande detail non trouvé'], 404);
        }

        $detail->status = $request->status;
        $detail->save();

        if ($request->status === 'livrée') {
            $menu = $detail->menu;
            $restaurantId = $menu->restaurant_id;
            $commission = ($menu->prix * $detail->quantity) * 0.1;

            $id = $detail->commande->user_id;
            $client = User::find($id);
            if ($client) {
                $client->notify(new CommandeLivreeNotification($detail));
            }
            $now = Carbon::now();
            $mois = $now->month;
            $annee = $now->year;

            $devoir = DevoirDExecution::where('restaurant_id', $restaurantId)
                ->where('mois', $mois)
                ->where('annee', $annee)
                ->first();

            if ($devoir) {
                $devoir->montant += $commission;
                $devoir->save();
            } else {
                DevoirDExecution::create([
                    'restaurant_id' => $restaurantId,
                    'mois' => $mois,
                    'annee' => $annee,
                    'montant' => $commission,
                    'etat' => 'non payé',
                ]);
            }
        }

        return response()->json(['message' => 'Status mis à jour avec succès', 'data' => $detail], 200);
    }
    public function ChangeEtatDevoir(Request $request, $id)
    {
        $devoir = DevoirDExecution::find($id);
        $devoir->etat = $request->etat;
        $devoir->save();

        return response()->json('État mis à jour');
    }
}
