<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;

use App\Http\Controllers\SujetController;          // listing L1/L2 si tu l'utilises encore
use App\Http\Controllers\PracticeController;       // sujets -> entraînement
use App\Http\Controllers\Student\ExamAttemptController;

use App\Http\Controllers\Dashboard\StudentDashboardController;
use App\Http\Controllers\Dashboard\AdminDashboardController;

use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\ExamController;
use App\Http\Controllers\Admin\ExamBuilderController;
use App\Http\Controllers\Admin\AdminResultsController;
use App\Http\Controllers\Admin\StudentController;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

// Listes des sujets L1 / L2 (si tu gardes ce contrôleur)
Route::get('/licence1', [SujetController::class, 'indexL1'])->name('subjects.l1');
Route::get('/licence2', [SujetController::class, 'indexL2'])->name('subjects.l2');

/*
|--------------------------------------------------------------------------
| Auth (Breeze)
|--------------------------------------------------------------------------
*/
require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    // Profil
    Route::get('/profile',  [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile',[ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile',[ProfileController::class, 'destroy'])->name('profile.destroy');
});

/*
|--------------------------------------------------------------------------
| SUJETS (Entraînement avec correction immédiate)
|--------------------------------------------------------------------------
| ⚠️ UNE SEULE définition pour /subjects/{subject} -> PracticeController
*/
Route::get('/subjects/{subject}', [PracticeController::class, 'show'])->name('subjects.practice');
Route::post('/subjects/{subject}/submit', [PracticeController::class, 'submit'])->name('subjects.practice.submit');

/*
|--------------------------------------------------------------------------
| EXAMENS (chronométrés, pas de score immédiat étudiant)
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/exams',                 [ExamAttemptController::class, 'index'])->name('student.exams.index');
    Route::get('/exams/{exam}',          [ExamAttemptController::class, 'start'])->name('student.exams.start');
    Route::post('/exams/{exam}/submit',  [ExamAttemptController::class, 'submit'])->name('student.exams.submit');
    Route::get('/exams/{exam}/thanks',   [ExamAttemptController::class, 'thanks'])->name('student.exams.thanks');
});

/*
|--------------------------------------------------------------------------
| Dashboards
|--------------------------------------------------------------------------
*/
// Étudiant (on autorise admin aussi si tu le souhaites)
Route::get('/dashboard', [StudentDashboardController::class, 'index'])
    ->middleware(['auth','role:student,admin'])
    ->name('dashboard');

// Admin
Route::prefix('admin')->name('admin.')->middleware(['auth','role:admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // CRUD admin
    Route::resource('units', UnitController::class);
    Route::resource('subjects', SubjectController::class);
    Route::resource('questions', QuestionController::class);
    Route::resource('exams', ExamController::class);
 Route::resource('students', StudentController::class)->only(['index']);
    // Builder d’examens
    Route::get('/exams/{exam}/builder',  [ExamBuilderController::class,'edit'])->name('exams.builder.edit');
    Route::post('/exams/{exam}/builder', [ExamBuilderController::class,'update'])->name('exams.builder.update');

    // Résultats + export
    Route::get('/results',         [AdminResultsController::class, 'index'])->name('results.index');
    Route::get('/results/export',  [AdminResultsController::class, 'export'])->name('results.export');

    // Export d’un examen
    Route::get('exports/exam/{exam}/{format}', [ExamController::class, 'export'])->name('exams.export');
});

/*
|--------------------------------------------------------------------------
| Debug (temporaire)
|--------------------------------------------------------------------------
*/
