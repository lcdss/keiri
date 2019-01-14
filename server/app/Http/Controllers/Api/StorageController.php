<?php

namespace App\Http\Controllers\Api;

use Storage;

class StorageController extends Controller
{
    public function __invoke($filePath)
    {
        if (!Storage::disk('media')->exists($filePath)) {
            return response()->json(null, 404);
        }

        $localPath = config('filesystems.disks.media.root').DIRECTORY_SEPARATOR.$filePath;

        return response()->file($localPath);
    }
}
