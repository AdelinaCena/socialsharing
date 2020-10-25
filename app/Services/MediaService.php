<?php
namespace App\Services;

/**
   * 
   */
  class MediaService 
  {
  	   static function UploadFile($file) 
  	   {
            // Get filename with the extension
            $filenameWithExt = $file->getClientOriginalName();
            //Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            // Get just ext
            $extension = $file->getClientOriginalExtension();
            // Filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            // Upload Image
            $file->storeAs('public/images', $fileNameToStore);
            // full file path
            $path = "http://localhost:8000/storage/images/".$fileNameToStore;

            return $path;
  	    }

        static function getFileName($full_path)
        {
            // split url 
            $fileName = [];
            preg_match("/[^\/]+$/", $full_path, $fileName);
            $name = $fileName[0];
            
            // delete file from storage disc
            unlink(storage_path('app/public/images/'.$name));
        } 
    }  
?>