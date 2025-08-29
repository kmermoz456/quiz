<?php

// app/Http/Controllers/Dashboard/AdminDashboardController.php
namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\{Unit, Subject, Question, Exam, User, ExamAttempt};

class AdminDashboardController extends Controller
{
    public function index()
    {
        $unitsCount     = Unit::count();
        $subjectsCount  = Subject::count();
        $questionsCount = Question::count();
        $examsCount     = Exam::count();
        $studentsCount  = User::where('role','student')->count();

        $latestExams    = Exam::latest()->take(4)->get(['id','title']);
        $latestAttempts = ExamAttempt::with(['exam','user'])
                            ->latest()->take(8)->get();

        // Petites séries pour les charts (ex: tentatives par mois)
        $seriesMonthly = ExamAttempt::selectRaw("DATE_FORMAT(created_at,'%Y-%m') m, COUNT(*) c")
                            ->groupBy('m')->orderBy('m')->get();

        return view('dashboard.admin', compact(
            'unitsCount','subjectsCount','questionsCount','examsCount',
            'studentsCount','latestExams','latestAttempts','seriesMonthly'
        ));
    }
}
