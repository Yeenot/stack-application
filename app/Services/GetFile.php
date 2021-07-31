<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class GetFile
{
    /**
     * Get file by path
     *
     * @return array
     */
    public function execute($url)
    {
        $path_parts = pathinfo($url);

        $newPath = $path_parts['dirname'] . '/tmp-files/';
        if(!is_dir ($newPath)){

            mkdir($newPath, 0777);
        }

        $newUrl = $newPath . $path_parts['basename'];
        copy($url, $newUrl);
        $imgInfo = getimagesize($newUrl);

        $file = new UploadedFile(
            $newUrl,
            $path_parts['basename'],
            $imgInfo['mime'],
            filesize($url),
            TRUE,
        );

        return $file;
    }

}