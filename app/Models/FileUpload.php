<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FileUpload extends Model
{
    use HasFactory;

    /**
     * Todo: Uploading Files
     *
     * ? This method is used to intiate the image uploading
     * ? This will be used in the controller
     * ? When passing images, also pass upload path, allow year/date folder to be created or not, lastest randomize image file name
     * ? This will return the image upload path
     * ? State if is private upload or not (Bolean)
     * ? If is private, upload will be done in the storage folder NB: if the file will be accessed via http, it will not be accessible
     * ? Download will be possible (good for pdfs & receipts)
     *
     * @param array $images
     * @param string $upload_path
     * @param boolean $year_folder
     * @param boolean $randomize_file_name
     * @param boolean $private_upload = false
     */
    public static function upload(array $files, string $upload_path = null, bool $year_folder = true, bool $randomize_file_name = true, bool $private_upload = false)
    {
        // Uploaded
        $uploaded = Self::uploadFile($files, $upload_path, $year_folder, $randomize_file_name, $private_upload);

        //  and if is null return null
        if (count($uploaded) == 0) {
            return null;
        }

        // Reset array keys,
        $uploaded = array_values($uploaded);

        // Return, check if there is only one image
        return (count($uploaded) == 1) ? $uploaded[0] : $uploaded;
    }

    /**
     * Todo: Images File
     *
     * ? This method is used to intiate the image uploading
     * ? This will be used in the controller
     * ? When passing files, also pass upload path, allow year/date folder to be created or not, lastest randomize file name
     * ? This will return the file upload path
     * ? State if is private upload or not (Bolean)
     * ? If is private, upload will be done in the storage folder NB: if the file will be accessed via http, it will not be accessible
     * ? Download will be possible (good for pdfs & receipts)
     *
     * @param array $files
     * @param string $upload_path
     * @param boolean $year_folder
     * @param boolean $randomize_file_name
     * @param boolean $private_upload = false
     * @param string $convert = null
     *
     * @return array
     */
    public static function uploadFile(array $files, string $upload_path = null, bool $year_folder = true, bool $randomize_file_name = true, bool $private_upload = false)
    {

        // Year/Month/Date
        $auto_folder = date('Y') . '/' . date('m') . '/' . date('d');

        // Uploaded
        $uploaded = [];

        // Generate a random name for each uploaded file
        foreach ($files as $file) {
            $file_name = ($randomize_file_name) ? uniqid() . '.' . $file->getClientOriginalExtension() : $file->getClientOriginalName();

            // Create the directory if it doesn't exist
            $directory = (!is_null($upload_path)) ? 'public/media-private/' . $upload_path : 'public/media-private';

            // Year/Month/Date
            if ($year_folder) {
                $directory = $directory . '/' . $auto_folder;
            }

            // Generate Folder if it doesn't exist
            if (!Storage::exists($directory)) {
                Storage::makeDirectory($directory);
            }

            // Call to check if the file exists if it does, give it a new name
            $image_path_name = self::existImage($directory . '/' . $file_name, true); // True = yes give new name if current exist
            // Get the value after the last slash
            $new_file_name = substr($image_path_name, strrpos($image_path_name, '/') + 1);
            // Upload the file to the directory
            $file->storeAs($directory, $new_file_name);

            // Get the URL path for the uploaded file
            $url_path = Storage::url($directory . '/' . $new_file_name);

            // For Public Upload
            if ($private_upload == false) {
                // Create the directory if it doesn't exist
                $public_directory = (!is_null($upload_path)) ? 'media/' . $upload_path : 'media';

                // Year/Month/Date
                if ($year_folder) {
                    $public_directory = $public_directory . '/' . $auto_folder;
                }

                // Generate Folder if it doesn't exist
                if (!File::exists($public_directory)) {
                    File::makeDirectory($public_directory, 0755, true);
                }

                // Call to check if the image exists
                $image_path_name = self::existImage("$public_directory/$new_file_name", true); // True = yes give new name if current exist
                // Get the value after the last slash
                $public_file_name = substr($image_path_name, strrpos($image_path_name, '/') + 1);
                // Move To Public Directory
                $file->move($public_directory, $public_file_name);

                // Delete from the storage directory
                Storage::delete($directory . '/' . $new_file_name);

                // Get the URL path for the uploaded file
                $url_path = $public_directory . '/' . $public_file_name;
            }

            // Images
            $uploaded[] = $url_path;
        }

        // Reset array keys,
        $uploaded = array_values($uploaded);

        //  and if is empty return blank array
        if (count($uploaded) == 0) {
            return [];
        }

        // Return array
        return $uploaded;
    }

    /**
     * Todo: Method to check if Image exist
     * ? Pass the image path
     * ? Allow generating new image name if current exist
     * ? Numerate the image name start from 1x
     *
     * @param string $image_path
     * @param boolean $generate_new_name
     * @param integer $numeration
     *
     */
    public static function existImage(string $image_path, $generate_new_name = false, $numeration = 1)
    {

        // Exist
        $exist = false;

        // Check if string start with storage/
        if (Str::startsWith($image_path, 'storage/')) {
            $image_path = Str::replaceFirst('storage/', '/storage/', $image_path);
        }

        // If the first path has storage/ remove it, if it's later in the path, keep it
        if (Str::startsWith($image_path, '/storage/')) {
            // If there is /storage/public/ in the path, remove it
            if (Str::startsWith($image_path, '/storage/public/')) {
                $image_path = Str::replaceFirst('/storage/public/', 'public/', $image_path);
            } else {
                $image_path = Str::replaceFirst('/storage/', 'public/', $image_path);
            }
        }

        // Exist in public
        if (File::exists($image_path)) {
            $exist = true;
        } elseif (Storage::exists($image_path)) {
            $exist = true;
        }

        // Check if the image exist
        if ($exist == true) {
            // Generate new name
            if ($generate_new_name) {
                // Get the Current image extension
                $extension = pathinfo($image_path, PATHINFO_EXTENSION);
                // Get the image name
                $image_name = pathinfo($image_path, PATHINFO_FILENAME);
                // Get the image directory
                $image_directory = pathinfo($image_path, PATHINFO_DIRNAME);
                // Check if the image name has _thumb
                if (strpos($image_name, '_thumb') !== false) {
                    // Replace everything after _thumb but keep the _thumb
                    $image_name = explode('_thumb', $image_name)[0];
                    // Add the _thumb back
                    $image_name = $image_name . '_thumb';
                }

                // Create the new image path
                $image_path = $image_directory . '/' . $image_name . '_' . $numeration . 'x.' . $extension;
                // Call the method again
                return self::existImage($image_path, true, $numeration + 1);
                // Return the image path
            }
            // Return true
            return true;
        }
        return ($generate_new_name) ? $image_path : false;
    }
}
