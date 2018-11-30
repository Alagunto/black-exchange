<?php

$router->get('/', function () use ($router) {
    return [
        "version" => "1.0.0",
        "module" => "exchange-user-api"
    ];
});

$router->get('/placed-secrets', "ExchangeController@getPlacedSecrets");
$router->post('/place-secret', "ExchangeController@place");
$router->post('/buy-secret', "ExchangeController@buy");

$router->post('/free_coin', "FreeCoinController@receiveFreeCoin");

$router->post("/register", "AccountsController@register");
$router->get("/balance", "AccountsController@balance");
