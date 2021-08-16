<?php

function getCurlData($url) {
    
    $configs = include('config.php');

    global $coin;
    global $currency;

    $curl = curl_init();

    $parameters = [
        'symbol' => $coin,
        'convert' => $currency
    ];

    $headers = [
        'Accepts: application/json',
        $configs[0]
    ];

    $qs = http_build_query($parameters);

    $request = "{$url}?{$qs}";

    curl_setopt_array($curl, array(
        CURLOPT_URL => $request,
        CURLOPT_HTTPHEADER => $headers,
        CURLOPT_RETURNTRANSFER => 1
    ));

    $curl_data = curl_exec($curl);
    $response_data = json_decode($curl_data, true);
    return $response_data;
        

    curl_close($curl);
}

function printLine($msgs) {
    foreach($msgs as $msg) {
        echo $msg . PHP_EOL;
    }
} 

function getCoinPrice() {
    global $coin;
    global $currency;
    global $urlLatest;
    return $price = number_format(getCurlData($urlLatest)["data"][$coin]["quote"][$currency]["price"], $decimals = 2, $decimal_separator = "," , $thousands_separator = ".");
}

function getCoinVolume() {
   global $coin;
   global $currency;
   global $urlLatest;

   return $volume = number_format(getCurlData($urlLatest)["data"][$coin]["quote"][$currency]["volume_24h"], $decimals = 2, $decimal_separator = "," , $thousands_separator = ".");
}

function displayCoinData() {
    global $coin;
    global $currency;

    $coinPrice = getCoinPrice();
    $coinVolume = getCoinVolume();

    $line = '%s currently priced at %s %s.';
    $line .= PHP_EOL;
    $line .= 'Todays volume is %s %s';
    echo sprintf($line, $coin, $coinPrice, $currency, $coinVolume, $currency);
}

if (!isset($argv[1]) || !isset($argv[2])) {
    print "This script needs 2 paramaters to work";
    return;
}

if (preg_match("/^[a-zA-Z]+$/", $argv[1]) === 0 &&  preg_match("/^[a-zA-Z]+$/", $argv[2]) === 0) {    
    $lines = [
        "You need to use 2 valid strings as paramaters.",
        "The strings must not contain numbers.",
        "The first paramater is the valid slug of the token you want to check.",
        "And the second parameter is the slug of the currency in which you want the price to be displayed.",
        "The currency can be a FIAT currency, it can also be another cryptocurrency",
        "For example: \"php crypto.php btc eur\" or \"php crypto.php eth btc\""
    ];
    
    printLine($lines);
    
    return;
}

$coin = strtoupper($argv[1]);
$currency = strtoupper($argv[2]);
$urlLatest = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/latest';

if (getCurlData($urlLatest)["status"]["error_code"] !== 0) {
    $lines = [
        "You need to use 2 valid strings as paramaters.",
        "The strings must not contain numbers.",
        "The first paramater is the valid slug of the token you want to check.",
        "And the second parameter is the slug of the currency in which you want the price to be displayed.",
        "The currency can be a FIAT currency, it can also be another cryptocurrency",
        "For example: \"php crypto.php btc eur\" or \"php crypto.php eth btc\""
    ];
    
    printLine($lines);
    
    return;
} 

displayCoinData();









