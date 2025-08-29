<?php

// app/Http/Controllers/Dashboard/StudentDashboardController.php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class StudentDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Stats rapides – adapte selon tes modèles
        $attemptsCount = $user->examAttempts()->count();
        $avgScore      = round($user->examAttempts()->whereNotNull('score')->avg('score') ?? 0, 1);
        $lastAttempts  = $user->examAttempts()->with('exam')->latest()->take(5)->get();

        return view('dashboard.student', compact('user','attemptsCount','avgScore','lastAttempts'));
    }
}

