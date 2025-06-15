<?php

namespace App\Services\Images;

use App\Http\Requests\UploadImageRequest;
use Illuminate\Http\Request;
use App\Services\BaseServiceInterface;
use Illuminate\Support\Facades\Storage;

class UploadImageService implements BaseServiceInterface{
    protected $request;

    public function __construct(UploadImageRequest $request)
    {
        $this->request = $request;
    }

    public function run() {
        $file = $this->request->file('image');
        $path = Storage::disk('cloudinary')->putFile('uploads', $file);
        $url = Storage::disk('cloudinary')->url($path);
      
        return $url;
    }
}