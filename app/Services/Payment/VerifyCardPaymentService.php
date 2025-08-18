<?php

namespace App\Services\Payment;

use Exception;
use Illuminate\Http\Request;
use App\Models\BrandTransaction;
use Illuminate\Support\Facades\Http;
use App\Services\BaseServiceInterface;

class VerifyCardPaymentService implements BaseServiceInterface
{
    protected $request;
    protected $brandId;
    protected $refNumber;

    public function __construct(Request $request, $brandId, $refNumber)
    {
        $this->request   = $request;
        $this->brandId   = $brandId;
        $this->refNumber = $refNumber;
    }

    public function run()
    {
        try {
            $user   = $this->request->user();
            $wallet = $user->wallet;

            if (!$wallet) {
                throw new Exception("Wallet not found");
            }

            $paymentChannel = strtolower($this->request->query('payment_channel'));

            $config = $this->getPaymentConfig($paymentChannel);

            if (!$config) {
                throw new Exception("Invalid payment channel");
            }

            $response = Http::withHeaders([
                'Content-Type'  => 'application/json',
                'Accept'        => 'application/json',
                'Authorization' => "Bearer " . $config['token']
            ])->get($config['url']);

            $responseData = $response->json();

            $brandTrans = $this->createBrandTransaction($user, $wallet, ucfirst($paymentChannel));

            if (isset($responseData['data']['amount'])) {
                $amount     = $config['amountHandler']($responseData['data']['amount']);
                $paymentRef = $responseData['data'][$config['paymentRefKey']] ?? null;

                $brandTrans->amount = $amount;
                $brandTrans->payment_reference = $paymentRef;

                $status = $this->mapStatus($responseData['data']['status'] ?? null, $config['statusMap']);

                if ($status === 'success') {
                    $wallet->balance += $amount;
                    $wallet->save();
                }

                $brandTrans->status = $status;
                $brandTrans->save();

                return $brandTrans;
            }

            throw new Exception("Unable to fund wallet, please try again later");
        } catch (\Exception $e) {
            throw $e;
        }
    }

    protected function getPaymentConfig(string $channel): ?array
    {
        $configs = [
            'paystack' => [
                'url'           => config('app.paystack.verify_url') . $this->refNumber,
                'token'         => config('app.paystack.bearer_token'),
                'amountHandler' => fn($amount) => round($amount / 100, 2),
                'paymentRefKey' => 'reference',
                'statusMap'     => [
                    'success' => 'success',
                    'pending' => 'pending',
                    'failed'  => 'failed'
                ]
            ],
            'flutterwave' => [
                'url'           => config('app.flutterwave.verify_url') . $this->refNumber . '/verify',
                'token'         => config('app.flutterwave.bearer_token'),
                'amountHandler' => fn($amount) => round($amount, 2),
                'paymentRefKey' => 'tx_ref',
                'statusMap'     => [
                    'success' => 'success',
                    'successful' => 'success',
                    'pending'    => 'pending',
                    'failed'     => 'failed'
                ]
            ]
        ];

        return $configs[$channel] ?? null;
    }

    protected function createBrandTransaction($user, $wallet, string $channel)
    {
        return BrandTransaction::create([
            'audience_id'                 => $user->id,
            'wallet_id'                   => $wallet->id,
            'brand_id'                    => $this->brandId,
            'payment_channel'             => $channel,
            'payment_channel_description' => 'Card Payment',
            'is_credit'                   => true,
            'sender_name'                 => $user->first_name ?? $user->email,
        ]);
    }

    protected function mapStatus(?string $rawStatus, array $map): string
    {
        return $map[$rawStatus] ?? 'failed';
    }
}
