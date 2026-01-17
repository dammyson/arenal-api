<?php

use App\Http\Controllers\AudienceRegisterController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\LiveController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\PrizeController;
use App\Http\Controllers\TriviaController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\ImageUploadController;
use App\Http\Controllers\CampaignGameController;
use App\Http\Controllers\SpinTheWheelController;
use App\Http\Controllers\TriviaQuestionController;
use App\Http\Controllers\CampaignGamePlayController;
use App\Http\Controllers\CampaignGameRuleController;
use App\Http\Controllers\SpinTheWheelComponentController;
use App\Http\Controllers\BrandAudienceTransactionController;
use App\Http\Controllers\CampaignGamePlayLeaderboardController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\PlayGameController;

Route::post('wallets/create', [WalletController::class, 'createWallet']);
Route::post('audience-wallets/create', [WalletController::class, 'createAudienceWallet']);
Route::post('wallet/deduct-fee', [WalletController::class, 'deductFee']);
Route::get('/brands',  [BrandController::class, 'index'])->name('index');
Route::get('brand-leaderboard/{brand}', [CampaignGamePlayLeaderboardController::class, 'brandLeaderboard']);
Route::get('arena-leaderboard', [CampaignGamePlayLeaderboardController::class, 'ArenaLeaderboard']);
Route::get('brand/{brand}/live', [LiveController::class, 'viewBrandLive']);
Route::get('list-referrals', [AudienceRegisterController::class, 'listReferrals']);
Route::put('redeem-referrals', [AudienceRegisterController::class, 'redeemReferrals']);
Route::post('faq', [FaqController::class, 'createFaq']);
Route::get('faq', [FaqController::class, 'getFaq']);
Route::get('spin-the-wheel/{spinTheWheel}/details', [SpinTheWheelController::class, 'getSpinTheWheelDetails']);
Route::get('spin-the-wheel-participation-details/{spinTheWheel}/trial-check', [SpinTheWheelController::class, 'trialCheck']);

Route::get('campaign-category', [CampaignController::class, 'getCampaignCategory']); 

Route::get('arena/prizes', [PrizeController::class, 'getArenaPrizes']);
Route::get('get-prizes', [PrizeController::class, 'getPrizes']);
Route::get('select-prize/{prize}', [PrizeController::class, 'selectPrize']);
Route::post('arena-audience/prizes', [PrizeController::class, 'storeArenaAudiencesPrizes']);
Route::post('spin-the-wheel/{spinTheWheel}/spin', [PrizeController::class, 'playSpinTheWheel']);
Route::get('arena-reward', [PrizeController::class, 'getArenaReward']);
Route::put('redeem/arena-reward/{reward}', [PrizeController::class, 'redeemArenaReward']);

// Route::get('brand-branch/{brand}/live', [LiveController::class, 'viewBranchLive']);

Route::post('join-live', [LiveController::class, 'joinLive']);
Route::get('brand/{brand}/live-history', [LiveController::class, 'liveHistory']);
Route::get('transaction-history', [BrandAudienceTransactionController::class, 'audienceTransactionHistory']);


Route::get('campaign-card', [CampaignController::class, 'getCampaignCards']);   
Route::get('brand/{brand}/get-profile', [BrandController::class, 'getBrandProfile']);
Route::get('/get-profile', [BrandController::class, 'getProfile']);
Route::get('arena-profile', [BrandController::class, 'arenaProfile']);
Route::post('arena-demography', [BrandController::class, 'storeAudienceDemo']);
Route::get('arena-demography', [BrandController::class, 'getAudienceDemo']);
Route::get('brand/{brand}/prizes', [BrandController::class, 'brandPrizes']);
Route::get('brand/{brand}/achievements', [PrizeController::class, 'achievements']);
Route::get('arena/achievements', [PrizeController::class, 'arenaAchievements']);
// Route::get('brand/{brand}/user-prizes', [PrizeController::class, 'audienceBrandPrize']);
Route::post('brand-audience/delivery/{brandAudienceReward}', [PrizeController::class, 'audienceBrandPrizeDelivery']);
Route::get('brand-audience/delivery/{audiencePrizeDelivery}', [PrizeController::class, 'getAudienceBrandPrizeDelivery']);
Route::get('brand-prizes/{brand}', [PrizeController::class, 'getBrandPrizes']);
Route::get('brand-badges/{brand}', [PrizeController::class, 'getBrandBadges']);
Route::get('arena-badges', [PrizeController::class, 'getBrandBadges']);
Route::put('brand-reward/{brandAudienceReward}', [PrizeController::class, 'redeemUserPrize']);
Route::get('redeem-history/brand/{brand}', [PrizeController::class, 'redeemHistory']);
Route::get('brand-badges/{brand}/user-badges', [PrizeController::class, 'getBrandAudienceBadges']);
Route::get('wallet/wallet-balance', [WalletController::class, 'getWalletBalance']);
Route::get('brand/{brand}/point-balance', [PrizeController::class, 'getAudiencePointBalance']);


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

Route::get('campaign/{campaign_id}/games/{game_id}/show-campaign-game', [CampaignGameController::class, 'showCampaignGame']);
Route::get('campaign/{campaign_id}/games/{game_id}/game-plays', [CampaignGamePlayController::class, 'show']);


Route::get('trivia/brand/{brand}', [TriviaController::class, 'index']);
Route::get('trivia/show-trivia/{trivia}', [TriviaController::class, 'show']);
Route::get('trivia/questions', [TriviaQuestionController::class, 'index']);
Route::get('trivia/{trivia}/questions', [TriviaQuestionController::class, 'show']);
Route::post('trivia/{trivia}', [TriviaQuestionController::class, 'processAnswers']);
Route::post('arena/trivia/{trivia}', [TriviaQuestionController::class, 'processArenaTriviaAnswers']);
Route::post('trivia-words/{trivia}', [TriviaQuestionController::class, 'wordTrivia']);
Route::post('recall-and-match/play/{recallMatch}', [PlayGameController::class, 'playRecallAndMatch']);

