<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
         $q = trim((string) $request->query('q',''));
        $users = User::query()
            ->when($q !== '', fn($qr) => $qr
                ->where('name','like',"%{$q}%")
                ->orWhere('email','like',"%{$q}%"))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

              $adminsCount = User::where('role', 'admin')->count();

        return view('admin.users.index', compact('users', 'q', 'adminsCount'));

    }

    public function updateRole(Request $request, User $user)
    {
        $data = $request->validate([
            'role' => ['required', Rule::in(['student','admin'])],
        ]);

        // empêcher de retirer le dernier admin
        if ($user->role === 'admin' && $data['role'] !== 'admin') {
            $adminsLeft = User::where('role', 'admin')->where('id', '!=', $user->id)->count();
            if ($adminsLeft === 0) {
                return back()->with('error', "Impossible de rétrograder le dernier administrateur.");
            }
        }

        // (optionnel) empêcher de te retirer toi-même tes droits
        // if ($user->id === auth()->id() && $data['role'] !== 'admin') {
        //     return back()->with('error', "Tu ne peux pas te retirer tes droits admin.");
        // }

        $user->update(['role' => $data['role']]);

        return back()->with('ok', "Rôle de {$user->name} mis à jour en « {$data['role']} ».");
    }


      public function toggleSubscription(User $user)
    {
        // On ne change pas l’admin principal par erreur
        if ($user->id === 1) {
            return back()->with('error','Impossible de modifier l’abonnement de l’administrateur principal.');
        }

        $user->subscription_active = !$user->subscription_active;
        // (Optionnel) Tu peux aussi définir une date de fin si tu désactives :
        // $user->subscription_ends_at = $user->subscription_active ? null : now();
        $user->save();

        return back()->with('ok', "Abonnement de {$user->name} " . ($user->subscription_active ? 'activé' : 'désactivé') . '.');
    }
}
