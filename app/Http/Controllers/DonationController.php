<?php

namespace App\Http\Controllers;

use App\Models\PayUnit;
use Illuminate\Http\Request;

class DonationController extends Controller
{

   public  function generateTransactionId($length)
{
    return substr(sha1(time()), 0, $length);
}
// $transaction_id = generateTransactionId(15);


    public function donate(Request $request) {
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


}