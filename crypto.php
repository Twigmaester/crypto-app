<?php

$configsNotSet = [
  'You need to enter your coinmarketcap API key inside the "config.php.example" file.',
  'You should then rename the file to "config.php".'
];

if (!file_exists('config.php')) {
  
  printLine($configsNotSet);

  return; 

}

$configs = include('config.php');

$missingKey = [
  'You need to paste a valid coinmarketcap API key inside the config.php file.'
];

if (strlen($configs['api-key']) < 55) {

  printLine($missingKey);

  return;

}

function getCurlData($config, $url, $firstToken, $secondToken) {
    
    $curl = curl_init();

    $parameters = [
        'symbol' => $firstToken,
        'convert' => $secondToken
    ];

    $headers = [
        'Accepts: application/json',
        $config['api-key']
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

function getCoinPrice($config, $url, $firstToken, $secondToken) {

    return $price = number_format(getCurlData($config, $url, $firstToken, $secondToken)["data"][$firstToken]["quote"][$secondToken]["price"], $decimals = 2, $decimal_separator = "," , $thousands_separator = ".");

}

function getCoinVolume($config, $url, $firstToken, $secondToken) {
 
   return $volume = number_format(getCurlData($config, $url, $firstToken, $secondToken)["data"][$firstToken]["quote"][$secondToken]["volume_24h"], $decimals = 2, $decimal_separator = "," , $thousands_separator = ".");

}

function displayCoinData($config, $url, $firstToken, $secondToken) {
  
    $coinPrice = getCoinPrice($config, $url, $firstToken, $secondToken);
    $coinVolume = getCoinVolume($config, $url, $firstToken, $secondToken);

    $line = '%s currently priced at %s %s.';
    $line .= PHP_EOL;
    $line .= 'Todays volume is %s %s';
    echo sprintf($line, $firstToken, $coinPrice, $secondToken, $coinVolume, $secondToken);
}

if (!isset($argv[1]) || !isset($argv[2])) {
    print "This script needs 2 paramaters to work";
    return;
}

if (preg_match("/^[a-zA-Z]+$/", $argv[1]) === 0 &&  preg_match("/^[a-zA-Z]+$/", $argv[2]) === 0) {    
    
    printLine($lines);
    
    return;
}

$coin = strtoupper($argv[1]);
$currency = strtoupper($argv[2]);
$urlLatest = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/latest';
$lines = [
  'You need to use 2 valid strings as paramaters.',
  'The strings must not contain numbers.',
  'The first paramater is the valid slug of the token you want to check.',
  'And the second parameter is the slug of the currency in which you want the price to be displayed.',
  'The currency can be a FIAT currency, it can also be another cryptocurrency',
  'For example: \"php crypto.php btc eur\" or \"php crypto.php eth btc\"',
];

if (getCurlData($configs, $urlLatest, $coin, $currency)["status"]["error_code"] !== 0) {
    
    printLine($lines);
    
    return;
} 

displayCoinData($configs, $urlLatest, $coin, $currency);




