<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class InitialzedData extends Model
{
    use HasFactory;


    protected $table = 'initializedData';
    protected $fillable = ['t_url','t_id', 't_sum', 'transaction_id'];
    public $appends = ['initialize'];

    public $timestamps = false;

    public function initialize() {
        return $this->hasOne(Initialze::class, 'transaction_id', 'transaction_id')->first();
    }

    public function getInitializeAttribute()
    {
        
        return $this->initialize();
    }

}
