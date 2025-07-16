<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\TriviaController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\FilterGameController;
use App\Http\Controllers\SearchGameController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CampaignGameController;
use App\Http\Controllers\SpinTheWheelController;
use App\Http\Controllers\UserRegisterController;
use App\Http\Controllers\ChangePasswordController;
use App\Http\Controllers\TriviaQuestionController;
use App\Http\Controllers\AudienceRegisterController;
use App\Http\Controllers\CampaignGameRuleController;
use App\Http\Controllers\SearchTransactionController;
use App\Http\Controllers\SpinTheWheelSectorController;
use App\Http\Controllers\TransactionHistoryController;
use App\Http\Controllers\OverallCampaignGamePlayLeaderboardController;
use App\Http\Controllers\SpinTheWheelComponentController;

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

        
        Route::post('campaign', [CampaignController::class, 'storeCampaign']);   
        Route::post('campaign/{campaign_id}/campaign-game', [CampaignGameController::class, 'storeCampaignGame']);     
        Route::post('games', [GameController::class, 'storeGame']);        
        Route::patch('games/{game_id}/update-game', [GameController::class, 'updateGame']);
        Route::post('games/{game_id}/upload-images',  [GameController::class, 'uploadImages']);        
        Route::post('campaign/{campaign_id}/game/{game_id}/rules', [CampaignGameRuleController::class, 'store']);
        Route::post('trivia', [TriviaController::class, 'store'])->name('create');
        Route::post('trivia/questions', [TriviaQuestionController::class, 'storeMultiple']);
        Route::get('user-spin-the-wheel', [SpinTheWheelController::class, 'userIndex'])->name('user.index');
        
        Route::prefix('spin-the-wheel')->group(function() {
            Route::post('/', [SpinTheWheelController::class, 'store'])->name('create');
            Route::post('background', [SpinTheWheelComponentController::class, 'storeBackground'])->name('create.sector.segment');
            Route::post('sector', [SpinTheWheelComponentController::class, 'storeSector'])->name('create.spin.sectore');
            Route::post('segment', [SpinTheWheelComponentController::class, 'storeSegment'])->name('create.sector.segment');
            Route::put('segment/{segmentId}', [SpinTheWheelComponentController::class, 'updateSegment'])->name('create.sector.segment');
            Route::delete('segment/{segmentId}', [SpinTheWheelComponentController::class, 'deleteSegment'])->name('create.sector.segment');
            Route::post('button', [SpinTheWheelComponentController::class, 'storeButton'])->name('create.sector.segment');
            Route::post('form', [SpinTheWheelComponentController::class, 'storeForm'])->name('create.sector.segment');
            Route::post('user-form', [SpinTheWheelComponentController::class, 'storeUserForm'])->name('create.sector.segment');
            Route::post('set-user-form', [SpinTheWheelComponentController::class, 'setUserForm'])->name('create.sector.segment');
            Route::post('reward-setup', [SpinTheWheelComponentController::class, 'storeRewardSetup'])->name('create.sector.segment');
            Route::post('custom-text', [SpinTheWheelComponentController::class, 'storeCustomText'])->name('create.sector.segment');

        });
        // Route::post('spin/{spinId}/sector/{sectorId}', [SpinTheWheelController::class, 'storeSpinSector'])->name('create.spin.sectore');
        Route::get('logout', [LogoutController::class, 'logout']);

        require __DIR__ . '/shared.php';
    });
});

// Audience routes
Route::middleware('auth:audience')->group(function () {

    Route::prefix('audiences/')->group(function () {
        Route::get('user-info', [ProfileController::class, 'userInfo']);
        Route::get('user-profile', [ProfileController::class, 'profile']);
        Route::get('top-three', [OverallCampaignGamePlayLeaderboardController::class, 'overallGamePlayTopThree']);
        Route::get('favorite-games', [CampaignGameController::class, 'indexFavorite']);
        Route::post('spin-the-wheel-prize', [SpinTheWheelController::class, 'audiencePrize']);


        // Route::patch('gamez/{game_id}/favorite', [GameController::class, 'toogleFavorite']);

        Route::post('play-game', [CampaignController::class, 'goToCampaignGame'])->name('play.game');

        Route::post('gameboard/search-game', [SearchGameController::class, 'searchGame']);
        Route::post('gameboard/category', [FilterGameController::class, 'filter']);
        Route::get('gameboard', [CampaignGameController::class, 'indexCampaignGame']);
        Route::patch('gameboard/{game_id}/favorite', [GameController::class, 'toogleFavorite']);
                
        Route::get("account-settings/profile", [ProfileController::class, "profile"]);
        Route::post("account-settings/profile/edit", [ProfileController::class, "editProfile"]);
        Route::patch("account-settings/profile/pin", [ProfileController::class, "changePin"]);
        Route::post("account-settings/profile/upload-image", [ProfileController::class, "uploadImage"]);
        Route::patch('account-settings/security/change-password', [ChangePasswordController::class, 'changePassword']);

        Route::get('wallet/fund-wallet', [WalletController::class, 'showAccountNumber']);
        Route::post('wallet/fund-wallet', [WalletController::class, 'fundWallet']);
        Route::post('wallet/transfer-funds', [WalletController::class, 'transferFund']);
        Route::post('wallet/create', [WalletController::class, 'createWallet']);
        Route::get('wallet/{wallet_id}/wallet-balance', [WalletController::class, 'getWalletBalance']);
        Route::post('wallet/{wallet_id}/transaction', [TransactionController::class, 'storeTransaction']);
        Route::post('wallet/{wallet_id}/transaction-history', [TransactionHistoryController::class, 'storeTxHistory']);
        Route::get('wallet/{wallet_id}/transaction-history', [TransactionHistoryController::class, 'getTxHistory']);
        Route::post('wallet/{wallet_id}/search-transaction', [SearchTransactionController::class, 'searchTransactionHistory']);
        Route::post('wallet/{wallet_id}/filter-transaction', [SearchTransactionController::class, 'filterTransactionHistory']);

        require __DIR__ . '/shared.php';
    });
    Route::get('audiences/logout', [LogoutController::class, 'logout']);
});
