<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\CompanyController;
use App\Http\Controllers\CampaignController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\CompanyUserController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CampaignGameController;
use App\Http\Controllers\user\ProfileController;
use App\Http\Controllers\wallet\WalletController;
use App\Http\Controllers\CampaignGameRuleController;
use App\Http\Controllers\search\searchGameController;
use App\Http\Controllers\TransactionHistoryController;
use App\Http\Controllers\CampaignLeaderboardController;
use App\Http\Controllers\search\searchTransactionController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix' => 'v1/'], function () use ($router) {
    
    $router->group(['prefix' => 'auth/'], function () use ($router) {
        //works
        $router->group(['prefix' => 'user'], function () use ($router) {
            $router->post('register', [RegisterController::class, 'clientRegister']); 
            $router->post('verify/otp', [RegisterController::class, 'verify']); 
            $router->post('create/pin', [RegisterController::class, 'createPIN']); 
            $router->post('/login', [LoginController::class, 'login']);
          
        });
       
    });

});

Route::middleware('auth:api')->group(function ($router) {
    $router->group(['prefix' => 'v1/'], function () use ($router) {

        //  Wallet (works)
        $router->group(['prefix' => 'wallets/'], function() use($router) {
            $router->post('create', [WalletController::class, 'createWallet']);
          
            $router->group(['prefix' => 'transactions/'], function () use ($router) {
                $router->get('{id}', [TransactionController::class, 'show']);
            });
        });


        // works
        $router->group(['prefix' => 'rules/'], function () use ($router) {
            $router->get('/', [CampaignGameRuleController::class, 'index']);
            $router->post('/', [CampaignGameRuleController::class, 'create']);
            $router->get('/{rule_id}', [CampaignGameRuleController::class, 'show']);
        });


        // all the router below should be accessible by only authorized personnel(admin, company staff, superadmin)
        //works
        $router->group(['prefix' => 'companies/'], function () use ($router) {
            $router->get('/', [CompanyController::class, 'index']);
            $router->post('/', [CompanyController::class, 'storeCompany']);
        });

        //works
        $router->group(['prefix' => 'company-user/'], function () use ($router) {
            $router->get('/', [CompanyUserController::class, 'indexCompanyUser']);
            $router->post('/', [CompanyUserController::class, 'storeCompanyUser']);
        });

        //works
        $router->group(['prefix' => 'brands/'], function () use ($router) {
            $router->get('/', [BrandController::class, 'index']);
            $router->post('/', [BrandController::class, 'storeBrand']);
        });

        //works
        $router->group(['prefix' => 'clients/'], function () use ($router) {
            $router->get('/', [ClientController::class, 'index']);
            $router->post('/', [ClientController::class, 'storeClient']);
        });

        //works
        $router->group(['prefix' => 'games/'], function() use ($router) {
            // validate if we just have one game to one campaign
            $router->get('/', [GameController::class, 'index']);
            $router->post('/', [GameController::class, 'storeGame']);

            $router->group(['prefix' => '{game_id}/'], function() use ($router) {
                $router->get('show-game', [GameController::class, 'showGame']);
                $router->patch('update-game', [GameController::class, 'updateGame']);
            });     

        });
       
    });
});


Route::middleware('auth:api')->group(function ($router) {
    $router->group(['prefix' => 'v1/'], function () use ($router) {
        $router->group(['prefix' => 'home'], function() use ($router) {
            $router->get('user-info', [ProfileController::class, 'userInfo']);
            $router->get('/top-three', [CampaignLeaderboardController::class, 'leaderboardTopThree']);
            $router->get('/campaigns', [CampaignController::class, 'index']);
            $router->get('/campaigns-game', [CampaignGameController::class, 'indexCampaignGame']);
            $router->get('/favorite-games', [GameController::class, 'indexFavorite']);
            $router->get('list-games-type', [GameController::class, 'gamesByType']);

            $router->group(['/campaign'], function () use ($router) {
                $router->group(['/{campaign_id}'], function () use($router) {
                    $router->get('game/{game_id}', [CampaignGameController::class, 'showCampaignGame']);

                });

            });

            $router->group(['/game'], function() use($router) {
                $router->get('/{game_id}', [GameController::class, 'showGame']);  
                $router->patch('{game_id}/favorite', [GameController::class, 'toogleFavorite']);
              
            });
            
            
        });

        $router->group(['prefix' => 'gameboard'], function() use ($router) {
            $router->post('search-game', [searchGameController::class, 'searchGame']);
            $router->get('user-info', [ProfileController::class, 'UserInfo']);
            $router->post('/category', [GameController::class, 'filter']);
            $router->get('/', [GameController::class, 'index']);
            $router->patch('/{game_id}/favorite', [GameController::class, 'toogleFavorite']);
              
        });

        
        $router->group(['prefix' => 'leaderboards/'], function() use ($router) {
            $router->post('/', [CampaignLeaderboardController::class, 'storeLeaderBoard']);
            $router->get('/daily', [CampaignLeaderboardController::class, 'showDaily']);
            $router->get('/weekly', [CampaignLeaderboardController::class, 'showWeekly']);
            $router->get('/monthly', [CampaignLeaderboardController::class, 'showMonthly']);
            $router->get('/alltime', [CampaignLeaderboardController::class, 'showAllTime']);
            $router->get('/top-three', [CampaignLeaderboardController::class, 'leaderboardTopThree']);

        });

        $router->group(['prefix' => 'account-settings'], function() use($router) {
            $router->get("profile", [ProfileController::class, "profile"]);
            $router->post("profile/edit", [ProfileController::class, "profileEdit"]);
            $router->group(['prefix' => 'security'], function() use ($router) {
                $router->post('change-password', [RegisterController::class, 'changePassword']); 
                $router->post('change-pin', [RegisterController::class, 'changePin']);

            });
            $router->group(['prefix'=> 'wallet'], function() use($router) {
                $router->get('/fund-wallet', [WalletController::class, 'showAccountNumber']);
                $router->post('/transfer-funds', [WalletController::class, 'transferFund']);
                $router->get('/transaction-history', [WalletController::class, 'transactionHistory']);
                $router->post('/search-transaction', [searchTransactionController::class, 'searchTransaction']);
                
                $router->group(['prefix' => '{wallet_id}'], function() use($router) {
                    $router->get('wallet-balance', [WalletController::class, 'getWalletBalance']);
                    $router->post('/transaction-history', [TransactionHistoryController::class, 'storeTxHistory']);

                });
                $router->get('/transaction-history', [TransactionHistoryController::class, 'getTxHistory']);
            });
        });
    });
});