<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\UserLoginController;
use App\Http\Controllers\CompanyUserController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CampaignGameController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserRegisterController;
use App\Http\Controllers\AudienceLoginController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\AudienceRegisterController;
use App\Http\Controllers\CampaignGamePlayController;
use App\Http\Controllers\CampaignGamePlayLeaderboardController;
use App\Http\Controllers\CampaignGameRuleController;
use App\Http\Controllers\TransactionHistoryController;
use App\Http\Controllers\CampaignLeaderboardController;
use App\Http\Controllers\GeneralLeaderboardController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\FilterGameController;
use App\Http\Controllers\OverallCampaignGamePlayLeaderboardController;
use App\Http\Controllers\SearchGameController;
use App\Http\Controllers\SearchTransactionController;
use App\Http\Controllers\TriviaQuestionController;
use App\Models\CampaignGamePlay;



Route::post('users/auth/create', [UserRegisterController::class, 'userRegister']);
Route::post('users/auth/sign_in', [UserRegisterController::class, 'newRegister']);
Route::post('users/auth/login', [UserRegisterController::class, 'newLogin']);
Route::post('users/auth/verify-otp', [UserRegisterController::class, 'verifyOtp']);
Route::get('auth/google/callback', [UserRegisterController::class, 'gooogleCallback']);


Route::post('audiences/auth/register', [AudienceRegisterController::class, 'registerAudience']);
Route::post('audiences/check-audience', [AudienceRegisterController::class, 'checkAudience']);
Route::post('audiences/auth/login', [AudienceLoginController::class, 'login']);

Route::post('auth/verify-otp', [UserRegisterController::class, 'verifyOtp']);


Route::group(["middleware" => ["auth:api"]], function () {

    Route::post('users/wallets/create', [WalletController::class, 'createWallet']);

    Route::get('users/wallets/transactions/{id}', [TransactionController::class, 'show']);
     
    Route::get('users/companies', [CompanyController::class, 'index']);
    Route::post('users/companies', [CompanyController::class, 'storeCompany']);

    Route::get('users/clients', [ClientController::class, 'index']);
    Route::post('users/clients', [ClientController::class, 'storeClient']);

    Route::get('users/brands', [BrandController::class, 'index']);
    Route::post('users/brands', [BrandController::class, 'storeBrand']);
        

    Route::get('users/games', [GameController::class, 'index']);
    Route::post('users/games', [GameController::class, 'storeGame']);
    Route::get('users/games/{game_id}/show-game', [GameController::class, 'showGame']);
    Route::patch('users/games/{game_id}/update-game', [GameController::class, 'updateGame']);

        
    Route::get('users/campaigns', [CampaignController::class, 'index']);           
    Route::post('users/campaigns', [CampaignController::class, 'storeCampaign']);
    Route::get('users/campaigns/{campaign_id}', [CampaignController::class, 'showCampaign']);
            

            
    Route::post('users/campaign/{campaign_id}/campaign-game', [CampaignGameController::class, 'storeCampaignGame']);
    Route::get('users/campaign/{campaign_id}/campaign-game', [CampaignGameController::class, 'indexCampaignGame']);
    Route::get('users/campaign/{campaign_id}/campaign-game/{game_id}/link', [CampaignController::class, 'generateCampaignLink']);
    Route::post('users/campaign/{campaign_id}/game/{game_id}/rules', [CampaignGameRuleController::class, 'store']);
    Route::get('users/campaign/{campaign_id}/game/{game_id}/rules', [CampaignGameRuleController::class, 'showCampaignGameRules']);

          
    Route::get('users/rules', [CampaignGameRuleController::class, 'index']);
    Route::post('users/rules', [CampaignGameRuleController::class, 'store']);
    Route::get('users/rules/{rule_id}', [CampaignGameRuleController::class, 'showCampaignGameRules']);

    Route::get('users/logout', [LogoutController::class, 'logout']);
   
    Route::get('trivia/questions', [TriviaQuestionController::class, 'index']);
    Route::post('trivia/questions', [TriviaQuestionController::class, 'storeMultiple']);
});

// Audience routes
Route::middleware('auth:audience')->group(function () {
    Route::get('audiences/home/user-info', [ProfileController::class, 'userInfo']);
    Route::get('audiences/home/user-profile', [ProfileController::class, 'profile']);
    Route::get('audiences/home/top-three', [OverallCampaignGamePlayLeaderboardController::class, 'overallGamePlayTopThree']);
    Route::get('audiences/home/campaigns', [CampaignController::class, 'index']);
    Route::get('audiences/home/campaigns-game', [CampaignGameController::class, 'indexCampaignGame']);
    Route::get('audiences/home/favorite-games', [CampaignGameController::class, 'indexFavorite']);

    Route::get('audiences/home/campaigns/{campaign_id}/games/game-plays', [CampaignGamePlayController::class, 'index']); // not seen in UI
    Route::get('audiences/home/campaigns/{campaign_id}/games/{game_id}/show-campaign-game', [CampaignGameController::class, 'showCampaignGame']);
    Route::post('audiences/home/campaigns/{campaign_id}/games/{game_id}/campaign-game-play', [CampaignGamePlayController::class, 'storeCampaignGamePlay']);
    Route::get('audiences/home/campaigns/{campaign_id}/games/{game_id}/game-plays', [CampaignGamePlayController::class, 'show']);
    Route::put('audiences/home/campaigns/{campaign_id}/games/{game_id}/game-plays', [CampaignGamePlayController::class, 'update']);
    Route::delete('audiences/home/campaigns/{campaign_id}/games/{game_id}/game-plays', [CampaignGamePlayController::class, 'destroy']); // not seen in UI

    Route::get('audiences/home/campaigns/{campaign_id}/games/{game_id}/campaign-game-leaderboard/daily', [CampaignGamePlayLeaderboardController::class, 'gameLeaderboardDaily']);
    Route::get('audiences/home/campaigns/{campaign_id}/games/{game_id}/campaign-game-leaderboard/weekly', [CampaignGamePlayLeaderboardController::class, 'gameLeaderboardWeekly']);
    Route::get('audiences/home/campaigns/{campaign_id}/games/{game_id}/campaign-game-leaderboard/monthly', [CampaignGamePlayLeaderboardController::class, 'gameLeaderboardMonthly']);
    Route::get('audiences/home/campaigns/{campaign_id}/games/{game_id}/campaign-game-leaderboard/alltime', [CampaignGamePlayLeaderboardController::class, 'gameLeaderboardAllTime']);
                    
    Route::get('audiences/home/gamez/{game_id}', [GameController::class, 'showGame']);
    Route::patch('audiences/home/gamez/{game_id}/favorite', [GameController::class, 'toogleFavorite']);

    Route::get('audiences/play-game',[CampaignController::class, 'goToCampaignGame'])->name('play.game'); 


    Route::post('audiences/gameboard/search-game', [SearchGameController::class, 'searchGame']);
    Route::get('audiences/gameboard/user-info', [ProfileController::class, 'userInfo']);
    Route::post('audiences/gameboard/category', [FilterGameController::class, 'filter']);
    Route::get('audiences/gameboard', [CampaignGameController::class, 'indexCampaignGame']);
    Route::patch('audiences/gameboard/{game_id}/favorite', [GameController::class, 'toogleFavorite']);

    Route::get('audiences/general/overall-leaderboard/daily', [OverallCampaignGamePlayLeaderboardController::class, 'overallLeaderboardDaily']);
    Route::get('audiences/general/overall-leaderboard/weekly', [OverallCampaignGamePlayLeaderboardController::class, 'overallLeaderboardWeekly']);
    Route::get('audiences/general/overall-leaderboard/monthly', [OverallCampaignGamePlayLeaderboardController::class, 'overallLeaderboardMonthly']);
    Route::get('audiences/general/overall-leaderboard/alltime', [OverallCampaignGamePlayLeaderboardController::class, 'overallLeaderboard']);
                
    Route::get('audiences/campaign/{campaign_id}/game/{game_id}/rules', [CampaignGameRuleController::class, 'showCampaignGameRules']);
    Route::get('audiences/campaign/{campaign_id}/game/campaign-game', [CampaignGameController::class, 'indexCampaignGame']);

    Route::get("audiences/account-settings/profile", [ProfileController::class, "profile"]);
    Route::post("audiences/account-settings/profile/edit", [ProfileController::class, "editProfile"]);           
    Route::patch('audiences/account-settings/security/change-password', [ChangePasswordController::class, 'changePassword']); 

    Route::get('audiences/wallet/fund-wallet', [WalletController::class, 'showAccountNumber']);
    Route::post('audiences/wallet/fund-wallet', [WalletController::class, 'fundWallet']);
    Route::post('audiences/wallet/transfer-funds', [WalletController::class, 'transferFund']);
    Route::post('audiences/wallet/create', [WalletController::class, 'createWallet']);
    Route::get('audiences/wallet/{wallet_id}/wallet-balance', [WalletController::class, 'getWalletBalance']);
    Route::post('audiences/wallet/{wallet_id}/transaction', [TransactionController::class, 'storeTransaction']);
    Route::post('audiences/wallet/{wallet_id}/transaction-history', [TransactionHistoryController::class, 'storeTxHistory']);
    Route::get('audiences/wallet/{wallet_id}/transaction-history', [TransactionHistoryController::class, 'getTxHistory']);
    Route::post('audiences/wallet/{wallet_id}/search-transaction', [SearchTransactionController::class, 'searchTransactionHistory']);
    Route::post('audiences/wallet/{wallet_id}/filter-transaction', [SearchTransactionController::class, 'filterTransactionHistory']);
        

    Route::get('audiences/logout', [LogoutController::class, 'logout']); 
});