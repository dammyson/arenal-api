<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\TriviaController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\CampaignGameController;
use App\Http\Controllers\SpinTheWheelController;
use App\Http\Controllers\TriviaQuestionController;
use App\Http\Controllers\CampaignGamePlayController;
use App\Http\Controllers\CampaignGameRuleController;
use App\Http\Controllers\CampaignGamePlayLeaderboardController;

Route::get('/',  [TriviaController::class, 'index'])->name('index');
Route::post('/upload-image',  [ImageUploadController::class, 'uploadImage'])->name('upload.image');
 Route::get('user-spin-the-wheel', [SpinTheWheelController::class, 'userIndex'])->name('user.index');
Route::get('spin-the-wheel', [SpinTheWheelController::class, 'index'])->name('index');
Route::get('spin-the-wheel/{id}', [SpinTheWheelController::class, 'show'])->name('index');

Route::get('games', [GameController::class, 'index']);
Route::post('games', [GameController::class, 'storeGame']);
Route::get('games/{game_id}/show-game', [GameController::class, 'showGame']);
Route::patch('games/{game_id}/update-game', [GameController::class, 'updateGame']);
Route::post('games/{game_id}/upload-images',  [GameController::class, 'uploadImages']);


Route::get('campaign', [CampaignController::class, 'index']);
Route::get('campaign/{campaign_id}', [CampaignController::class, 'showCampaign']);
Route::post('campaign/{campaign_id}/campaign-game', [CampaignGameController::class, 'storeCampaignGame']);
Route::get('campaign/{campaign_id}/campaign-game', [CampaignGameController::class, 'indexCampaignGame']);
Route::get('campaign/{campaign_id}/campaign-game/{game_id}/link', [CampaignController::class, 'generateCampaignLink']);
Route::post('campaign/{campaign_id}/game/{game_id}/rules', [CampaignGameRuleController::class, 'store']);
Route::get('campaign/{campaign_id}/game/{game_id}/rules', [CampaignGameRuleController::class, 'showCampaignGameRules']);


Route::get('campaign/{campaign_id}/games/game-plays', [CampaignGamePlayController::class, 'index']); // not seen in UI
Route::get('campaign/{campaign_id}/games/{game_id}/show-campaign-game', [CampaignGameController::class, 'showCampaignGame']);
Route::post('campaign/{campaign_id}/games/{game_id}/campaign-game-play', [CampaignGamePlayController::class, 'storeCampaignGamePlay']);
Route::get('campaign/{campaign_id}/games/{game_id}/game-plays', [CampaignGamePlayController::class, 'show']);
Route::put('campaign/{campaign_id}/games/{game_id}/game-plays', [CampaignGamePlayController::class, 'update']);
Route::delete('campaign/{campaign_id}/games/{game_id}/game-plays', [CampaignGamePlayController::class, 'destroy']); // not seen in UI

Route::get('campaign/{campaign_id}/games/{game_id}/campaign-game-leaderboard/daily', [CampaignGamePlayLeaderboardController::class, 'gameLeaderboardDaily']);
Route::get('campaign/{campaign_id}/games/{game_id}/campaign-game-leaderboard/weekly', [CampaignGamePlayLeaderboardController::class, 'gameLeaderboardWeekly']);
Route::get('campaign/{campaign_id}/games/{game_id}/campaign-game-leaderboard/monthly', [CampaignGamePlayLeaderboardController::class, 'gameLeaderboardMonthly']);
Route::get('campaign/{campaign_id}/games/{game_id}/campaign-game-leaderboard/alltime', [CampaignGamePlayLeaderboardController::class, 'gameLeaderboardAllTime']);



Route::get('rules', [CampaignGameRuleController::class, 'index']);
Route::post('rules', [CampaignGameRuleController::class, 'store']);
Route::get('rules/{rule_id}', [CampaignGameRuleController::class, 'showCampaignGameRules']);

Route::get('trivia/questions', [TriviaQuestionController::class, 'index']);
