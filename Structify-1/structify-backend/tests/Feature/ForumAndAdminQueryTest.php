<?php

namespace Tests\Feature;

use App\Models\ForumPost;
use App\Models\Topic;
use App\Models\Track;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ForumAndAdminQueryTest extends TestCase
{
    use RefreshDatabase;

    public function test_forum_search_keeps_topic_filter_and_hides_replies(): void
    {
        $user  = User::factory()->create();
        $track = Track::create(['name' => 'DSA', 'slug' => 'dsa']);
        $stack = Topic::create(['track_id' => $track->id, 'title' => 'Stack', 'slug' => 'stack']);
        $queue = Topic::create(['track_id' => $track->id, 'title' => 'Queue', 'slug' => 'queue']);

        $match = ForumPost::create(['user_id' => $user->id, 'topic_id' => $stack->id, 'title' => 'pop', 'body' => 'stack pop help']);
        ForumPost::create(['user_id' => $user->id, 'topic_id' => $queue->id, 'title' => 'q', 'body' => 'pop in queue?']);
        ForumPost::create(['user_id' => $user->id, 'topic_id' => $stack->id, 'parent_id' => $match->id, 'body' => 'pop reply']);

        $ids = collect(
            $this->getJson("/api/forum?topic_id={$stack->id}&search=pop")->assertOk()->json('data')
        )->pluck('id');

        $this->assertEquals([$match->id], $ids->all());
    }

    public function test_public_forum_list_reports_is_upvoted_for_logged_in_user(): void
    {
        $user  = User::factory()->create();
        $track = Track::create(['name' => 'DSA', 'slug' => 'dsa']);
        $topic = Topic::create(['track_id' => $track->id, 'title' => 'Stack', 'slug' => 'stack']);
        $post  = ForumPost::create(['user_id' => $user->id, 'topic_id' => $topic->id, 'title' => 't', 'body' => 'body text']);
        $post->upvoters()->attach($user->id);

        $this->withToken($user->createToken('t')->plainTextToken)
            ->getJson('/api/forum')
            ->assertOk()
            ->assertJsonPath('data.0.is_upvoted', true);
    }

    public function test_admin_user_search_respects_role_filter(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'name' => 'Boss Budi']);
        User::factory()->create(['role' => 'user', 'name' => 'Budi Biasa']);

        $roles = collect(
            $this->withToken($admin->createToken('a')->plainTextToken)
                ->getJson('/api/admin/users?search=Budi&role=user')
                ->assertOk()
                ->json('data')
        )->pluck('role')->unique()->values()->all();

        $this->assertSame(['user'], $roles);
    }
}
