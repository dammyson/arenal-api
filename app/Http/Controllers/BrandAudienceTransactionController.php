<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Transactions\ListAudienceBrandTransactionService;

class BrandAudienceTransactionController extends BaseController
{
      public function audienceTransactionHistory(Request $request, $brandId) {
          try {
            
            $data = (new ListAudienceBrandTransactionService($brandId, $request->user()->id))->run();

        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse(["transaction_history" => $data], "Brand transaction history succcessfully");
    }
}
