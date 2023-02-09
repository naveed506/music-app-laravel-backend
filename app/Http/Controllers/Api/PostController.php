<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\PostRequest;
use App\Models\Post;
use App\Services\ImageService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $postsPerPage = 1;
            $post = Post::with('user')
                ->orderBy('updated_at', 'desc')
                ->simplePaginate($postsPerPage);
            $pageCount = count(Post::all()) / $postsPerPage;

            return response()->json([
                'paginate' => $post,
                'page_count' => ceil($pageCount)
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong in PostController.index',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        try {
            if ($request->hasFile('image') === false) {
                return response()->json(['error' => 'There is no image to upload.'], 400);
            }

            $post = new Post;

            (new ImageService)->updateImage($post, $request, '/images/posts/', 'store');

            $post->title = $request->get('title');
            $post->location = $request->get('location');
            $post->description = $request->get('description');

            $post->save();

            return response()->json('New post created', 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong in PostController.store',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        try {
            $post = Post::with('user')->findOrFail($id);

            return response()->json($post, 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong in PostController.show',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        try {
            $post = Post::findOrFail($id);

            if ($request->hasFile('image')) {
                (new ImageService)->updateImage($post, $request, '/images/posts/', 'update');
            }

            $post->title = $request->get('title');
            $post->location = $request->get('location');
            $post->description = $request->get('description');

            $post->save();

            return response()->json('Post with id ' . $id . ' was updated!', 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong in PostController.update',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        try {
            $post = Post::findOrFail($id);

            if (!empty($post->image)) {
                $currentImage = public_path() . '/images/posts/' . $post->image;

                if (file_exists($currentImage)) {
                    unlink($currentImage);
                }
            }

            $post->delete();

            return response()->json('Post deleted', 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Something went wrong in PostController.destroy',
                'error' => $e->getMessage()
            ], 400);
        }
    }
}
