<?php

namespace App\Http\Controllers;

use App\Account;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


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
        $validator = Validator::make($request->all(), [
            'to' => 'required|exists:accounts,id',
            'amount' => 'required|numeric|min:1',
            'details' => 'sometimes|string',
        ],
        [
            'to.exists' => 'The destination account does not exists'
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        if ($account->balance - $request->amount >= 0) {
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
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                return response()->json('The destination account does not exist', 409);
            }
        } else {
            return response()->json('The amount to transfer exceeds your balance', 422);
        }

    }
}
