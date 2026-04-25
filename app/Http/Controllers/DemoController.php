<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\GreetingServiceInterface;
use App\Models\Post;
use App\Models\Video;
use App\Models\Comment;

class DemoController extends Controller
{
    protected $greetingService;

    // Dependency Injection via the Service Container
    public function __construct(GreetingServiceInterface $greetingService)
    {
        $this->greetingService = $greetingService;
    }

    public function index()
    {
        // 1. Demonstrate Service Container & Dependency Injection
        $greeting = $this->greetingService->greet('Interviewer');

        // 2. Demonstrate Polymorphic Behaviour (Simulated Memory Example)
        // We will create instances in memory since we might not have migrated the DB yet
        $post = new Post(['title' => 'Laravel Concepts', 'body' => 'Explaining Service Containers...']);
        $post->id = 1; // Fake ID

        $video = new Video(['title' => 'Laravel Tutorial Video', 'url' => 'https://example.com/video']);
        $video->id = 1; // Fake ID

        $postComment = new Comment(['body' => 'Great post!']);
        $postComment->commentable_id = $post->id;
        $postComment->commentable_type = Post::class;

        $videoComment = new Comment(['body' => 'Very helpful video!']);
        $videoComment->commentable_id = $video->id;
        $videoComment->commentable_type = Video::class;

        return response()->json([
            'dependency_injection_result' => $greeting,
            'polymorphic_relations' => [
                'post' => [
                    'details' => $post,
                    'simulated_comment' => $postComment
                ],
                'video' => [
                    'details' => $video,
                    'simulated_comment' => $videoComment
                ]
            ]
        ]);
    }
}
