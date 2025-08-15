<?php
namespace App\Services\Transactions;

use App\Models\BrandTransaction;
use App\Models\Transaction;
use App\Services\BaseServiceInterface;

class ListAudienceBrandTransactionService implements BaseServiceInterface {
    protected $brandId;
    protected $audienceId;
    protected $filter;

    public function __construct($brandId, $audienceId, $filter = null) {
        $this->brandId = $brandId;
        $this->audienceId = $audienceId;
        $this->filter = $filter;
    }

    public function run() {
        $brandTransaction = BrandTransaction::where('audience_id', $this->audienceId);
        
        if ($this->filter !== null && $this->filter != '') {
            // dd($this->filter);

            $brandTransaction->where(function($query) {
                foreach(
                    [
                    'payment_channel',
                    'payment_channel_description',
                    'status',
                    'sender_name',
                    'amount',
                ] as $column
                ) {
                    $query->orWhere($column, 'like', '%' . $this->filter . '%');
                }

                if (in_array(strtolower($this->filter), ['true', 'false', '1', '0'])) {

                    $query->Where('is_credit', filter_var($this->filter, FILTER_VALIDATE_BOOLEAN));
                    
                }
            });
            
            // Handle boolean filtering for is_credit
          
        }


        return $brandTransaction->get();

                 
    }

}