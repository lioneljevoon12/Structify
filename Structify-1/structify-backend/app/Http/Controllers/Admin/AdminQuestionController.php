<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Question;
use App\Http\Requests\StoreQuestionRequest;
use Illuminate\Http\Request;

class AdminQuestionController extends Controller
{
    public function index(Request $request)
    {
        $questions = Question::with('topic:id,title')
            ->when($request->topic_id, fn($q) => $q->where('topic_id', $request->topic_id))
            ->orderBy('topic_id')
            ->get();

        return response()->json(['data' => $questions]);
    }

    public function store(StoreQuestionRequest $request)
    {
        $question = Question::create($request->validated());

        return response()->json([
            'message' => 'Soal berhasil dibuat',
            'data'    => $question,
        ], 201);
    }

    public function update(StoreQuestionRequest $request, $id)
    {
        $question = Question::findOrFail($id);
        $question->update($request->validated());

        return response()->json([
            'message' => 'Soal berhasil diupdate',
            'data'    => $question,
        ]);
    }

    public function destroy($id)
    {
        $question = Question::findOrFail($id);
        $question->delete();

        return response()->json(['message' => 'Soal berhasil dihapus']);
    }
}