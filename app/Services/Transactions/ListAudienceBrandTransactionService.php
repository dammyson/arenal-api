<?php
namespace App\Services\Transactions;

use App\Models\BrandTransaction;
use App\Models\Transaction;
use App\Services\BaseServiceInterface;

class ListAudienceBrandTransactionService implements BaseServiceInterface {
    protected $brandId;
    protected $audienceId;

    public function __construct($brandId, $audienceId) {
        $this->brandId = $brandId;
        $this->audienceId = $audienceId;
    }

    public function run() {
        return BrandTransaction::where('brand_id', $this->brandId)
            ->where('audience_id', $this->audienceId)
            ->get();
    }

}