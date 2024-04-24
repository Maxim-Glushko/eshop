<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylorotwell@gmail.com>
 */


/*$string = 'a:4:{s:6:"_token";s:40:"6KYVPl5tUSUFJTFWcJN9sBuZdfyF1leADeTgar15";s:9:"_previous";a:1:{s:3:"url";s:110:"http://www.dteam.com.ua/accessories/colour=navy:black;sizes=l:xl:adult:shoesize4:shoesize1:teenager;type=child";}s:9:"_sf2_meta";a:3:{s:1:"u";i:1623020903;s:1:"c";i:1623020903;s:1:"l";s:1:"0";}s:5:"flash";a:2:{s:3:"old";a:0:{}s:3:"new";a:0:{}}}';
print_r(unserialize($string));
exit();*/

/*$json = [
    '_token' => '6KYVPl5tUSUFJTFWcJN9sBuZdfyF1leADeTgar15',
    '_previous' => [
        'url' => 'http://www.dteam.com.ua/accessories/colour=navy:black;sizes=l:xl:adult:shoesize4:shoesize1:teenager;type=child',
    ],
    '_sf2_meta' => [
        'u' => 1623020903,
        'c' => 1623020903,
        'l' => 0
    ],
    'flash' => [
        'old' => [],
        'new' => []
    ]
];*/


/*
$ip = $_SERVER['REMOTE_ADDR'];
$ua = $_SERVER['HTTP_USER_AGENT'];
file_put_contents ('info.txt', 'ip: ' . $ip . '; userAgent: ' . $ua . "\n", FILE_APPEND);
*/

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels nice to relax.
|
*/

require __DIR__.'/../bootstrap/autoload.php';

/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| We need to illuminate PHP development, so let us turn on the lights.
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight our users.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);
