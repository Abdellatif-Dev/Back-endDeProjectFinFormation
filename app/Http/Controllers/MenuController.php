<?php

namespace App\Http\Controllers;

use App\Models\CommandesDetail;
use App\Models\comment;
use App\Models\menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $m = menu::with('restaurant')->get();
        return response()->json($m);
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
            'name' => 'nullable|string|max:255',
            'category' => 'nullable|string|max:255',
            'prix' => 'nullable|numeric',
            'description' => 'nullable|string',
            'image_plate' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $path = null;

        if ($request->hasFile('image_plate')) {
            $image = $request->file('image_plate');
            $filename = time() . '_' . $image->getClientOriginalName();
            $path = $image->storeAs('Plats', $filename, 'public');
        }


        $plate = menu::create([
            'name' => $request->name,
            'category' => $request->category,
            'prix' => $request->prix,
            'description' => $request->description,
            'restaurant_id' => $request->ID,
            'image_plate' => $path,
        ]);

        return response()->json($plate);
    }

    /**
     * Display the specified resource.
     */
    public function show(menu $menu)
    {
        $plate = menu::with(['commandeDetails','restaurant'])
            ->where('id', $menu->id)
            ->first();
        $lesComments=comment::with('user')
        ->where('menu_id', $menu->id)
        ->get();
        $lesCommandes = CommandesDetail::with('commande')
            ->where('menu_id', $menu->id)
            ->get();
        return response()->json(['plate'=>$plate,'lesCommandes'=>$lesCommandes,'lesComments'=>$lesComments]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(menu $menu)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, menu $menu)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(menu $menu)
    {
        $menu->delete();

        return response()->json([
            'message' => 'Plat supprimé avec succès'
        ]);
    }

    public function menuPourUnResto(Request $request)
    {
        $plats = menu::where('restaurant_id', $request->query('id'))->get();

        return response()->json($plats);
    }
}
