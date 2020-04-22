<?php

namespace App\Http\Controllers;

use App\Account;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class AccountController extends Controller
{
    /**
     * Create an Account
     *
     * @bodyParam name string required The name of the owner. Example: Matt
     * @bodyParam currency string required The ID of the account. Example: USD
     * @response 201{
     *  "id": 4,
     *  "message": "Welcome Matt!. Your Account ID is 4"
     * }
     * @response 422 {
     *  "name": "The name must be at least 3 characters.",
     *  "currency": "The selected currency is invalid."
     * }
     */
    public function create(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:3',
            'currency' => 'required|in:USD,EUR',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        try {
            $account = new Account();
            $account->name = $request->name;
            $account->balance = 0;
            $account->currency = $request->currency;
            $account->save();
            return response()->json([
                    'id' => $account->id,
                    'message' => "Welcome {$account->name}!. Your Account ID is {$account->id}",
                ],
                201
            );
        } catch (\Exception $e) {
            return response()->json(
                ['message' => 'The account could not be created'],
                409
            );
        }
    }

    /**
     * Display the account
     *
     * @urlParam id required The id of the account. Example: 1
     * @response {
     *  "id": 4,
     *  "name": "Matt",
     *  "balance": 14000,
     *  "currency": "USD"
     * }
     * @response 404{
     *  "message": "Not Found!"
     * }
     */
    public function show(Account $account)
    {
        return response()->json($account);
    }

    /**
     * Display all the transactions
     *
     * @urlParam id required The id of the account. Example: 1
     * @response [
     *  {
     *      "id": 3,
     *      "from": 2,
     *      "to": 1,
     *      "details": "sample transaction 3",
     *      "amount": "15"
     *  },
     *  {
     *      "id": 2,
     *      "from": 1,
     *      "to": 2,
     *      "details": "sample transaction 2",
     *      "amount": "24"
     *  },
     *  {
     *      "id": 1,
     *      "from": 1,
     *      "to": 2,
     *      "details": "sample transaction",
     *      "amount": "14"
     *  }
     *  ]
     * @response 404{
     *  "message": "Not Found!"
     * }
     */
    public function getTransactions(Account $account)
    {
        return response()->json($account->transactions);
    }

    /**
     * Make a transactions
     *
     * @urlParam id required The id of the account. Example: 1
     * @bodyParam to number required The id of the destination account. Example: 2
     * @bodyParam amount number required The amount to be transfered. Example: 500
     * @bodyParam details string required The detail of the transactions. Example: Sample transactions of $500
     * @response 201{
     *  "message": "Transaction was successful"
     * }
     * @response 422{
     *    "to": [
     *      "The to field is required."
     *    ],
     *    "amount": [
     *      "The amount field is required."
     *    ]
     *  }
     * @response 422{
     *    "to": [
     *      "The destination account does not exists"
     *    ]
     *  }
     * @response 409 {
     *  "message": "The amount to transfer exceeds your balance"
     * }
     * @response 409 {
     *   "message": "The destination account does not had the same currency of your account"
     * }
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
        if ($account->id == $request->to) {
            return response()->json(['message' => 'The destination account can not be the source account'], 422);
        }
        if ($account->balance - $request->amount >= 0) {
            try {
                $toAccount = Account::findOrFail($request->to);
                if ($toAccount->currency != $account->currency) {
                    return response()->json(
                        ['message' => 'The destination account does not had the same currency of your account'],
                        409
                    );
                }
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
                return response()->json(['message' => 'Transaction was successful'], 201);
            } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
                return response()->json(
                    ['message' => 'The destination account does not exist'],
                    409
                );
            }
        } else {
            return response()->json(
                ['message' => 'The amount to transfer exceeds your balance'],
                409
            );
        }
    }
}
