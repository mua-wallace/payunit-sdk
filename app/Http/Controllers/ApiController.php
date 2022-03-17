<?php

namespace App\Http\Controllers;

use App\Models\Initialze;
use App\Models\InitialzedData;
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

        $response = $this->requestHeader()->post($this->baseUrl.'/gateway/initialize', [
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

        return $this->getAllPSP($initialiseData->t_url, $initialiseData->t_id,$initialiseData->t_sum);
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
        $response = $this->requestHeader()->post($this->baseUrl.'/gateway/makepayment', [
            "gateway" => $request->gateway,
            "amount" => $request->amount,
            "transaction_id" => $request->transaction_id,
            "phone_number" => $request->phone_number,
            "currency" => $request->currency,
            "paymentType" => $request->paymentType,
            "name" => $request->name,
            "notify_url" => $request->notify_url
        ]);
        return $response->json();
    }

    public function status()
    {
        $response = $this->requestHeader()->get($this->baseUrl.'/gateway/gateways', [
            "gateway" => $this->$this->$res->data->gateway,
            "transaction_id" => $this->$this->$res->data->transaction_id,
            "pay_token" => $this->$this->$res->data->pay_token,
            "pay_ref" => $this->$this->$res->data->pay_ref

        ]);
        return $response->json();
    }
}
