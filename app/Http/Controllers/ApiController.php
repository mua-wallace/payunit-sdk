<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller

{
    public $serverResponse = [];

    public function __construct()  {

    }

    public function initialize(Request $request) {
        $apibasic = 'payunit_sand_A6Db0FGsw:d86d7f17-4d42-43c5-84f6-6bf9de8ac126';
         $base64 = base64_encode($apibasic);
        //  echo $base64
        $welikemoney = $request->total_amount + 700;
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic '.$base64,
            'x-api-key' => 'cdaec973655bfdd12a25222dc42a27c32a916a88',
            'mode' => 'test'
        ])->post('https://app.payunit.net/api/gateway/initialize',[
            "transaction_id"=> $request->transaction_id,
            "total_amount"=> $welikemoney,
            "currency"=> $request->currency,
            "return_url"=> $request->return_url
        ]);
    
        $this->$serverResponse = $response->json();

        return $response;
    }

    public function getAllPSP() {
        $apibasic = 'payunit_sand_A6Db0FGsw:d86d7f17-4d42-43c5-84f6-6bf9de8ac126';
        $base64 = base64_encode($apibasic);
        
         $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic '.$base64,
            'x-api-key' => 'cdaec973655bfdd12a25222dc42a27c32a916a88',
            'mode' => 'test'
         ])->get('https://app.payunit.net/api/gateway/gateways',[
             "t_url" => $this->$this->$serverResponse->data->t_url,
             "t_id" => $this->$this->$serverResponse->data->t_id,
             "t_sum" => $this->$this->$serverResponse->data->t_sum
    
         ]);
         return $response->json();
    }
    
    public function makepayment(Request $request) {
        $apibasic = 'payunit_sand_A6Db0FGsw:d86d7f17-4d42-43c5-84f6-6bf9de8ac126';
        $base64 = base64_encode($apibasic);
           $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic '.$base64,
            'x-api-key' => 'cdaec973655bfdd12a25222dc42a27c32a916a88',
            'mode' => 'test'
        ])->post('https://app.payunit.net/api/gateway/makepayment',[
            "gateway" => $request->gateway,
            "amount" => $request->amount,
            "transaction_id"=> $request->transaction_id,
            "phone_number" => $request->phone_number,
            "currency"=> $request->currency,
            "paymentType" => $request->paymentType,
            "name" => $request->name,
            "notify_url"=> $request->notify_url
        ]);
           return $response->json();
    }
}
