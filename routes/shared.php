<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\PrizeController;
use App\Http\Controllers\TriviaController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\CampaignGameController;
use App\Http\Controllers\SpinTheWheelController;
use App\Http\Controllers\TriviaQuestionController;
use App\Http\Controllers\CampaignGamePlayController;
use App\Http\Controllers\CampaignGameRuleController;
use App\Http\Controllers\SpinTheWheelComponentController;
use App\Http\Controllers\CampaignGamePlayLeaderboardController;
use App\Http\Controllers\OverallCampaignGamePlayLeaderboardController;

Route::get('/',  [TriviaController::class, 'index'])->name('index');
Route::get('/brands',  [BrandController::class, 'index'])->name('index');
Route::get('brand-prizes/{brand}', [PrizeController::class, 'getBrandPrizes']);

Route::post('/upload-image',  [ImageUploadController::class, 'uploadImage'])->name('upload.image');
Route::get('user-spin-the-wheel', [SpinTheWheelController::class, 'userIndex'])->name('user.index');
Route::get('spin-the-wheel', [SpinTheWheelController::class, 'index'])->name('index');
Route::get('spin-the-wheel/{id}', [SpinTheWheelController::class, 'show'])->name('index');
Route::get('show-user-form', [SpinTheWheelComponentController::class, 'showUserForm'])->name('create.sector.segment');

Route::get('game', [GameController::class, 'index']);
Route::get('game/{game_id}/show-game', [GameController::class, 'showGame']);

Route::get('campaign', [CampaignController::class, 'index']);
Route::get('campaign/{campaign_id}', [CampaignController::class, 'showCampaign']);
Route::get('campaign-game', [CampaignGameController::class, 'indexCampaignGame']);
Route::post('campaign/{campaign_id}/campaign-game/{game_id}/link', [CampaignController::class, 'generateCampaignLink']);
Route::get('campaign/{campaign_id}/game/{game_id}/rules', [CampaignGameRuleController::class, 'showCampaignGameRules']);


Route::get('campaign/games/game-plays', [CampaignGamePlayController::class, 'index']); // not seen in UI
Route::get('campaign/{campaign_id}/games/{game_id}/show-campaign-game', [CampaignGameController::class, 'showCampaignGame']);
Route::post('campaign/{campaign_id}/games/{game_id}/campaign-game-play', [CampaignGamePlayController::class, 'storeCampaignGamePlay']);
Route::get('campaign/{campaign_id}/games/{game_id}/game-plays', [CampaignGamePlayController::class, 'show']);
Route::put('campaign/{campaign_id}/games/{game_id}/game-plays', [CampaignGamePlayController::class, 'update']);
Route::delete('campaign/{campaign_id}/games/{game_id}/game-plays', [CampaignGamePlayController::class, 'destroy']); // not seen in UI

Route::get('campaign/{campaign_id}/games/{game_id}/campaign-game-leaderboard/daily', [CampaignGamePlayLeaderboardController::class, 'gameLeaderboardDaily']);
Route::post('brand-leaderboard', [CampaignGamePlayLeaderboardController::class, 'brandLeaderboard']);
Route::get('campaign/{campaign_id}/games/{game_id}/campaign-game-leaderboard/weekly', [CampaignGamePlayLeaderboardController::class, 'gameLeaderboardWeekly']);
Route::get('campaign/{campaign_id}/games/{game_id}/campaign-game-leaderboard/monthly', [CampaignGamePlayLeaderboardController::class, 'gameLeaderboardMonthly']);
Route::get('campaign/{campaign_id}/games/{game_id}/campaign-game-leaderboard/alltime', [CampaignGamePlayLeaderboardController::class, 'gameLeaderboardAllTime']);

Route::get('general/overall-leaderboard/daily', [OverallCampaignGamePlayLeaderboardController::class, 'overallLeaderboardDaily']);
Route::get('general/overall-leaderboard/weekly', [OverallCampaignGamePlayLeaderboardController::class, 'overallLeaderboardWeekly']);
Route::get('general/overall-leaderboard/monthly', [OverallCampaignGamePlayLeaderboardController::class, 'overallLeaderboardMonthly']);
Route::get('general/overall-leaderboard/alltime', [OverallCampaignGamePlayLeaderboardController::class, 'overallLeaderboard']);

// Route::get('rules', [CampaignGameRuleController::class, 'index']);

Route::get('trivia/questions', [TriviaQuestionController::class, 'index']);
Route::post('trivia/{trivia}', [TriviaQuestionController::class, 'processAnswers']);