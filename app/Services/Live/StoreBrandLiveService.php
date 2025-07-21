<?php

namespace App\Services\Live;

use App\Models\Live;
use App\Models\Brand;
use App\Models\BrandDetail;
use App\Services\BaseServiceInterface;
use App\Http\Requests\Live\StoreLiveRequest;
use App\Http\Requests\User\BrandStoreRequest;

class StoreBrandLiveService implements BaseServiceInterface{
    protected $request;

    public function __construct(StoreLiveRequest $request)
    {
        $this->request = $request;
    }

    public function run() {
        try {
            $user = $this->request->user();

            $live = Live::create([
                ...$this->request->validated(),
                'user_id' => $user->id
            ]);

            return $live;

        } catch(\Throwable $e) {
            
            throw $e;
        }      

    }
}