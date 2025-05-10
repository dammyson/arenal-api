<?php

namespace App\Services\Games;

use App\Models\Game;
use App\Services\BaseServiceInterface;
use App\Http\Requests\StoreGameRequest;

class ToogleFavoriteGame implements BaseServiceInterface{
    protected $gameId;

    public function __construct($gameId)
    {
        $this->gameId = $gameId;
    }

    public function run() {
        $game = Game::find($this->gameId);
        $game->is_favorite = !$game->is_favorite;
        $game->save();
        return $game;
    }
}