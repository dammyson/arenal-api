<?php

namespace App\Services\Games;

use App\Models\Game;
use App\Services\BaseServiceInterface;
use App\Http\Requests\StoreGameRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

class ShowGameService implements BaseServiceInterface{
    protected $gameId;

    public function __construct($gameId)
    {
        $this->gameId = $gameId;
    }

    public function run() {
      $game = Game::where('id', $this->gameId)->with('rules')
          ->with('spinTheWheels')
          ->with('trivias')
          ->first();

      if (!$game) {
        throw new ModelNotFoundException("Game is not found");
      }
      
      return $game;


    }
}