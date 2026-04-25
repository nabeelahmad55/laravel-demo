<?php

namespace App\Http\Controllers;

use App\Events\PostCreated;
use App\Jobs\ProcessVideoUpload;
use App\Models\Post;
use App\Models\Video;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Pipeline;

class ContentController extends Controller
{
    public function testPipeline()
    {
        // 1. The data we want to process
        $postContent = 'this IS SOME rAW text from thE uSEr.';

        // 2. An array of "pipes" (closures or classes) to process the data in order
        $pipes = [
            function ($content, $next) {
                // Pipe 1: Convert to lowercase
                $content = strtolower($content);
                return $next($content);
            },
            function ($content, $next) {
                // Pipe 2: Capitalize first letter of each word
                $content = ucwords($content);
                return $next($content);
            },
            function ($content, $next) {
                // Pipe 3: Append a period if missing
                if (!str_ends_with($content, '.')) {
                    $content .= '.';
                }
                return $next($content);
            }
        ];

        // 3. Send the data through the pipeline
        $processedContent = Pipeline::send($postContent)
            ->through($pipes)
            ->then(function ($content) {
                // This runs at the very end of the pipeline
                return $content;
            });

        return response()->json([
            'original' => $postContent,
            'processed' => $processedContent,
            'message' => 'Data passed through the pipeline successfully!'
        ]);
    }

    public function storePost()
    {
        // 1. Create a dummy post
        $post = Post::create([
            'title' => 'My first event-driven post',
            'body' => 'This is the content of the post.'
        ]);

        // 2. Dispatch the event. 
        // We do this instead of putting email sending logic here!
        PostCreated::dispatch($post);

        return response()->json([
            'message' => 'Post created! Event dispatched in the background.'
        ]);
    }

    public function storeVideo()
    {
        // 1. Create a dummy video
        $video = Video::create([
            'title' => 'My awesome vacation video',
            'url' => 'https://example.com/vacation.mp4'
        ]);

        // 2. Dispatch the job to the queue
        // This stops the user from having to wait for video processing.
        ProcessVideoUpload::dispatch($video);

        return response()->json([
            'message' => 'Video uploaded! Processing job dispatched to the queue.'
        ]);
    }
}
