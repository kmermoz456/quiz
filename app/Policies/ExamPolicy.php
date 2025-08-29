<?php
namespace App\Policies;
use App\Models\{User,Exam};
class ExamPolicy
{
public function update(User $user, Exam $exam): bool { return $user->isAdmin(); }
public function delete(User $user, Exam $exam): bool { return $user->isAdmin(); }
}