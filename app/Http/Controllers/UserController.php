<?php

namespace App\Http\Controllers;

use App\Models\CommentResto;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('devoirs')->get();
        return response()->json($users);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'client',
            'image' => 'user.png'
        ]);
        auth()->login($user);
        return response()->json($user, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $restaurant = user::with(['menus','commandeDetails.commande'])
            ->where('id', $id)
            ->first();
        $lesComments=CommentResto::with('user')
            ->where('restaurant_id',$id)
            ->get();
        
        return response()->json(['restaurant'=>$restaurant,'lesComments'=>$lesComments]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = user::find($id);
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'tele' => 'required|string|max:20',
            'image' => 'nullable|image|mimes:jpg,jpeg,png',
            'old_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:8',
        ]);

        // Vérification de mot de passe
        if ($request->filled('old_password') && $request->filled('new_password')) {
            if (!Hash::check($request->old_password, $user->password)) {
                return response()->json(['message' => 'Ancien mot de passe incorrect', 'user' => $user]);
            }
            $user->password = Hash::make($request->new_password);
        }

        // Mise à jour de l'image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $path = $image->storeAs('users', $filename, 'public');
            $user->image = $path;
        }

        $user->name = $request->name;
        $user->tele = $request->tele;
        $user->save();

        return response()->json(['message' => 'Profil mis à jour avec succès', 'user' => $user]);
    }
    
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user=User::find($id);
        $user->delete();
        return response()->json('Suppression terminée avec succès' );
    }
}
