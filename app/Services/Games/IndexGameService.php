<?php

namespace App\Services\Games;

use App\Models\Game;
use App\Services\BaseServiceInterface;
use App\Http\Requests\StoreGameRequest;

class IndexGameService implements BaseServiceInterface{

    public function __construct()
    {
        
    }

    public function run() {
        
       return Game::get();

    }
}