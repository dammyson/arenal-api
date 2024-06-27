<?php

namespace App\Http\Controllers\search;

use App\Models\Game;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class searchGameController extends Controller
{
    // 
    public function searchGame(Request $request) {
        $validated = $request->validate([
            'search-input' => 'required|string'
        ]);

        try {
           $games = Game::whereAny([
                'title',
                'type'
            ], 'LIKE', $validated['search-input']. '%');

        } catch(\Throwable $throwable) {
            
            report($throwable);
            response()->json([
                "error" => "true",
                "message" => $throwable->getMessage()
            ], 500);
        }

        response()->json([
            "error" => "false",
            "message" => $games
        ], 200);

       
    }
}
