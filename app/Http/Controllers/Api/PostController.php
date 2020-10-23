<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\StorePostRequest;
use App\Http\Resources\PostResource;
use App\Models\Post;
use App\Models\Media;
use App\Services\MediaService;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::with(['media', 'user'])->latest()->get();
        $postsCollection = PostResource::collection($posts);

        return response()->json(['success'=> true, 'allPosts' => $postsCollection]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
        try { 

            $input = $request->only(['title', 'text']);
            $input['user_id'] = auth()->user()->id;
            
            $post = Post::create($input);
            
            if($post && $request->media) {
                
                foreach($request->media as $file) {
                   
                    $path = MediaService::UploadFile($file);

                    Media::create([
                        'url' => $path,
                        'post_id' => $post->id
                    ]);
                }
            }

            return response()->json(
                [
                    'success' => true, 
                    'post' => new PostResource($post)
                ]
            );
        
        } catch (\Exception $e) {
            \Log::error($e);

            return response()->json(
                [
                    'success' => false, 
                ]
            );
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::findOrFail($id);

        $media = $post->media()->get();

        return response()->json(
            [
                'success' => true, 
                'post' =>  new PostResource($post),
                'media' => $media 
            ]
        );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {

            $input = $request->all();
            
            $post = Post::findOrFail($id);
            $post->update($input);
            
            return response()->json(['success' => true, 'post' => new PostResource($post)]);
        } catch (\Exception $e) {
            \Log::error($e);

            return response()->json(
                [
                    'success' => false, 
                    'message' => $e->getMessage()
                ],
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
