<?php
namespace App\Models;

use Spatie\MediaLibrary\MediaCollections\Models\Media as BaseMedia;

class Media extends BaseMedia
{
    public function getUrl($conversionName = ''): string
    {
        $path = parent::getUrl($conversionName);

        // Check if it's stored on the FTP disk and prepend the custom URL
        if ($this->disk === 'media_ftp') {
            return env('FTP_BASE_URL')  . ltrim($path, '/');
        }

        return $path;
    }
}
