<?php

namespace App\Services\Games;

use App\Http\Requests\Game\UpdateGameRequest;
use App\Models\Game;
use App\Services\BaseServiceInterface;
use App\Http\Requests\StoreGameRequest;

class UploadGameImageService implements BaseServiceInterface{
    protected $game;
    protected $url;

    public function __construct(Game $game, $url)
    {
        $this->game = $game;
        $this->url = $url;
    }

    public function run() {
        
       

       $this->game->image_url = $this->url;
       $this->game->save();
       return $this->game;

    }
}