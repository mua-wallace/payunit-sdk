<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InitialzedData extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'initializedData';
    protected $fillable = ['t_url','t_id', 't_sum', 'transaction_id'];

    public $timestamps = false;

}
