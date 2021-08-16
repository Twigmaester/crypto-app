# crypto-app
Simple php command line app that displays the price of a selected crypto token in any chosen currency.

The app works with coinmarketcap's free API.

In order for the app to work you first need to enter a valid coinmarketcap API the config.php.example file and then rename the file to config.php.

The app takes two arguments.
The first is the slug of the cryptocurrency of your choosing.
And the second is the slug of a currency of your choosing.

Examples: "php crypto.php btc eur"
          "php crypto.php eth gdp"
          "php crypto.php ada usd"

You can also you can also check a tokens value in relation to another token. For example, you can check Ethereums Bitcoin value with "php crypto.php eth btc" or Cardanos Ethereum value with "php crypto.php ada eth". 
