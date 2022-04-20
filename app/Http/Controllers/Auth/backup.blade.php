// forgotPassword
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
 *       @OA\Property(property="transaction_id", type="string", example="233333332323234"),
 *       @OA\Property(property="gateway", type="string", example="mtnmomo or orange etc"),
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



/**
     * @OA\Post(
     * path="/api/initialize",
     * summary="Initialize payment",
     * 
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
     *       type="object", ref="#/components/schemas/Initialze",
     *       @OA\Property(property="error", type="boolean", example="false"),
     *       @OA\Property(property="status_code", type="integer", example="200"),
     *       @OA\Property(property="status", type="string", example="success"),
     *       @OA\Property(property="message", type="string", example="Transaction  has been successfully initiated!"),
     *    )
     *   
     *
     * ),
     * )
     */

    <?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema()
*/

class Initialze extends Model
{
    use HasFactory;

    /**
     * $transaction_id
     * @OA\Property(property ="transaction_id",type="string",description="This transaction should be unique per request and it should   be a string  This id should be alphanumeric and less than 20 characters")
     *
     * @var string
     */
    
    public $transaction_id;

    /**
     * $total_amount
     * 
     * @OA\Property(property ="total_amount",type="integer",description="The amount to be paid")
     * 
     * @var string
     */
    public $total_amount;

    /**
     * $currency
     * 
     * @OA\Property(property ="currency",type="string",description="The currency you want to use: XAF")
     * 
     * @var string
     */
    public $currency;

    /**
     * $name
     * 
     * @OA\Property(property ="name",type="string",description="Name of the merchant")
     * 
     * @var string
     */
    public $name;

    /**
     * $description
     * 
     * @OA\Property(property ="description",type="mediumText",description="A description you can give to this type of  transaction")
     * 
     * @var string
     */
    public $description;

    

   

    

    protected $table = 'initializer';
    protected $fillable = ['transaction_id','total_amount', 'currency', 'return_url', 'notify_url', 'name', 'description', 'purchaseRef' ];
}


// Schema::create('initializer', function (Blueprint $table) {
//     $table->id();
//     $table->string('transaction_id')->unique();
//     $table->integer('total_amount');
//     $table->string('name')->nullable();
//     $table->mediumText('description')->nullable();
//     $table->mediumText('return_url');
//     $table->mediumText('notify_url')->nullable();
//     $table->mediumText('purchaseRef')->nullable();
//     $table->string('currency');
//     $table->timestamps();
// });
