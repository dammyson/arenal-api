<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CampaignGameController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserRegisterController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\AudienceRegisterController;
use App\Http\Controllers\CampaignGameRuleController;
use App\Http\Controllers\TransactionHistoryController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\FilterGameController;
use App\Http\Controllers\OverallCampaignGamePlayLeaderboardController;
use App\Http\Controllers\SearchGameController;
use App\Http\Controllers\SearchTransactionController;


Route::post('users/auth/register', [UserRegisterController::class, 'userRegister']);
Route::post('users/auth/login', [UserRegisterController::class, 'login']);
Route::post('users/auth/verify-otp', [UserRegisterController::class, 'verifyOtp']);

Route::post('audiences/auth/register', [AudienceRegisterController::class, 'registerAudience']);
Route::post('audiences/check-audience', [AudienceRegisterController::class, 'checkAudience']);
Route::post('audiences/auth/login', [AudienceRegisterController::class, 'login']);
Route::post('audiences/auth/forget-password', [AudienceRegisterController::class, 'forgotPasswordPost']);
Route::post('audiences/auth/change-password', [AudienceRegisterController::class, 'changePassword']);

Route::post('auth/verify-otp', [UserRegisterController::class, 'verifyOtp']);

Route::group(["middleware" => ["auth:api"]], function () {

    Route::prefix('users/')->group(function () {

        Route::post('wallets/create', [WalletController::class, 'createWallet']);

        Route::get('wallets/transactions/{id}', [TransactionController::class, 'show']);
        Route::get('companies', [CompanyController::class, 'index']);
        Route::post('companies', [CompanyController::class, 'storeCompany']);
        Route::put('companies/{company}', [CompanyController::class, 'updateCompany']);
        Route::delete('companies/{company}', [CompanyController::class, 'deleteCompany']);
        Route::get('clients', [ClientController::class, 'index']);
        Route::post('clients', [ClientController::class, 'storeClient']);
        Route::put('clients/{client}', [ClientController::class, 'updateClient']);
        Route::delete('clients/{client}', [ClientController::class, 'deleteClient']);
        Route::get('brands', [BrandController::class, 'index']);
        Route::post('brands', [BrandController::class, 'storeBrand']);
        Route::put('brands/{brand}', [BrandController::class, 'updateBrand']);
        Route::delete('brands/{brand}', [BrandController::class, 'deleteBrand']);
        Route::get('logout', [LogoutController::class, 'logout']);

        require __DIR__ . '/shared.php';
    });
});

// Audience routes
Route::middleware('auth:audience')->group(function () {

    Route::prefix('audiences/')->group(function () {
        Route::get('home/user-info', [ProfileController::class, 'userInfo']);
        Route::get('home/user-profile', [ProfileController::class, 'profile']);
        Route::get('home/top-three', [OverallCampaignGamePlayLeaderboardController::class, 'overallGamePlayTopThree']);
        Route::get('home/campaigns-game', [CampaignGameController::class, 'indexCampaignGame']);
        Route::get('home/favorite-games', [CampaignGameController::class, 'indexFavorite']);

        Route::get('audiences/home/gamez/{game_id}', [GameController::class, 'showGame']);
        Route::patch('audiences/home/gamez/{game_id}/favorite', [GameController::class, 'toogleFavorite']);

        Route::get('audiences/play-game', [CampaignController::class, 'goToCampaignGame'])->name('play.game');

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
        Route::post("audiences/account-settings/profile/upload-image", [ProfileController::class, "uploadImage"]);
        Route::patch('audiences/account-settings/security/change-password', [ChangePasswordController::class, 'changePassword']);
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

        require __DIR__ . '/shared.php';
    });
    Route::get('audiences/logout', [LogoutController::class, 'logout']);
});
