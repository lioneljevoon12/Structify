<?php

namespace Tests\Feature;

use App\Models\Question;
use App\Models\Topic;
use App\Models\Track;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class QuizSubmitTest extends TestCase
{
    use RefreshDatabase;

    private function makeTopic(string $slug): Topic
    {
        $track = Track::firstOrCreate(['slug' => 'dsa'], ['name' => 'DSA']);

        return Topic::create(['track_id' => $track->id, 'title' => $slug, 'slug' => $slug]);
    }

    private function makeQuestion(Topic $topic, string $correct = 'A'): Question
    {
        return Question::create([
            'topic_id'       => $topic->id,
            'question_text'  => 'Q?',
            'option_a'       => 'a', 'option_b' => 'b', 'option_c' => 'c', 'option_d' => 'd',
            'correct_answer' => $correct,
            'timer_seconds'  => 30,
        ]);
    }

    public function test_duplicate_question_ids_are_rejected(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $topic = $this->makeTopic('stack');
        $q = $this->makeQuestion($topic);
        $this->makeQuestion($topic);

        $this->postJson('/api/quiz/submit', [
            'topic_id' => $topic->id,
            'answers'  => [
                ['question_id' => $q->id, 'selected_answer' => 'A'],
                ['question_id' => $q->id, 'selected_answer' => 'A'],
            ],
        ])->assertStatus(422)->assertJsonValidationErrors('answers.0.question_id');
    }

    public function test_questions_from_another_topic_are_rejected(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $topic = $this->makeTopic('stack');
        $other = $this->makeTopic('queue');
        $this->makeQuestion($topic);
        $foreign = $this->makeQuestion($other);

        $this->postJson('/api/quiz/submit', [
            'topic_id' => $topic->id,
            'answers'  => [['question_id' => $foreign->id, 'selected_answer' => 'A']],
        ])->assertStatus(422);
    }

    public function test_unanswered_questions_count_as_wrong(): void
    {
        Sanctum::actingAs(User::factory()->create());
        $topic = $this->makeTopic('stack');
        $q1 = $this->makeQuestion($topic, 'A');
        $this->makeQuestion($topic, 'B');

        $this->postJson('/api/quiz/submit', [
            'topic_id' => $topic->id,
            'answers'  => [['question_id' => $q1->id, 'selected_answer' => 'A']],
        ])->assertOk()->assertJson([
            'score'           => 50,
            'correct_answers' => 1,
            'total_questions' => 2,
        ]);

        $this->assertDatabaseCount('quiz_attempt_answers', 1);
    }
}
