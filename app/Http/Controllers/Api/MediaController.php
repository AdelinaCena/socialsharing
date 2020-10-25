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
        
        // split url 
        $fileName = [];
        preg_match("/[^\/]+$/", $media->url, $fileName);
        $name = $fileName[0];
        
        // delete file from database 
        $media->delete();

        // delete file from storage disc
        unlink(storage_path('app/public/images/'.$name));
        

        return "deleted";
    }
}
