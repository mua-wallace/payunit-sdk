<?php

namespace App\Http\Controllers;

use App\Models\PayUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use SebastianBergmann\Environment\Console;

class DonationController extends Controller
{

    public  function generateTransactionId($length)
    {
        return substr(sha1(time()), 0, $length);
    }
    // $transaction_id = generateTransactionId(15)



    public function donate(Request $request)
    {
        // /home/muawallace/Projects/NestJS/ArtisanInspire/sdk/resources/views/response.blade.php

        $myPayment = new PayUnit(
            env("API_KEY"),
            env("API_SECRET"),
            env("API_USER"),
            "http://127.0.0.1:8000/success",
            "",
            "test",
            "",
            "",
            "XAF",
            "muawallace",
            $this->generateTransactionId(12),
        );
        return $myPayment->makePayment($request->post('total_amount'));
    }




    public function initialize()
    {
        $apibasic = 'payunit_sand_A6Db0FGsw:d86d7f17-4d42-43c5-84f6-6bf9de8ac126';
         $base64 = base64_encode($apibasic);
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'Authorization' => `Basic {$base64},
            'x-api-key' => 'cdaec973655bfdd12a25222dc42a27c32a916a88',
            'mode' => 'test'
        ])->post('https://app.payunit.net/api/gateway/initialize');
        return $response->json();
    }
    
}
