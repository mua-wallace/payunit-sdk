<?php

namespace App\Http\Controllers;

use SevenGps\PayUnit;
use Illuminate\Http\Request;

class DonationController extends Controller
{

    public function donate(Request $request) {
        $myPayment = new PayUnit(
            $_ENV["API_KEY"],
            $_ENV["API_SECRET"],
            $_ENV["API_USER"],
            "http://localhost:8000/post",
            "notifyUrl",
            "",
            "test",
            "",
            "XAF",
            "wallace",
            "transactionId"
          );
    }

}