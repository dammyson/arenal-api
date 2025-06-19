<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UploadImageRequest;
use App\Services\Images\UploadImageService;

class ImageUploadController extends BaseController{
    //
    public function uploadImage(UploadImageRequest $request) {
        try {
            $data = (new UploadImageService($request))->run();
        } catch (\Exception $e){

            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }         
        return $this->sendResponse($data, "image upload succcessfully");
    
       
    }
}
