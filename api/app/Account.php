<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'balance', 'currency'
    ];

    public $timestamps = false;

    /**
     * The transactions was made for this account
     */
    protected function madeTransactions()
    {
        return $this->hasMany('App\Transaction', 'from');
    }

    /**
     * The transactions was received for this account
     */
    protected function receivedTransactions()
    {
        return $this->hasMany('App\Transaction', 'to');
    }

    /**
     * All the transactions for this account
     */
    protected function transactions()
    {
        return $this->madeTransactions()->union($this->receivedTransactions())->orderBy('id', 'desc');
    }
}
