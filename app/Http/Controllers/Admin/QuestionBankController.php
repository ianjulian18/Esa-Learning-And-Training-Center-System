<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuestionBank;
use App\Models\Question;
use Illuminate\Http\Request;
use Inertia\Inertia;

class QuestionBankController extends Controller
{
    public function index()
    {
        return Inertia::render('Admin/Assessments/QuestionBanks', [
            'banks' => QuestionBank::withCount('questions')->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        QuestionBank::create($validated);
        return back();
    }

    public function show(QuestionBank $questionBank)
    {
        $questionBank->load('questions');
        return Inertia::render('Admin/Assessments/Questions', [
            'bank' => $questionBank
        ]);
    }

    public function storeQuestion(Request $request, QuestionBank $questionBank)
    {
        $validated = $request->validate([
            'content' => 'required|string',
            'type' => 'required|in:MULTIPLE_CHOICE,TRUE_FALSE',
            'options' => 'required|array',
            'correct_answer' => 'required|string',
            'explanation' => 'nullable|string'
        ]);

        $questionBank->questions()->create($validated);
        return back();
    }
}
