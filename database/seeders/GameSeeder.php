<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Game;

class GameSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user =  User::factory()->create([
            'first_name' => 'Game',
            'last_name' => 'User',
            'email' => 'games@example.com',
        ]);

        $games = [
            [
                'id' => Str::uuid(),
                'name' => 'Spin The Wheel',
                'type' => 'gamble',
                'image_url' => 'https://example.com/chess.png',
                'user_id' => $user->id,
                'price' => 0.00,
            ],
            [
                'id' => Str::uuid(),
                'name' => 'Tic Tac Toe',
                'type' => 'Board',
                'image_url' => 'https://example.com/chess.png',
                'user_id' => $user->id,
                'price' => 0.00,
            ]
        ];

        foreach ($games as $game) {
            Game::create($game);
        }
    }
}
