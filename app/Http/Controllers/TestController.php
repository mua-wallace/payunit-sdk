<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

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

        return $this->initializeRequest($request);
    }

    public function initializeRequest($data)
    {
        $apibasic = 'payunit_sand_A6Db0FGsw:d86d7f17-4d42-43c5-84f6-6bf9de8ac126';
        $base64 = base64_encode($apibasic);
        $welikemoney = $data->total_amount + 700;
        return Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => 'Basic ' . $base64,
            'x-api-key' => 'cdaec973655bfdd12a25222dc42a27c32a916a88',
            'mode' => 'test'
        ])->post('https://app.payunit.net/api/gateway/initialize', [
            "transaction_id" => $data->transaction_id,
            "total_amount" => $welikemoney,
            "currency" => $data->currency,
            "return_url" => $data->return_url
        ]);
    }

    public function getAllPSPAndInitialise(Request $request)
    {

        $request->validate([
            "transaction_id" => 'required|string',
            "total_amount" => 'required|integer',
            "return_url" => 'required|string'
        ]);

        $request['currency'] = 'XAF';

        $apibasic = 'payunit_sand_A6Db0FGsw:d86d7f17-4d42-43c5-84f6-6bf9de8ac126';
        $base64 = base64_encode($apibasic);


        $req = new Request([
            "transaction_id" => "22222222222222222222267",
            "total_amount" => 100,
            "currency" => "XAF",
            "return_url" => "http://localhost:4000"
        ]);

        $res = $this->initializeRequest($req);

        if ($res->ok()) {

            $resInitialise = $res->json();

            $initialiseData = [
                "t_url" => $resInitialise['data']['t_url'],
                "t_id" => $resInitialise['data']['t_id'],
                "t_sum" => $resInitialise['data']['t_sum']
            ];

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
                'Authorization' => 'Basic ' . $base64,
                'x-api-key' => 'cdaec973655bfdd12a25222dc42a27c32a916a88',
                'mode' => 'test'
            ])->get('https://app.payunit.net/api/gateway/gateways', $initialiseData);

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
