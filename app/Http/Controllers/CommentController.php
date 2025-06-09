<?php

namespace App\Http\Controllers;

use App\Models\comment;
use App\Models\CommentResto;
use Illuminate\Http\Request;

class CommentController extends Controller
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
        $validated = $request->validate([
            'menu_id' => 'required|exists:menus,id',
            'user_id' => 'required|exists:users,id',
            'comment' => 'required|string',
            'rating' => 'required|numeric|min:1|max:5',
        ]);

        $exists = comment::where('menu_id', $validated['menu_id'])
            ->where('user_id', $validated['user_id'])
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'Vous avez déjà commenté ce plat.'
            ], 403);
        }

        $comment = comment::create([
            'menu_id' => $validated['menu_id'],
            'user_id' => $validated['user_id'],
            'comment' => $validated['comment'],
            'rating' => $validated['rating'],
        ]);

        return response()->json([
            'message' => 'Commentaire ajouté avec succès.',
            'comment' => $comment
        ], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(comment $comment)
    {
        //
    }
    public function ajouterCommenteResto(Request $request)
    {
        $validated = $request->validate([
            'restaurant_id' => 'required|exists:users,id',
            'user_id' => 'required|exists:users,id',
            'comment' => 'required|string',
            'rating' => 'required|numeric|min:1|max:5',
        ]);

        $exists = CommentResto::where('restaurant_id', $validated['restaurant_id'])
            ->where('user_id', $validated['user_id'])
            ->exists();

        if ($exists) {
            return response()->json('Vous avez déjà commenté ce plat.');
        }

        $comment = CommentResto::create([
            'restaurant_id' => $validated['restaurant_id'],
            'user_id' => $validated['user_id'],
            'comment' => $validated['comment'],
            'rating' => $validated['rating'],
        ]);

        return response()->json('Commentaire ajouté avec succès.');
    }
}
