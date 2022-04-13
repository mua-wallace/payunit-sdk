<?php

namespace App\Http\Controllers;

use App\Models\Initialze;
use App\Models\InitialzedData;
use App\Models\MtnsucesssData;
use App\Models\ResponseData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

/**
     * @OA\Post(
     * path="/api/initializerrr",
     * summary="Initialize payment",
     * description="Initialize payment",
     * tags={"payunit"},
     * @OA\RequestBody(
     *    required=true,
     *    description="Pass initialize  credentials",
     *    @OA\JsonContent(
     *       required={"transaction_id","total_amount", "currency", "return_url"},
     *       @OA\Property(property="transaction_id", type="string", format="string", example="123456789101112"),
     *       @OA\Property(property="total_amount", type="integer", format="string", example="2000"),
     *       @OA\Property(property="currency", type="string", format="string", example="XAF or USD"),
     *       @OA\Property(property="return_url", type="string", format="string", example="http://localhost:4000"),
     *       @OA\Property(property="name", type="string", format="string", example="mtn or orange etc"),
     *       @OA\Property(property="description", type="string", format="string", example="Your description"),
     *       @OA\Property(property="purchaseRef", type="string", format="string", example="any_reference_number"),
     *    ),
     * ),
     * @OA\Response(
     *    response=422,
     *    description="Wrong credentials response",
     *    @OA\JsonContent(
     *       @OA\Property(property="message", type="string", example="Sorry, wrong  transaction_id or total_amount, currency or return_url. Please try again"),
     *    ),
     * ),
     *  @OA\Response(
     *    response=200,
     *    description="List of all marks or a single mark",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="boolean", example="false"),
     *       @OA\Property(property="status_code", type="integer", example="200"),
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="message", type="string", example="Transaction  has been successfully initiated!"),
     *    )
     * ),
     * )
     */




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
        $response = $this->requestHeader()->get($this->baseUrl . '/gateway/gateways', [
            't_url' => $t_url,
            't_id' => $t_id,
            't_sum' => $t_sum
        ]);

        return $response->json();
    }

    public function makepayment(Request $request)
    {
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
    // dd($transca->initialize);



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
        // $status = DB::select('select * from mtnsucessdata where status');
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
