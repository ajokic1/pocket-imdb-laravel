<?php

namespace Tests\Unit;

use App\Events\NewLikeEvent;
use App\Http\Controllers\Api\MovieController;
use App\Movie;
use App\User;
use Illuminate\Support\Facades\Event;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LikeTest extends TestCase
{
    use RefreshDatabase;

    private $controller;
    private $user;
    private $movie;

    public function setUp(): void
    {
        parent::setUp();

        Event::fake();

        $this->user = factory(User::class)->create();
        $this->be($this->user);

        $this->movie = factory(Movie::class)->create();
        $this->controller = new MovieController();
    }

    public function testUserCanLike()
    {
        $this->controller->like($this->movie);
        $this->movie->refresh();

        $this->assertTrue($this->movie->likes == 1);
        Event::assertDispatched(NewLikeEvent::class);
    }

    public function testUserCanDislike()
    {
        $this->controller->dislike($this->movie);
        $this->movie->refresh();

        $this->assertTrue($this->movie->dislikes == 1);
        Event::assertDispatched(NewLikeEvent::class);
    }

    public function testUserCanUnlikeIfLiked()
    {
        $this->controller->like($this->movie);
        $this->controller->unlike($this->movie);
        $this->movie->refresh();

        $this->assertTrue($this->movie->likes == 0);
        Event::assertDispatched(NewLikeEvent::class);
    }

    public function testUserCanUnlikeIfDisliked()
    {
        $this->controller->dislike($this->movie);
        $this->controller->unlike($this->movie);
        $this->movie->refresh();

        $this->assertTrue($this->movie->dislikes == 0);
        Event::assertDispatched(NewLikeEvent::class);
    }

    public function testThrowExceptionIfAlreadyLiked()
    {
        $this->expectException(HttpException::class);

        $this->controller->like($this->movie);
        $this->controller->like($this->movie);

        $this->assertTrue($this->movie->likes == 1);
    }

    public function testThrowExceptionIfAlreadyDisliked()
    {
        $this->expectException(HttpException::class);

        $this->controller->dislike($this->movie);
        $this->controller->dislike($this->movie);

        $this->assertTrue($this->movie->dislikes == 1);
    }

}
