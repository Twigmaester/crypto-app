
<?php

/**
 * Requires curl enabled in php.ini
 **/



$coin = strtoupper($argv[1]); // The first paramater is the coin you want to look up
$currency = strtoupper($argv[2]); // The second paramater is the currency you want to check the coin against




$url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/latest';
$parameters = [

  'symbol' => $coin,
  'convert' => $currency
];

$headers = [
  'Accepts: application/json',
  'X-CMC_PRO_API_KEY: c1046118-7a21-4840-a5f6-4e70b10c8fa5'
];
$qs = http_build_query($parameters); // query string encode the parameters
$request = "{$url}?{$qs}"; // create the request URL


$curl = curl_init(); // Get cURL resource
// Set cURL options
curl_setopt_array($curl, array(
  CURLOPT_URL => $request,            // set the request URL
  CURLOPT_HTTPHEADER => $headers,     // set the headers 
  CURLOPT_RETURNTRANSFER => 1         // ask for raw response instead of bool
));




$curl_data = curl_exec($curl); // Send the request, save the response
$response_data = json_decode($curl_data, true); // Save response as array

$volume = number_format($response_data["data"][$coin]["quote"][$currency]["volume_24h"], $decimals = 2); // Fetch and sanitize todays volume
$price = number_format($response_data["data"][$coin]["quote"][$currency]["price"]); // Fetch and sanitize current price


echo $coin . " is currently priced at " . $price . " " . $currency . "."; // Display coin price in selected currency
echo "\nTodays volume is " . $volume . " " . $currency . ".";  // Display coin's daily volume in chosen currency






curl_close($curl); // Close request
?>


