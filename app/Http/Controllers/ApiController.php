<?php

namespace App\Http\Controllers;

use App\Models\Initialze;
use App\Models\InitialzedData;
use App\Models\MtnsucesssData;
use Error;
use Exception;
use Illuminate\Http\Request;
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
            "transaction_id" => 'required|string',
            "total_amount" => 'required|integer',
            "return_url" => 'required|string'
        ]);
        $request['currency'] = 'XAF';
        $welikemoney = $request->total_amount + 700;

        $initialise = new Initialze;
        $initialise->transaction_id = $request->transaction_id;
        $initialise->total_amount = $request->total_amount;
        $initialise->currency = $request->currency;
        $initialise->return_url = $request->return_url;
        $initialise->notify_url = $request->notify_url;
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

        $initialiseData = new InitialzedData;
        $initialiseData->t_url = $response['data']['t_url'];
        $initialiseData->t_id = $response['data']['t_id'];
        $initialiseData->t_sum = $response['data']['t_sum'];
        $initialiseData->transaction_id = $response['data']['transaction_id'];
        $initialiseData->save();

        // return $response->json();

        return $this->getAllPSP($initialiseData->t_url, $initialiseData->t_id, $initialiseData->t_sum);
    }



    public function getAllPSP($t_url, $t_id, $t_sum)
    {
        $response = $this->requestHeader()->get($this->baseUrl. '/gateway/gateways', [
            't_url' => $t_url,
            't_id' => $t_id,
            't_sum' => $t_sum
        ]);

        return $response->json();
    }

    public function makepayment(Request $request)
    {
        $transca = InitialzedData::where('transaction_id', '=', $request->transaction_id)->first();

        $transca1 = MtnsucesssData::where('transaction_id', '=', $request->transaction_id)->first();


        // dd($transca1);


        // dd($transca->initialize);


            if($transca) {
                $response = $this->requestHeader()->post($this->baseUrl . '/gateway/makepayment', [
                    "gateway" => $request->gateway,
                    "amount" => $transca->initialize->total_amount,
                    "transaction_id" => $transca->initialize->transaction_id,
                    "phone_number" => $request->phone_number,
                    "currency" => $transca->initialize->currency,
                    "paymentType" => $request->paymentType,
                    "name" => $transca->initialize->name,
                    "notify_url" => $transca->initialize->notify_url
                ]); 
      
                if($request->gateway == 'mtnmomo') {
                    $mtnsuccessdata = new MtnsucesssData;
                    $mtnsuccessdata->gateway = $request->gateway;
                    $mtnsuccessdata->status = $response['status'];
                    $mtnsuccessdata->message = $response['message'];
                    $mtnsuccessdata->payment_ref = $response['data']['payment_ref'];
                    $mtnsuccessdata->transaction_id = $response['data']['transaction_id'];
                    $mtnsuccessdata->pay_token= $response['data']['pay_token'];
                    // $mtnsuccessdata->save();
                } 

                // return $transca1;
                return $this->getpaymentstatus($mtnsuccessdata->gateway, $mtnsuccessdata->transaction_id, $mtnsuccessdata->pay_token, $mtnsuccessdata->payment_ref);

                // return $mtnsuccessdata;
            } 
        }

   public function getpaymentstatus($gateway, $transactionId, $payToken, $paymentRef)
    {
        $response = $this->requestHeader()->get($this->baseUrl . '/gateway/paymentstatus/' . $gateway . '/' . $transactionId, [
            "pay_token" => $payToken,
            "pay_ref" => $paymentRef

        ]);
        return $response->json();
    }
}
