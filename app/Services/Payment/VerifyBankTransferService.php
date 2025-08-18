<?php

namespace App\Services\Payment;
use Exception;
use Illuminate\Http\Request;
use App\Models\BrandTransaction;
use Illuminate\Support\Facades\Http;
use App\Services\BaseServiceInterface;

class VerifyBankTransferService implements BaseServiceInterface
{
    protected $request;
    protected $brandId;
    protected $refNumber;

    public function __construct(Request $request, $brandId, $refNumber)
    {
        $this->request = $request;
        $this->brandId = $brandId;
        $this->refNumber = $refNumber;
    }

    public function run()
    {
        try {
            $user = $this->request->user();
            $wallet = $user->wallet;

            if (!$wallet) {
                throw new Exception("Wallet not found");
            }
            
            $verifyUrl = config('app.seerbit.verify_url');
            $bearerToken = config('app.seerbit.bearer_token');
            // dd($verifyUrl, $bearerToken);

            $response = Http::withHeaders([
                    'Authorization' =>  'Bearer ' . $bearerToken, 
                    'Content-Type' => 'application/json'
                ])
                ->get("{$verifyUrl}{$this->refNumber}");

            $responseData = $response->json();

            // return $responseData;
            if ($responseData["status"] ===  "SUCCESS" && $responseData["data"]["code"] === "00") {

                $paymentInfo = $responseData["data"]["payments"];
                $data["paymentType"] = $paymentInfo["paymentType"];
                $data["paymentReference"] = $paymentInfo["paymentReference"];
                $data["amount"] = $paymentInfo["amount"];
                $data["transactionProcessedTime"] = $paymentInfo["transactionProcessedTime"];
                $data["customer_email"] =  $responseData["data"]["customers"]["customerEmail"];
                $data["customer_phone_number"] =  $responseData["data"]["customers"]["customerMobile"];
                

                // if ($data["customer_email"] != $user->email) {
                //     throw new Exception("account does not match the user");

                // }
                
                $brandTrans = BrandTransaction::create([
                    'audience_id' => $user->id, 
                    'wallet_id' => $wallet->id, 
                    'brand_id' => $this->brandId, 
                    'payment_channel' => 'Seerbit', 
                    'payment_channel_description' => 'Bank Transfer',
                    'is_credit' => true,
                    'sender_name' => $user->first_name ?? $user->email,
                    'payment_reference' => $data["paymentReference"], 
                    'status' => strtolower($responseData["status"]),
                    'amount' =>  $data["amount"]
                    
                ]);


                $amount = $paymentInfo["amount"];
                $amount = round($amount, 2);
                // dd($amount);

                $wallet->balance += $amount;
                $wallet->save();


                return $brandTrans;

            }
            
            throw new Exception("unable to fund wallet, pls try again later");

            // return $this->sendError("unable to fund wallet, pls try again later");

                
                
        } catch (\Exception $e) {

            throw $e;
        }
    }

    private function statusFunction() {

        $txnStatus = [
            "SUCCESS" => 'success',
            "PENDING" => 'pending',
            "FAILED" => 'failed'
        ];

        return $txnStatus;
    }
    private function mapStatus(string $status, array $statusMap) {
       return $statusMap[$status] ?? null;
    }

    
}
