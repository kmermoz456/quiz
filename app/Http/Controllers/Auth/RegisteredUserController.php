<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\Rule;

class RegisteredUserController extends Controller
{
    public function create()
    {
        return view('auth.register');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'                  => ['required','string','max:255'],
            'email'                 => ['required','string','lowercase','email','max:255', Rule::unique('users','email')],
            'phone'                 => ['nullable','string','max:30'],
            'level'                 => ['nullable','in:L1,L2'], // rends-le 'required' si tu veux l'imposer
            'password'              => ['required', 'confirmed', Password::defaults()],
            // 'password_confirmation' est géré par 'confirmed'
        ]);

        // 🔐 Sécurité: ne pas laisser s’auto-créer admin
        $role = 'student';

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'phone'    => $validated['phone'] ?? null,
            'level'    => $validated['level'] ?? null,
            'password' => Hash::make($validated['password']),
        ]);

        event(new Registered($user));
        Auth::login($user);

       return redirect(route('dashboard', absolute: false));
    }
}


  