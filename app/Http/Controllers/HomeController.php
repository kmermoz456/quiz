<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Subject;

class HomeController extends Controller
{
    public function index()
    {
        // Petites données pour animer l'accueil (facultatif)
        $stats = [
            'l1'       => Subject::where('level', 'L1')->count(),
            'l2'       => Subject::where('level', 'L2')->count(),
            'exams'    => Exam::count(),
        ];

        // Examens publiés et ouverts bientôt (optionnel)
        $latestExams = Exam::with('subject.unit')
            ->where('is_published', true)
            ->latest()
            ->take(6)
            ->get();

        // La page d’accueil que tu as déjà (welcome.blade.php)
        return view('welcome', compact('stats', 'latestExams'));
    }
}
