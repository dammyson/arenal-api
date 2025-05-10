<?php

namespace App\Services\Client;

use App\Models\Client;
use App\Services\BaseServiceInterface;

class IndexClientService implements BaseServiceInterface{
   
    public function __construct()
    {
    }

    public function run() {
        return Client::all();

    }
}