<?php


if(!isset($argv[1]) && !isset($argv[2])) {
 print "This script needs 2 paramaters to work";
 exit;
} elseif (preg_match("/^[a-zA-Z]+$/", $argv[1]) &&  preg_match("/^[a-zA-Z]+$/", $argv[2])) {
  
  $coin = strtoupper($argv[1]);
  $currency = strtoupper($argv[2]);

  $curl = curl_init();

  $url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/latest';
  $parameters = [
  
    'symbol' => $coin,
    'convert' => $currency
  ];

  $headers = [
    'Accepts: application/json',
    'X-CMC_PRO_API_KEY: c1046118-7a21-4840-a5f6-4e70b10c8fa5'
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

  if ($response_data["status"]["error_code"] === 0) {

    $volume = number_format($response_data["data"][$coin]["quote"][$currency]["volume_24h"], $decimals = 2, $decimal_separator = "," , $thousands_separator = ".");
    $price = number_format($response_data["data"][$coin]["quote"][$currency]["price"], $decimals = 2, $decimal_separator = "," , $thousands_separator = ".");

    $format = "$coin is currently price at $price $currency.\nTodays volume is $volume $currency";
    echo sprintf($format);

    curl_close($curl);

     exit;
  } else {
    print "You need to use 2 valid strings as paramaters.";
    print "\nThe strings must not contain numbers.";
    print "\nThe first paramater is the valid slug of the token you want to check.";
    print "\nAnd the second parameter is the slug of the currency in which you want the price to be displayed.";
    print "\nThe currency can be a FIAT currency, it can also be another cryptocurrency";
    print "\nFor example:";
    print " \"php crypto.php btc eur\" or \"php crypto.php eth btc\"";
  }
} else {
  print "You need to use 2 valid strings as paramaters.";
  print "\nThe strings must not contain numbers.";
  print "\nThe first paramater is the valid slug of the token you want to check.";
  print "\nAnd the second parameter is the slug of the currency in which you want the price to be displayed.";
  print "\nThe currency can be a FIAT currency, it can also be another cryptocurrency";
  print "\nFor example:";
  print " \"php crypto.php btc eur\" or \"php crypto.php eth btc\"";
 
    exit;
}


 


