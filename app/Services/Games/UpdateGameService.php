<?php

namespace App\Services\Games;

use App\Http\Requests\Game\UpdateGameRequest;
use App\Models\Game;
use App\Services\BaseServiceInterface;
use App\Http\Requests\StoreGameRequest;

class UpdateGameService implements BaseServiceInterface{
    protected $request;
    protected $gameId;

    public function __construct(UpdateGameRequest $request, $gameId)
    {
        $this->request = $request;
        $this->gameId = $gameId;
    }

    public function run() {
        
        $game = Game::find($this->gameId);

        if (!$game) {
            return response()->json(['error' => 'false', 'message' => 'Game not found'], 404);
        }

        return $game->update($this->request->validated);

    }
}