<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;


 /**
     * @OA\Post(
     * path="/api/initialize",
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
     *       @OA\Property(property="currency", type="string", format="string", example="XAF"),
     *       @OA\Property(property="return_url", type="string", format="string", example="http://localhost:4000"),
     *       @OA\Property(property="name", type="string", format="string", example="mtn"),
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
     *    description="Success",
     *    @OA\JsonContent(
     *       @OA\Property(property="error", type="boolean", example="false"),
     *       @OA\Property(property="status_code", type="integer", example="200"),
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="message", type="string", example="Transaction  has been successfully initiated!"),
     *    )
     * ),
     * )
     */

class LoginController extends Controller
{


    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
