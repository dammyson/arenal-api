<?php

namespace App\Services\Wallet;

use App\Models\Wallet;
use Illuminate\Http\Request;
use App\Services\BaseServiceInterface;

class CreateWalletService implements BaseServiceInterface{
    protected $user;

    public function __construct(Request $request)
    {
        $this->user = $request->user();
    }

    public function run() {
        return  Wallet::create([
            'user_id' => $this->user->id,
            'revenue_share_group' => 'audience'
        ]);
    }
}
