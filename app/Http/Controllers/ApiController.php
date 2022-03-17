<?php

namespace App\Http\Controllers;

use App\Models\Initialze;
use App\Models\InitialzedData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class ApiController extends Controller

{
    public function initialize(Request $request)
    {

        $request->validate([
            "transaction_id" => 'required|string',
            "total_amount" => 'required|integer',
            "return_url" => 'required|string'
        ]);
        $request['currency'] = 'XAF';
        $apibasic = 'payunit_sand_A6Db0FGsw:d86d7f17-4d42-43c5-84f6-6bf9de8ac126';
        $base64 = base64_encode($apibasic);
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


        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . $base64,
            'x-api-key' => 'cdaec973655bfdd12a25222dc42a27c32a916a88',
            'mode' => 'test'
        ])->post('https://app.payunit.net/api/gateway/initialize', [
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

        return $initialise;
    }

    public function getAllPSP()
    {
        $apibasic = 'payunit_sand_A6Db0FGsw:d86d7f17-4d42-43c5-84f6-6bf9de8ac126';
        $base64 = base64_encode($apibasic);

        if (response(200)) {


            // $initialiseData = DB::select('select * from users where id = ?', [1]);

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . $base64,
                'x-api-key' => 'cdaec973655bfdd12a25222dc42a27c32a916a88',
                'mode' => 'test'
            ])->get('https://app.payunit.net/api/gateway/gateways',[]
            );

            return $response->json();
        }

        return 'not ok';
    }

    public function makepayment(Request $request)
    {
        $apibasic = 'payunit_sand_A6Db0FGsw:d86d7f17-4d42-43c5-84f6-6bf9de8ac126';
        $base64 = base64_encode($apibasic);
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . $base64,
            'x-api-key' => 'cdaec973655bfdd12a25222dc42a27c32a916a88',
            'mode' => 'test'
        ])->post('https://app.payunit.net/api/gateway/makepayment', [
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
        $apibasic = 'payunit_sand_A6Db0FGsw:d86d7f17-4d42-43c5-84f6-6bf9de8ac126';
        $base64 = base64_encode($apibasic);

        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . $base64,
            'x-api-key' => 'cdaec973655bfdd12a25222dc42a27c32a916a88',
            'mode' => 'test'
        ])->get('https://app.payunit.net/api/gateway/gateways', [
            "gateway" => $this->$this->$res->data->gateway,
            "transaction_id" => $this->$this->$res->data->transaction_id,
            "pay_token" => $this->$this->$res->data->pay_token,
            "pay_ref" => $this->$this->$res->data->pay_ref

        ]);
        return $response->json();
    }
}
