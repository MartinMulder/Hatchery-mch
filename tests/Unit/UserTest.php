<?php

namespace Tests\Unit;

use App\Models\Project;
use App\Models\User;
use App\Models\Vote;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseTransactions, DatabaseMigrations;

    /**
     * Assert the User has a relation with zero or more Project(s).
     */
    public function testUserProjectsRelationship()
    {
        $user = factory(User::class)->create();
        $this->be($user);
        $this->assertEmpty($user->projects);
        $project = factory(Project::class)->create(['user_id' => $user->id]);
        $user = User::find($user->id);
        $this->assertCount(1, $user->projects);
        $this->assertInstanceOf(Collection::class, $user->projects);
        $this->assertInstanceOf(Project::class, $user->projects->first());
        $this->assertEquals($project->id, $user->projects->first()->id);
        factory(Project::class)->create(['user_id' => $user->id]);
        $user = User::find($user->id);
        $this->assertCount(2, $user->projects);
    }

    /**
     * Assert the User has a relation with zero or more Votes(s).
     */
    public function testUserVotesRelationship()
    {
        $user = factory(User::class)->create();
        $this->be($user);
        $this->assertEmpty($user->votes);
        $user = User::find($user->id);
        $vote = factory(Vote::class)->create(['user_id' => $user->id]);
        $this->assertCount(1, $user->votes);
        $this->assertInstanceOf(Collection::class, $user->votes);
        $this->assertInstanceOf(Vote::class, $user->votes->first());
        $this->assertEquals($vote->id, $user->votes->first()->id);
        factory(Vote::class)->create(['user_id' => $user->id]);
        $user = User::find($user->id);
        $this->assertCount(2, $user->projects);
    }
}
