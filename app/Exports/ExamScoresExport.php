<?php
namespace App\Exports;
use App\Models\Exam;
use Maatwebsite\Excel\Concerns\FromCollection;
class ExamScoresExport implements FromCollection
{
public function __construct(public Exam $exam){}
public function collection()
{
$rows = collect([
['Étudiant','Exam','Score','Max','%','Soumis le']
]);
foreach($this->exam->attempts()->with('user')->get() as $a){
$rows->push([
$a->user->name,
$this->exam->title,
$a->score,
$a->max_score,
$a->max_score ? round(100*$a->score/$a->max_score,1).'%' :
'0%',$a->submitted_at,
]);
}
return $rows;
}
}