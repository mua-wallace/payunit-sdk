<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

/**
 * @OA\Post(
 * path="/api/makepayment",
 * summary="make a paymenment",
 * description="make payments",
 * security={{"bearer_token":{}}},
 * tags={"payunit"},
 * @OA\RequestBody(
 *    required=true,
 *    description="pass make payment credentials",
 *    @OA\JsonContent(
 *       required={"transaction_id", "phone_number", "gateway", "paymentType"},
 *       @OA\Property(property="transaction_id", type="string", example="123456789101112"),
 *       @OA\Property(property="gateway", type="string", example="mtn or orange etc"),
 *       @OA\Property(property="phone_number", type="integer", example="670000000"),
 *       @OA\Property(property="paymentType", type="string", example="button"),
 *    ),
 * ),
 * @OA\Response(
 *    response=422,
 *    description="Unprocessable Entity",
 *    @OA\JsonContent(
 *       @OA\Property(property="error", type="boolean", example="true"),
 *       @OA\Property(property="status_code", type="integer", example="422"),
 *       @OA\Property(property="status", type="string", example="Unprocessable Entity"),
 *       @OA\Property(property="message", type="string", example="The given data was invalid"),
 *    )
 * ),
 * @OA\Response(
 *    response=401,
 *    description="Unauthorized",
 *    @OA\JsonContent(
 *       @OA\Property(property="error", type="boolean", example="true"),
 *       @OA\Property(property="status_code", type="integer", example="401"),
 *       @OA\Property(property="status", type="string", example="Unprocessable Entity"),
 *       @OA\Property(property="message", type="string", example="An error occurred, please try again later."),
 *    )
 * ),
 * @OA\Response(
 *    response=200,
 *    description="Success",
 *    @OA\JsonContent(
 *       @OA\Property(property="error", type="boolean", example="false"),
 *       @OA\Property(property="status_code", type="integer", example="200"),
 *       @OA\Property(property="status", type="string", example="success"),
 *       @OA\Property(property="message", type="string", example="Payment has been successfully made!"),
 *    )
 * ),
 * )
 */

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;
}
