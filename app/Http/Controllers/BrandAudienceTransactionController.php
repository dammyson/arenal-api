<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Transactions\ListAudienceBrandTransactionService;

class BrandAudienceTransactionController extends BaseController
{
      public function audienceTransactionHistory(Request $request) {
          try {
            $filter = $request->query('filter');
            
            $data = (new ListAudienceBrandTransactionService($request->user()->id, $filter))->run();

        }  catch (\Exception $e){
            return $this->sendError("something went wrong", ['error' => $e->getMessage()], 500);
        }        
        return $this->sendResponse(["transaction_history" => $data], "Brand transaction history succcessfully");
    }
}
