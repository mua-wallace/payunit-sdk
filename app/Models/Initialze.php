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
    // public $transaction_id;
    /**
     * $total_amount
     *
     * @OA\Property(property ="total_amount",type="integer",description="The amount to be paid")
     *
     * @var string
     */
    // public $total_amount;
    /**
     * $currency
     *
     * @OA\Property(property ="currency",type="string",description="The currency you want to use: XAF")
     *
     * @var string
     */
    // public $currency;
    /**
     * $name
     *
     * @OA\Property(property ="name",type="string",description="Name of the merchant")
     *
     * @var string
     */
    // public $name;
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