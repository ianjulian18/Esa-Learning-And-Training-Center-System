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
            'question_banks' => QuestionBank::withCount('questions')->get()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string'
        ]);

        QuestionBank::create($validated);
        return back()->with('success', 'Question Bank created successfully.');
    }

    public function destroy(QuestionBank $questionBank)
    {
        $questionBank->delete();
        return back()->with('success', 'Question Bank deleted successfully.');
    }

    public function show($id)
    {
        $questionBank = QuestionBank::with('questions')->findOrFail($id);
        return Inertia::render('Admin/Assessments/Questions', [
            'bank' => $questionBank
        ]);
    }

    public function storeQuestion(Request $request, $id)
    {
        $questionBank = QuestionBank::findOrFail($id);
        $validated = $request->validate([
            'question_text' => 'required|string',
            'type' => 'required|in:MULTIPLE_CHOICE,TRUE_FALSE,ESSAY',
            'options' => 'nullable|array',
            'answer_key' => 'required|string',
            'points' => 'required|integer|min:1'
        ]);

        $questionBank->questions()->create($validated);
        return back()->with('success', 'Question added successfully.');
    }

    public function destroyQuestion($bankId, $questionId)
    {
        Question::where('id', $questionId)->where('question_bank_id', $bankId)->delete();
        return back()->with('success', 'Question deleted successfully.');
    }
}
