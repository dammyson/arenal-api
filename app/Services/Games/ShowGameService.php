<?php

namespace App\Services\Games;

use App\Models\Game;
use App\Services\BaseServiceInterface;
use App\Http\Requests\StoreGameRequest;

class ShowGameService implements BaseServiceInterface{
    protected $gameId;

    public function __construct($gameId)
    {
        $this->gameId = $gameId;
    }

    public function run() {
       return Game::where('id', $this->gameId)->with('rules')->first();

    }
}