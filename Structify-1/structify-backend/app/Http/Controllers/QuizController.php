<?php

namespace App\Http\Controllers;

use App\Models\Question;
use App\Models\Topic;
use App\Models\QuizAttempt;
use App\Models\QuizAttemptAnswer;
use App\Http\Requests\QuizSubmitRequest;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function getQuestions($topicId)
    {
        $topic = Topic::findOrFail($topicId);

        $questions = Question::where('topic_id', $topicId)
            ->select('id', 'question_text', 'option_a', 'option_b', 'option_c', 'option_d', 'timer_seconds')
            ->get();

        return response()->json([
            'topic'     => ['id' => $topic->id, 'title' => $topic->title],
            'questions' => $questions,
        ]);
    }

    public function submit(QuizSubmitRequest $request)
    {
        $topic = Topic::findOrFail($request->topic_id);

        $questionIds = collect($request->answers)->pluck('question_id');
        $questions   = Question::whereIn('id', $questionIds)->get()->keyBy('id');

        $correctCount = 0;
        $answerRecords = [];

        foreach ($request->answers as $answer) {
            $question  = $questions[$answer['question_id']] ?? null;
            if (!$question) continue;

            $isCorrect = strtoupper($answer['selected_answer']) === strtoupper($question->correct_answer);
            if ($isCorrect) $correctCount++;

            $answerRecords[] = [
                'question_id'     => $question->id,
                'selected_answer' => strtoupper($answer['selected_answer']),
                'is_correct'      => $isCorrect,
            ];
        }

        $total = count($answerRecords);
        $score = $total > 0 ? round(($correctCount / $total) * 100, 2) : 0;

        $attempt = QuizAttempt::create([
            'user_id'         => $request->user()->id,
            'topic_id'        => $topic->id,
            'score'           => $score,
            'total_questions' => $total,
            'correct_answers' => $correctCount,
            'completed_at'    => now(),
        ]);

        foreach ($answerRecords as $record) {
            QuizAttemptAnswer::create(array_merge(
                $record, ['quiz_attempt_id' => $attempt->id]
            ));
        }

        return response()->json([
            'message'         => 'Quiz selesai',
            'score'           => $score,
            'correct_answers' => $correctCount,
            'total_questions' => $total,
            'attempt_id'      => $attempt->id,
        ]);
    }

    public function history(Request $request)
    {
        $history = QuizAttempt::where('user_id', $request->user()->id)
            ->with('topic:id,title,slug')
            ->orderByDesc('completed_at')
            ->get();

        return response()->json(['data' => $history]);
    }

    public function detail(Request $request, $id)
    {
        $attempt = QuizAttempt::where('user_id', $request->user()->id)
            ->with([
                'topic:id,title',
                'answers.question:id,question_text,correct_answer,option_a,option_b,option_c,option_d',
            ])
            ->findOrFail($id);

        return response()->json(['data' => $attempt]);
    }
}