<?php

namespace App\Services\Live;

use App\Models\Live;
use App\Services\BaseServiceInterface;
use App\Http\Requests\Live\UpdateBrandLiveRequest;

class UpdateBrandLiveService implements BaseServiceInterface{
    protected $request;
    protected $live;

    public function __construct(Live $live, UpdateBrandLiveRequest $request)
    {
        $this->request = $request;
        $this->live = $live;
    }

    public function run() {
        try {

            $this->live->update($this->request->validated());

            return $this->live;

        } catch(\Throwable $e) {
            
            throw $e;
        }      

    }
}