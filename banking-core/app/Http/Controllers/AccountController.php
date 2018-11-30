<?php

namespace App\Http\Controllers;

use App\Models\Account;

class AccountController extends Controller
{
    public function accounts() {
        return [
            "status" => "ok",
            Account::all()
        ];
    }

    public function create() {
        $this->validate(request(), [
            "account" => "string|alpha_num|min:1|max:30"
        ]);

        $account = request("account");

        if (Account::query()->where("uuid", $account)->exists())
            return [
                "status" => "bad",
                "reason" => "already_exists"
            ];

        return [
            "status" => "ok",
            "result" => Account::create([
                "uuid" => $account,
                "balance" => 0
            ])
        ];
    }

    public function balance() {
        $this->validate(request(), [
            "account" => "string|exists:accounts,uuid"
        ]);

        $account = Account::query()->where("uuid", request("account"))->first();

        return [
            "status" => "ok",
            "result" => $account->balance
        ];
    }

    function change() {
        $this->validate(request(), [
            "account" => "string|exists:accounts,uuid",
        ]);

        $account = Account::query()->where("uuid", request("account"))->first();
        $account->balance += floatval(request("addition"));
        $account->save();

        return [
            "status" => "ok",
            "result" => $account->toArray()
        ];
    }
}
