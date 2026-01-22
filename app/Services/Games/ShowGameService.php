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
      $game = Game::with([
          'rules',
          'campaigns.brand'
      ])->find($this->gameId);

      if (!$game) {
        throw new ModelNotFoundException("Game is not found");
      }

      $brands = $game->campaigns
        ->pluck('brand')
        ->filter()
        ->unique('id')
        ->values();
      
      return [
        "game" => $game,
        "brands" => $brands
      ];


    }
}