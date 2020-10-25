<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Media;

class MediaController extends Controller
{

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
