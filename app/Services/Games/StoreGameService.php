<?php

namespace App\Services\Games;

use App\Models\Game;
use App\Services\BaseServiceInterface;
use App\Http\Requests\StoreGameRequest;

class StoreGameService implements BaseServiceInterface{
    protected $request;

    public function __construct(StoreGameRequest $request)
    {
        $this->request = $request;
    }

    public function run() {     

        return Game::create([
            ...$this->request->validated(),
            'user_id' => $this->request->user()->id
        ]);

    }
}