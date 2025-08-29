<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExamAttempt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AdminResultsController extends Controller
{
    protected function dateColumn(): string
    {
        // Nouvel ordre logique : submitted_at > finished_at (legacy) > started_at > created_at
        if (Schema::hasColumn('exam_attempts', 'submitted_at')) return 'submitted_at';
        if (Schema::hasColumn('exam_attempts', 'finished_at'))  return 'finished_at';
        if (Schema::hasColumn('exam_attempts', 'started_at'))   return 'started_at';
        return 'created_at';
    }

    public function index(Request $request)
    {
        $request->validate([
            'start' => ['nullable','date'],
            'end'   => ['nullable','date','after_or_equal:start'],
            'level' => ['nullable','in:L1,L2'],
            'q'     => ['nullable','string','max:120'],
        ]);

        $start = $request->date('start');
        $end   = $request->date('end');
        $level = $request->string('level')->toString() ?: null;
        $q     = trim($request->string('q')->toString());

        $dateCol = $this->dateColumn();

        // Uniquement les tentatives réellement soumises et notées
        $base = ExamAttempt::query()
            ->with(['exam:id,title','user:id,name,email,level'])
            ->whereNotNull('score')
            ->whereNotNull($dateCol);

        if ($level) {
            $base->whereHas('user', fn($u) => $u->where('level', $level));
        }
        if ($start) {
            $base->whereDate($dateCol, '>=', $start->format('Y-m-d'));
        }
        if ($end) {
            $base->whereDate($dateCol, '<=', $end->format('Y-m-d'));
        }
        if ($q !== '') {
            $base->where(function ($qq) use ($q) {
                $qq->whereHas('user', fn($u) => $u->where('name','like',"%{$q}%")
                                                 ->orWhere('email','like',"%{$q}%"))
                   ->orWhereHas('exam', fn($e) => $e->where('title','like',"%{$q}%"));
            });
        }

        $avgScore = round((clone $base)->avg('score') ?? 0, 2);
        $count    = (clone $base)->count();

        $results = (clone $base)
            ->orderByDesc($dateCol)
            ->paginate(20)
            ->withQueryString();

        return view('admin.results.index', compact('results','avgScore','count','start','end','level','q'));
    }

    public function export(Request $request): StreamedResponse
    {
        $request->validate([
            'start' => ['nullable','date'],
            'end'   => ['nullable','date','after_or_equal:start'],
            'level' => ['nullable','in:L1,L2'],
            'q'     => ['nullable','string','max:120'],
        ]);

        $start = $request->date('start');
        $end   = $request->date('end');
        $level = $request->string('level')->toString() ?: null;
        $q     = trim($request->string('q')->toString());

        $dateCol = $this->dateColumn();

        $query = ExamAttempt::query()
            ->with(['exam:id,title','user:id,name,email,level'])
            ->whereNotNull('score')
            ->whereNotNull($dateCol);

        if ($level) $query->whereHas('user', fn($u) => $u->where('level', $level));
        if ($start) $query->whereDate($dateCol, '>=', $start->format('Y-m-d'));
        if ($end)   $query->whereDate($dateCol, '<=', $end->format('Y-m-d'));
        if ($q !== '') {
            $query->where(function ($qq) use ($q) {
                $qq->whereHas('user', fn($u) => $u->where('name','like',"%{$q}%")
                                                 ->orWhere('email','like',"%{$q}%"))
                   ->orWhereHas('exam', fn($e) => $e->where('title','like',"%{$q}%"));
            });
        }

        $filename = 'resultats_'.now()->format('Ymd_His').'.csv';

        return response()->streamDownload(function () use ($query, $dateCol) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Date','Étudiant','Email','Niveau','Examen','Score (%)'], ';');

            $query->orderBy($dateCol)->chunk(1000, function ($rows) use ($out, $dateCol) {
                foreach ($rows as $r) {
                    fputcsv($out, [
                        optional($r->{$dateCol})->format('Y-m-d H:i'),
                        $r->user->name ?? '',
                        $r->user->email ?? '',
                        $r->user->level ?? '',
                        $r->exam->title ?? '',
                        $r->score,
                    ], ';');
                }
            });

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }
}
