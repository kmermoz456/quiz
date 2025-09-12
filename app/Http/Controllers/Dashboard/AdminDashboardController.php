<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\{Unit, Subject, Question, Exam, User, ExamAttempt};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $unitsCount     = Unit::count();
        $subjectsCount  = Subject::count();
        $questionsCount = Question::count();
        $examsCount     = Exam::count();
        $studentsCount  = User::where('role', 'student')->count();

        $latestExams    = Exam::latest()->take(4)->get(['id','title']);
        $latestAttempts = ExamAttempt::with(['exam','user'])
                                ->latest()->take(8)->get();

        // -------- Série mensuelle compatible SGBD --------
        $driver = DB::getDriverName(); // 'pgsql', 'mysql', 'sqlite', ...

        if ($driver === 'pgsql') {
            // PostgreSQL : date_trunc + format en PHP
            $seriesMonthly = ExamAttempt::selectRaw("date_trunc('month', created_at) as m, COUNT(*) as c")
                ->groupBy('m')
                ->orderBy('m')
                ->get()
                ->map(fn ($r) => [
                    'm' => Carbon::parse($r->m)->format('Y-m'),
                    'c' => (int) $r->c,
                ]);
        } elseif ($driver === 'sqlite') {
            // SQLite
            $seriesMonthly = ExamAttempt::selectRaw("strftime('%Y-%m', created_at) as m, COUNT(*) as c")
                ->groupBy('m')
                ->orderBy('m')
                ->get()
                ->map(fn ($r) => ['m' => $r->m, 'c' => (int) $r->c]);
        } else {
            // MySQL / MariaDB
            $seriesMonthly = ExamAttempt::selectRaw("DATE_FORMAT(created_at,'%Y-%m') as m, COUNT(*) as c")
                ->groupBy('m')
                ->orderBy('m')
                ->get()
                ->map(fn ($r) => ['m' => $r->m, 'c' => (int) $r->c]);
        }
        // --------------------------------------------------

        return view('dashboard.admin', compact(
            'unitsCount','subjectsCount','questionsCount','examsCount',
            'studentsCount','latestExams','latestAttempts','seriesMonthly'
        ));
    }
}
