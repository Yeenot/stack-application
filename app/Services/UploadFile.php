<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UploadFile
{
    /**
     * Upload file
     *
     * @param \Illuminate\Http\File $file
     * @return array
     */
    public function execute($file)
    {
        $extension = $file->getClientOriginalExtension();
        $originalName = $file->getClientOriginalName();
        $name = Str::random(40) . "." . $extension;
        $path = Storage::disk('public')->putFileAs("stacks", $file, $name);

        return [
            'extension' => $extension,
            'originalName' => $originalName,
            'name' => $name,
            'path' => $path,
        ];
    }
}