<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Services\MediaService;
use App\Models\Media;
use App\Models\Post;

class MediaController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $post = Post::findOrFail($request->post_id);
        
        try {
            foreach($request->media as $file) {
                   
                $path = MediaService::UploadFile($file);

                Media::create([
                    'url' => $path,
                    'post_id' => $post->id
                ]);
            }

            return response()->json([
                "success" => true,
                "message" => "Files created successfully!"
            ]);

        } catch(\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => "Couldn`t create file. Please try again later!"
            ]);
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
        // find  file of media with id $id
        $media = Media::findOrFail($id);
        
        try {
            // split url 
            $fileName = [];
            preg_match("/[^\/]+$/", $media->url, $fileName);
            $name = $fileName[0];
            
            // delete file from database 
            $media->delete();

                // delete file from storage disc
                unlink(storage_path('app/public/images/'.$name));

                return response()->json([
                    "success" => true, 
                    "message" => "File deleted successfully!"
                ]);
        } catch(\Exception $e) {
            return response()->json([
                    "success" => false, 
                    "message" => "There is a problem try again later."
                ]);
        }
        
    }
}
