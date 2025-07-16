<?php

namespace App\Services\Prize;

use App\Models\BrandDetail;
use App\Services\BaseServiceInterface;
use App\Http\Requests\User\BrandStoreRequest;
use App\Http\Requests\User\PrizeStoreRequest;
use App\Models\Prize;

class StorePrizeService implements BaseServiceInterface{
    protected $request;

    public function __construct(PrizeStoreRequest $request)
    {
        $this->request = $request;
    }

    public function run() {
        $user = $this->request->user();

        $prize = Prize::create([
            ...$this->request->validated()
        ]);

        return $prize;

    }
}