<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Initialze extends Model
{
    use HasFactory;
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'initializer';
    protected $fillable = ['transaction_id','total_amount', 'currency', 'return_url', 'notify_url', 'name', 'description', 'purchaseRef' ];

    

    public $timestamps = false;
}
