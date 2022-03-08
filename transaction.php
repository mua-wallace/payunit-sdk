<?php
require __DIR__ . '/vendor/autoload.php';

use SevenGps\PayUnit;
//get hte amount from the input 

if (isset($_POST['submit'])) {
    $total_amount = $_POST['amount'];
}
//generate random id for the transaction
function generateTransactionId($length)
{
    return substr(sha1(time()), 0, $length);
}
$transaction_id = generateTransactionId(15);

$myPayment = new PayUnit(
    getenv("API_KEY"),
    getenv("API_PASS"),
    getenv("API_USER"),
    "http://localhost:2023/response.php",
    "",
    "test",
    "Payment test",
    "",
    "XAF",
    "Wallice",
    $transaction_id
);
$myPayment->makePayment($total_amount);
