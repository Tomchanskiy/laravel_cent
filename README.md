# tomchanio / laravel_cent
Centrifugo (Centrifuge) [2.0+] PHP Server HTTP API implementation for Laravel 5.5+

## Base Installation
1. Run `composer require tomchanio/laravel_cent` & `composer update`
2. Create `config/centrifugo.php` as provided below
3. Add alias in `config/app.php` as provided below

## Config example `config/centrifugo.php`
```php
<?php
    return [
        'baseUrl'         => 'http://localhost:8000/api/', // full api url
        'secret'          => 'skoniksnyashamoyanikamuneotdam', // you super secret key
        'apikey'          => 'skoniksnyashamoyanikamuneotdam', // you api key
    ];

```

## Alias additions `config/app.php`
```php
    'aliases' => [
        ...
        'Centrifugo'=> Tomchanio\Centrifugo\Centrifugo::class,
    ]
    
```



## [Module usage || sending your requests] example
```php
<?php
use Centrifugo;

class Controller
{
    public function your_func()
    {
        // declare Centrifugo
        $Centrifugo = new Centrifugo();

        // generating token example
        $userid = '1337_1448_228';
        $info = ['token' => '123'];
        $token = $Centrifugo->generateToken($userid, $info);
        // publishing example
        $Centrifugo->publish("channel" , ["yout text or even what rou want"]);
        
        // each method returns its response; 
        // list of awailible methods: 
        $response = $Centrifugo->publish($channle, $messageData);
        $response = $Centrifugo->broadcast($channles, $messageData);
        $response = $Centrifugo->unsubscribe($channle, $userId);
        $response = $Centrifugo->disconnect($userId);
        $response = $Centrifugo->presence($channle);
        $response = $Centrifugo->presence_stats($channle);
        $response = $Centrifugo->history($channle);
        $response = $Centrifugo->history_remove($channle);
        $response = $Centrifugo->channels();
        $response = $Centrifugo->info();
        $response = $Centrifugo->generateToken($user);
        
        // You can create a controller to bild your own interface;
    }
```
### For more information go [here](https://centrifugal.github.io/centrifugo/)
