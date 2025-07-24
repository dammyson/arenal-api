<?php

namespace App\Services\Prize;

use App\Services\BaseServiceInterface;
use App\Models\Prize;
use Illuminate\Http\Request;

class RedeemUserBrandPrizeService implements BaseServiceInterface{
    protected $prize;

    public function __construct(Prize $prize)
    {
        $this->prize = $prize;
    }

    public function run() {
        
        $this->prize->is_redeemed = true;
        $this->prize->save();

        return $this->prize;

    }
}