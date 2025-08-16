<?php
namespace App\Services\Transactions;

use Carbon\Carbon;
use App\Models\Transaction;
use App\Models\BrandTransaction;
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

        if ($this->filter == "daily") {
                $brandTransaction->whereDate('created_at', Carbon::now()->toDateString());
                  
        } else if ($this->filter == "weekly") {
                $start_week = Carbon::now()->startOfWeek()->format('Y-m-d');
                $end_week = Carbon::now()->endOfWeek()->format('Y-m-d');

                $brandTransaction->whereDate('created_at', '>=', $start_week)->whereDate('created_at', '<=', $end_week);

        } else if ($this->filter == 'monthly') {            
                $start_month = Carbon::now()->firstOfMonth()->format('Y-m-d');
                $end_month = Carbon::now()->lastOfMonth()->format('Y-m-d');
                $brandTransaction->whereDate('created_at', '>=', $start_month)->whereDate('created_at', '<=', $end_month);
        } 
        
        return $brandTransaction->get();

        // if ($this->filter !== null && $this->filter != '') {
            // dd($this->filter);

            // $brandTransaction->where(function($query) {
            //     foreach(
            //         [
            //         'payment_channel',
            //         'payment_channel_description',
            //         'status',
            //         'sender_name',
            //         'amount',
            //     ] as $column
            //     ) {
            //         $query->orWhere($column, 'like', '%' . $this->filter . '%');
            //     }

            //     if (in_array(strtolower($this->filter), ['true', 'false', '1', '0'])) {

            //         $query->Where('is_credit', filter_var($this->filter, FILTER_VALIDATE_BOOLEAN));
                    
            //     }
            // });
            
            // Handle boolean filtering for is_credit
          
        // }



                 
    }

}