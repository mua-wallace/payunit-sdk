<?php

namespace App\Http\Controllers;

use App\Models\Initialze;
use App\Models\ResponseData;
use Illuminate\Http\Request;
use App\Models\InitialzedData;
use App\Models\MtnsucesssData;
use App\Rules\PhoneValidator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;



class ApiController extends Controller
{






    private $baseUrl = 'https://app.payunit.net/api';
    private function requestHeader()
    {

        $apibasic = env('API_BASIC');
        $base64 = base64_encode($apibasic);
        return Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . $base64,
            'x-api-key' => env('X_API_KEY'),
            'mode' => 'test'
        ]);
    }


    public function initialize(Request $request)
    {

        $request->validate([
            "transaction_id" => array('required', 'string'),
            "total_amount" => 'required|integer',
            "return_url" => 'required|string',
            "currency" => array('required', 'string','regex:/^(XAF|USD)$/'),
        ]);

        try {

        
            $welikemoney = $request->total_amount + 700;

            $initialise = new Initialze;
            $initialise->transaction_id = $request->transaction_id;
            $initialise->total_amount = $request->total_amount;
            $initialise->currency = $request->currency;
            $initialise->return_url = $request->return_url;
            $initialise->notify_url = "http://localhost:4000";
            $initialise->name = $request->name;
            $initialise->description = $request->description;
            $initialise->purchaseRef = $request->purchaseRef;
            $initialise->save();

            $response = $this->requestHeader()->post($this->baseUrl . '/gateway/initialize', [
                "transaction_id" => $request->transaction_id,
                "total_amount" => $welikemoney,
                "currency" => $request->currency,
                "return_url" => $request->return_url
            ]);

            Log::info('initialize data', compact('response'));

            if ($response->ok()) {
                $initialiseData = new InitialzedData;
                $initialiseData->t_url = $response['data']['t_url'];
                $initialiseData->t_id = $response['data']['t_id'];
                $initialiseData->t_sum = $response['data']['t_sum'];
                $initialiseData->transaction_id = $response['data']['transaction_id'];
                $initialiseData->save();
    
                return $this->getAllPSP($initialiseData->t_url, $initialiseData->t_id, $initialiseData->t_sum);
            }

            throw new  \Exception($response);           

        } catch (\Exception $exception) {
        throw new \Exception($exception->getMessage());
        }
    }



    public function getAllPSP($t_url, $t_id, $t_sum)
    {
        $response = $this->requestHeader()->get($this->baseUrl . '/gateway/gateways', [
            't_url' => $t_url,
            't_id' => $t_id,
            't_sum' => $t_sum
        ]);

        return $response->json();
    }

    public function makepayment(Request $request)
    {

        $request->validate([
           
            
            "phone_number" => array('required', new PhoneValidator),
        ]);

        $transca = InitialzedData::where('transaction_id', '=', $request->transaction_id)->first();


        $requestData = [
            "gateway" => $request->gateway,
            "amount" => $transca->initialize->total_amount,
            "transaction_id" => $transca->initialize->transaction_id,
            "phone_number" => $request->phone_number,
            "currency" => $transca->initialize->currency,
            "paymentType" => $request->paymentType,
            "name" => $transca->initialize->name,
            "notify_url" => $transca->initialize->notify_url
        ];

        $resData = [];
        $saveResponse = new ResponseData;
        $saveResponse->gateway = $request->gateway;

        if ($transca && $request->gateway === 'mtnmomo') {
            $resData = $this->requestHeader()->post($this->baseUrl . '/gateway/makepayment', $requestData);
            $saveResponse->payment_ref = $resData['data']['payment_ref'];
            $saveResponse->pay_token = $resData['data']['pay_token'];
        }

        if ($transca && $request->gateway === 'orange') {
            $resData = $this->requestHeader()->post($this->baseUrl . '/gateway/makepayment', $requestData);
            $saveResponse->auth_token = $resData['data']['auth-token'];
            $saveResponse->paytoken = $resData['data']['paytoken'];
            $saveResponse->x_token = $resData['data']['x-token'];
        }

        $saveResponse->transaction_id = $resData['data']['transaction_id'] ?? '';
        $saveResponse->status = $resData['status'] ?? 'FAILED';
        $saveResponse->message = $resData['message'] ?? 'No Provider was selected';
        $saveResponse->save();



        // return $resData;
        // return $saveResponse;

        if ($saveResponse && $saveResponse->gateway === 'mtnmomo') {
            return $this->getpaymentstatus($saveResponse->gateway, $saveResponse->transaction_id, $saveResponse->pay_token, $saveResponse->payment_ref);
        }

        if ($saveResponse && $saveResponse->gateway === 'orange') {
            return $this->getpaymentstatus($saveResponse->gateway, $saveResponse->transaction_id, $saveResponse->paytoken, $saveResponse->auth_token, $saveResponse->x_token);
        }
    }



    public function getpaymentstatus($gateway, $transactionId, $payToken, $paymentRef)
    {
        $response = $this->requestHeader()->get($this->baseUrl . '/gateway/paymentstatus/' . $gateway . '/' . $transactionId, [
            "pay_token" => $payToken,
            "payment_ref" => $paymentRef

        ]);


        return $response->json();
    }




    public function transactionStatus()
    {
        $datas = MtnsucesssData::where('status', '=', 'SUCCESS')->get();
        $count = count($datas);
        $number_new_success_transaction = 0;
        $number_new_failed_transaction = 0;
        $number_pedding_transaction = 0;
        if ($datas) {

            foreach ($datas as $data) {

                $response = $this->getpaymentstatus($data['gateway'], $data['transaction_id'], $data['pay_token'], $data['payment_ref']);
                if (isset($response['message'])) {

                    $transaction = MtnsucesssData::where('transaction_id', '=', $data['transaction_id'])->first();
                    if (str_contains($response['message'], 'successful')) {

                        $number_new_success_transaction++;
                        $transaction->status = 'SUCCESS';
                        $transaction->save();

                        // Send sms or email to customer
                    } else if (str_contains($response['message'], 'failed')) {

                        $number_new_failed_transaction++;
                        $transaction->status = 'FAILED';
                        $transaction->save();

                        // Send sms or email to customer
                    } else {

                        $number_pedding_transaction++;
                    }
                }
            }
        }

        return [
            'Old_pending_transactions' => $count,
            'New_pending_transactions' => $number_pedding_transaction,
            'New_success_transactions' => $number_new_success_transaction,
            'New_failed_transactions' => $number_pedding_transaction
        ];
    }

    public  function test()
    {
        return ['name' => 'wallice'];
    }
}
