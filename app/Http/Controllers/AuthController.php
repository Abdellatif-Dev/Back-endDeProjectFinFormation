<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
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
            'image' => 'user.png',
            'image_resto' => 'Image-Resto.png'
        ]);
        auth()->login($user);
        return response()->json($user, 201);
    }
    public function registerResto(Request $request)
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
            'role' => 'restaurant',
            'image' => 'user.png',
            'image_resto' => 'Image-Resto.png'
        ]);
        auth()->login($user);
        return response()->json($user, 201);
    }
    public function login(Request $request)
    {
         $request->validate([
        'email' => 'required|email',
        'password' => 'required|string',
    ]);

    $user = User::where('email', $request->email)->first();

    if (!$user || !Hash::check($request->password, $user->password)) {
        return response()->json(['message' => 'Email ou mot de passe incorrect'], 401);
    }

    Auth::login($user);

    return response()->json($user, 200);
        
        
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $user = auth()->user();
        return response()->json($user);
    }
    public function aff()
    {
        $user = auth()->user();
        return response()->json($user);
    }
    public function update_profile(Request $request)
    {
        $user = User::find($request->ID);

        if (!$user) {
            return response()->json(['message' => 'Utilisateur non trouvé'], 404);
        }

        $request->validate([
            'name' => 'nullable|string|max:255',
            'nameResto' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'tele' => 'nullable|numeric',
            'description' => 'nullable|string',
            'imageResto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user->name = $request->name ?? $user->name;
        $user->nameResto = $request->nameResto ?? $user->nameResto;
        $user->address = $request->address ?? $user->address;
        $user->tele = $request->tele ?? $user->tele;
        $user->description = $request->description ?? $user->description;
        if ($request->hasFile('image_resto')) {
            $image = $request->file('image_resto');
            $filename = time() . '_' . $image->getClientOriginalName();
            $path = $image->storeAs('restos', $filename, 'public');

            $user->image_resto = $path; 
        }

        $user->save();

        return response()->json($user);
    }
}
