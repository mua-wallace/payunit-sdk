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
        
        $myPayment = new PayUnit(
            env("API_KEY"),
            env("API_SECRET"),
            env("API_USER"),
            "http://localhost:8000/welcome",
            "",
            "live",
            "",
            "",
            "XAF",
            "muawallace",
            $this->generateTransactionId(12),
          );
        return $myPayment->makePayment($request->post('total_amount'));
    }


}