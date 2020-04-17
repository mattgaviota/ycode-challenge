<?php

namespace App\Http\Controllers;

use App\Account;
use App\Transaction;
use Illuminate\Http\Request;

class AccountController extends Controller
{
    /**
     * Display the account
     *
     * @param  \App\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function show(Account $account)
    {
        return response()->json($account);
    }

    /**
     * Display all the transactions
     *
     * @param  \App\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function getTransactions(Account $account)
    {
        return response()->json($account->transactions);
    }

    /**
     * Make a transactions
     *
     * @param  \App\Account  $account
     * @return \Illuminate\Http\Response
     */
    public function makeTransaction(Request $request, Account $account)
    {
        try {
            $toAccount = Account::findOrFail($request->to);
            $toAccount->balance += $request->amount;
            $account->balance -= $request->amount;
            $toAccount->save();
            $account->save();
            $transaction = new Transaction();
            $transaction->from = $account->id;
            $transaction->to = $toAccount->id;
            $transaction->details = $request->details;
            $transaction->amount = $request->amount;
            $transaction->save();
            return response()->json('Success');
        } catch (\Exception $e) {
            dd($e);
        }

    }
}
