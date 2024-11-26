<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ImageUploadService
{
    /**
     * Upload an image and create a thumbnail.
     *
     * @param  \Illuminate\Http\UploadedFile $file
     * @param  string $path
     * @param  array $dimensions
     * @return array
     */
    public function uploadImageWithThumbnail($uploadedFile, $path = 'players', $h = 80, $w = 80)
    {      
        // Players Image Direcotry
        $playerImagePath = storage_path("app/public/{$path}/");

        // Players Image Direcotry
        $thumbImagePath = storage_path("app/public/{$path}/thumbs/");     

        // Check if the folder exists, if not, create it
        if (!File::exists($playerImagePath)) {
            File::makeDirectory($playerImagePath, 0777, true); // true to create subdirectories
        }
        
        // Check if the folder exists, if not, create it
        if (!File::exists($thumbImagePath)) {
            File::makeDirectory($thumbImagePath, 0777, true); // true to create subdirectories
        } 

        // Define the custom file name
        $filename = Str::random(6) . '_' .time() . '.jpg';

        /** Simple upload the file */
        //$imagePath = $uploadedFile->store('players', 'public');

         // Convert the image to JPG and resize it (optional)
        $largeImage = Image::make($uploadedFile->getPathname())->encode('jpg', 90); // Convert to JPG with 90% quality


         // Save the image to the specified directory
        $largeImage->save($playerImagePath."".$filename);

        // Define the path where the file will be stored
        //$path = $file->storeAs('uploads', $customFileName, 'public');
        //$baseFileName = basename($imagePath);                  

        // Resize and convert the image to JPG
        $thumbImage = Image::make($uploadedFile->getPathname())
        ->resize($h*2, $w*2, function ($constraint) {
            $constraint->aspectRatio(); // Maintain aspect ratio
            $constraint->upsize();     // Prevent upscaling
        })
        ->encode('jpg', 80); // Encode to JPG with 80% quality

        // Optionally crop the image to exactly 80x80
        $thumbImage->crop($h, $w);

        // Save the image to the specified path
        $thumbImage->save($thumbImagePath.$filename);

        return $filename;
    }

    /**
     * Delete image and thumbnail.
     *
     * @param  string $image
     * @param  string $path
     * @return bool/void
     */
    function deleteSavedFile($image = '', $path = 'players'){

        if($image == ''){
            return false;
        }
        
        //Construct the image base path
        $imagePath = "app\public".DIRECTORY_SEPARATOR.$path.DIRECTORY_SEPARATOR;

        // Construct the absolute path to the file
        /**Image Delete */
        $absolutePath = storage_path($imagePath.$image);
        $this->deleteFile($absolutePath);
        
        /**Thumbnail Delete */
        // Construct the absolute path to the file
        $absolutePath = storage_path($imagePath . "thumbs".DIRECTORY_SEPARATOR.$image);
        $this->deleteFile($absolutePath);
    }

    /**
     * Delete image and thumbnail.
     *
     * @param  string $absolutePath
     * @return void
     */
    function deleteFile($absolutePath = ''){
        // Check if the file exists and delete it
        if (File::exists($absolutePath)) {
            File::delete($absolutePath);
            \Log::info("File deleted: " . $absolutePath);
        } else {
            \Log::info("File not found: " . $absolutePath);
        }
    }

    function deleteMainFile($image = '', $path = ''){
        //Construct the image base path
        $imagePath = "app\public".DIRECTORY_SEPARATOR.$path.DIRECTORY_SEPARATOR;

        $absolutePath = storage_path($imagePath.$image);
        $this->deleteFile($absolutePath);
    }

    function deleteThumbFile($image = '', $path = ''){
        //Construct the image base path
        $imagePath = "app\public".DIRECTORY_SEPARATOR.$path.DIRECTORY_SEPARATOR;        

        $absolutePath = storage_path($imagePath . "thumbs".DIRECTORY_SEPARATOR.$image);
        $this->deleteFile($absolutePath);
    }
}
