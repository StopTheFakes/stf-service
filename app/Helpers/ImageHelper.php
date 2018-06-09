<?php

namespace App\Helpers;

use Illuminate\Http\UploadedFile;
use File;
use Storage;
use Image;

class ImageHelper
{
    private $image;
    private $prefix;

    public function __construct(string $prefix, UploadedFile $image)
    {
        $this->prefix = $prefix;
        $this->image = $image;
    }

    // Generates file path like aa/bb/cccccccccc
    private function formatImageName()
    {
        $hash = sha1_file($this->image->path()) . uniqid();
        $hash = str_split($hash);
        $subDir1 = join('', array_slice($hash, 0, 2));
        $subDir2 = join('', array_slice($hash, 2, 2));

        $filepath = sprintf('/uploads/%s/%s/%s', $this->prefix, $subDir1, $subDir2);
        $directory = public_path() . $filepath;
        $filename = join('', array_slice($hash, 4)) . '.' . $this->image->getClientOriginalExtension();
        return [$directory, $filename, $filepath];
    }

    // Stores file and returns path to it
    public function upload()
    {
        list($directory, $filename, $filepath) = $this->formatImageName();

        if (!File::exists($directory)) {
            File::makeDirectory($directory, 0755, true, true);
        }

        $this->image->move($directory, $filename);

        return $filepath . '/' . $filename;
    }

    public function uploadAndResize(array $sizes)
    {
        list($directory, $filename, $filepath) = $this->formatImageName();
        foreach ($sizes as $type => $size) {
            $image = Image::make($this->image->path());
            $image->resize($size[0], $size[1], function ($constraint) {
                $constraint->aspectRatio();
            });
            $newPath = preg_replace(
                '/^\/uploads\/' . $this->prefix . '\//',
                '/uploads/' . $this->prefix . '/' . $type . '/',
                $filepath);
            $fullPath = public_path() . $newPath . '/' . $filename;
            $directory = dirname($fullPath);
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true, true);
            }
            $image->save($fullPath);
        }
        return $filepath . '/' . $filename;
    }

    public static function moveTempFile(string $filepath, string $prefix, array $sizes)
    {
        $tempFile = public_path() . $filepath;
        if (!preg_match('/^\/uploads\/tmp\//', $filepath)) return false;
        foreach ($sizes as $type => $size) {
            $image = Image::make($tempFile);
            $image->resize($size[0], $size[1], function ($constraint) {
                $constraint->aspectRatio();
            });
            $newPath = preg_replace(
                '/^\/uploads\/tmp\//',
                '/uploads/' . $prefix . '/' . $type . '/',
                $filepath);
            $basePath = preg_replace(
                '/^\/uploads\/tmp\//',
                '/uploads/' . $prefix . '/',
                $filepath);
            $fullPath = public_path() . $newPath;
            $directory = dirname($fullPath);
            if (!File::exists($directory)) {
                File::makeDirectory($directory, 0755, true, true);
            }
            $image->save($fullPath);
        }
        File::delete($tempFile);
        return $basePath;
    }


    public static function saveFile(string $filepath, string $prefix, array $sizes)
    {

    }


}
