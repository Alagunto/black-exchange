<?php

namespace App\Http\Controllers;

use App\Models\Secret;
use Illuminate\Pagination\Paginator;

class SecretsController extends Controller
{
    public function placeSecret()
    {
        $service = app(\App\Services\BankingService::class);

        $account = request("account");

        if ($service->balance($account) === false) {
            return [
                "status" => "bad",
                "reason" => "account_not_found"
            ];
        }

        $secret = request("secret");
        $description = request("description");
        $price = min(3.0, request("price"));

        Secret::create([
            "secret" => $secret,
            "description" => $description,
            "owner_account" => $account,
            "price" => $price
        ]);

        return [
            "status" => "ok",
            "result" => ["added_for_price", $price]
        ];
    }

    public function getPlacedSecrets()
    {
        return [
            "status" => "ok",
            "result" => Secret::query()->orderBy("id", "DESC")->paginate(10)
        ];
    }

    public function buySecret()
    {
        $secret_id = request("secret_id");
        $account = request("account");

        $service = app(\App\Services\BankingService::class);
        $balance = $service->balance($account);
        if ($balance === false) {
            return [
                "status" => "bad",
                "reason" => "account_not_found"
            ];
        }

        $secret = Secret::find($secret_id);
        if (is_null($secret))
            return [
                "status" => "bad",
                "reason" => "secret_not_found"
            ];

        if ($balance >= $secret->price) {
            $service->change($account, -$secret->price);
            return [
                "status" => "ok",
                "result" => [
                    "meta" => $secret,
                    "secret" => $secret->secret,
                ]
            ];
        }

        return [
            "status" => "bad",
            "reason" => "insufficient_funds"
        ];
    }
}
