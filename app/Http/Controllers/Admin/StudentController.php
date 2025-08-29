<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class StudentController extends Controller
{
    public function index()
    {
        $students = User::where('role','student')->orderBy('name')->paginate(20);
        return view('admin.students.index', compact('students'));
    }
}
