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
use App\Models\CampaignGamePlay;

Route::group(['prefix' => 'users'], function ($router) {
    $router->group(['prefix' => 'auth'], function () use ($router) {
        $router->post('register', [UserRegisterController::class, 'userRegister']); 
        $router->post('/login', [UserLoginController::class, 'login']);
        
    });
});


Route::group(['prefix' => 'audiences'], function ($router) {
    $router->group(['prefix' => 'auth'], function () use ($router) {
        $router->post('/register', [AudienceRegisterController::class, 'registerAudience']); 
        $router->post('/login', [AudienceLoginController::class, 'login']);
      
    });        
});


Route::group(["middleware" => ["auth:api"]], function ($router) {
    $router->group(['prefix' => 'users/'], function () use ($router) {
       
        //  Wallet (works)
        $router->group(['prefix' => 'wallets/'], function() use($router) {
            $router->post('create', [WalletController::class, 'createWallet']);
          
            // This route is not completely implemented(awaiting payment system)
            $router->group(['prefix' => 'transactions/'], function () use ($router) {
                $router->get('{id}', [TransactionController::class, 'show']);
            });
        });



        // all the router below should be accessible by only authorized personnel(admin, company staff, superadmin)
        //works
        $router->group(['prefix' => 'companies/'], function () use ($router) {
            $router->get('/', [CompanyController::class, 'index']);
            $router->post('/', [CompanyController::class, 'storeCompany']);
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

        $router->group(['prefix' => 'campaigns/'], function () use ($router) {
            $router->get('/', [CampaignController::class,'index']);           
            $router->post('/', [CampaignController::class,'storeCampaign']);
            $router->get('/{campaign_id}', [CampaignController::class,'showCampaign']);
        });

        $router->group(['prefix' => 'campaign' ], function() use ($router) {
            $router->group(['prefix' => '{campaign_id}' ], function() use ($router) {
                $router->group(['prefix' => 'campaign-game'], function () use ($router) {
                    $router->post('/', [CampaignGameController::class, 'storeCampaignGame']);
                    $router->get('/', [CampaignGameController::class, 'indexCampaignGame']);
                });

                $router->group(['prefix' => 'game'], function() use ($router) {
                    $router->post('/{game_id}/rules', [CampaignGameRuleController::class, 'store']);
                    $router->get('/{game_id}/rules', [CampaignGameRuleController::class, 'showCampaignGameRules']);
                });  
             
            });
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

        $router->group(['prefix' => 'rules/'], function () use ($router) {
            $router->get('/', [CampaignGameRuleController::class, 'index']);
            $router->post('/', [CampaignGameRuleController::class, 'store']);
            $router->get('/{rule_id}', [CampaignGameRuleController::class, 'showCampaignGameRules']);
        });
        
        $router->get('/logout', [LogoutController::class, 'logout']);
       
    });
});


Route::middleware('auth:api')->group(function ($router) {
    $router->group(['prefix' => 'audiences/'], function () use ($router) {
        $router->group(['prefix' => 'home'], function() use ($router) {
            $router->get('user-info', [ProfileController::class, 'userInfo']);
            $router->get('user-profile', [ProfileController::class, 'profile']);
            $router->get('/top-three', [OverallCampaignGamePlayLeaderboardController::class, 'overallGamePlayTopThree']);
            $router->get('/campaigns', [CampaignController::class, 'index']);
            $router->get('/campaigns-game-type', [CampaignGameController::class, 'indexCampaignGame']);
            $router->get('/favorite-games', [CampaignGameController::class, 'indexFavorite']);

            $router->group(['prefix' => 'campaigns'], function () use ($router) {
                $router->group(['prefix' => '{campaign_id}'], function () use($router) {
                    $router->group(['prefix' => 'games'], function () use ($router) {
                        $router->get('game-plays', [CampaignGamePlayController::class, 'index']); // not seen in UI
                        $router->group(['prefix' => '{game_id}'], function () use ($router) {
                            $router->get('/show-campaign-game', [CampaignGameController::class, 'showCampaignGame']);
                            $router->post('/campaign-game-play', [CampaignGamePlayController::class, 'storeCampaignGamePlay']);
                            $router->get('game-plays', [CampaignGamePlayController::class, 'show']);
                            $router->put('game-plays', [CampaignGamePlayController::class, 'update']);
                            $router->delete('game-plays', [CampaignGamePlayController::class, 'destroy']);// not seen in UI

                            $router->group(['prefix' => 'campaign-game-leaderboard'], function () use ($router) {
                                $router->get('/daily', [CampaignGamePlayLeaderboardController::class, 'gameLeaderboardDaily']);
                                $router->get('/weekly', [CampaignGamePlayLeaderboardController::class, 'gameLeaderboardWeekly']);
                                $router->get('/monthly', [CampaignGamePlayLeaderboardController::class, 'gameLeaderboardMonthly']);
                                $router->get('/alltime', [CampaignGamePlayLeaderboardController::class, 'gameLeaderboardAllTime']);   
                            });
                        });
                    });
                });
            });
            

            $router->group(['prefix' => 'gamez'], function() use($router) {
                $router->get('/{game_id}', [GameController::class, 'showGame']);  
                $router->patch('/{game_id}/favorite', [GameController::class, 'toogleFavorite']);
              
            });
            
            
        });

        $router->group(['prefix' => 'gameboard'], function() use ($router) {
            $router->post('search-game', [SearchGameController::class, 'searchGame']);
            $router->get('user-info', [ProfileController::class, 'userInfo']);
            $router->post('/category', [FilterGameController::class, 'filter']);
            $router->get('/', [CampaignGameController::class, 'indexCampaignGame']);
            $router->patch('/{game_id}/favorite', [GameController::class, 'toogleFavorite']);
              
        });

        $router->group(['prefix' => 'general/'], function() use ($router) {
            $router->group(['prefix' => 'overall-leaderboard/'], function() use ($router) {
                $router->get('/daily', [OverallCampaignGamePlayLeaderboardController::class, 'overallLeaderboardDaily']);
                $router->get('/weekly', [OverallCampaignGamePlayLeaderboardController::class, 'overallLeaderboardWeekly']);
                $router->get('/monthly', [OverallCampaignGamePlayLeaderboardController::class, 'overallLeaderboardMonthly']);
                $router->get('/alltime', [OverallCampaignGamePlayLeaderboardController::class, 'overallLeaderboard']);

            });
        });
        
        $router->group(['prefix' => 'campaign' ], function() use ($router) {
            $router->group(['prefix' => '{campaign_id}' ], function() use ($router) {
                $router->group(['prefix' => 'game'], function() use ($router) {
                    $router->get('/{game_id}/rules', [CampaignGameRuleController::class, 'showCampaignGameRules']);
                });

                $router->group(['prefix' => 'campaign-game'], function () use ($router) {
                    $router->get('/', [CampaignGameController::class, 'indexCampaignGame']);
                });
            });

        });

        $router->group(['prefix' => 'account-settings'], function() use($router) {
            $router->get("profile", [ProfileController::class, "profile"]);
            $router->post("profile/edit", [ProfileController::class, "profileEdit"]);
            $router->group(['prefix' => 'security'], function() use ($router) {
                $router->patch('change-password', [ChangePasswordController::class, 'changePassword']); 

            });

            $router->group(['prefix'=> 'wallet'], function() use($router) {
                $router->get('/fund-wallet', [WalletController::class, 'showAccountNumber']);
                $router->post('/fund-wallet', [WalletController::class, 'fundWallet']);
                $router->post('/transfer-funds', [WalletController::class, 'transferFund']);
                $router->post('create', [WalletController::class, 'createWallet']);
                
                $router->group(['prefix' => '{wallet_id}'], function() use($router) {
                    $router->get('wallet-balance', [WalletController::class, 'getWalletBalance']);
                    $router->post('/transaction-history', [TransactionHistoryController::class, 'storeTxHistory']);
                    $router->get('/transaction-history', [TransactionHistoryController::class, 'getTxHistory']);
                    $router->post('/search-transaction', [SearchTransactionController::class, 'searchTransaction']);

                });
            });

            $router->get('/logout', [LogoutController::class, 'logout']);
        });
    });
});