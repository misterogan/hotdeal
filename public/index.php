<?php
/*
if(array_key_exists('REMOTE_ADDR' , $_SERVER)){	
    if($_SERVER['REMOTE_ADDR'] == '52.89.130.89' || $_SERVER['HTTP_X_FORWARDED_FOR'] ==  '52.41.247.32' || $_SERVER['HTTP_X_FORWARDED_FOR'] ==  '52.11.161.195'){

    }else{
        session_start();
        if( !isset( $_SESSION['auth_header'] ) ){
        $valid_passwords = array ("hotdealindonesia" => "hotdeal202201");
        $valid_users = array_keys($valid_passwords);

        $user = $_SERVER['PHP_AUTH_USER'];
        $pass = $_SERVER['PHP_AUTH_PW'];

        $validated = (in_array($user, $valid_users)) && ($pass == $valid_passwords[$user]);

        if (!$validated) {
            header('WWW-Authenticate: Basic realm="HOTDEAL"');
            header('HTTP/1.0 401 Unauthorized');
            die ("Not authorized");
            unset($_SESSION['auth_header']);
        }else{
            $_SESSION['auth_header'] = 1;
        }
        }
    }
    
}
*/
// if(array_key_exists('REMOTE_ADDR' , $_SERVER)){

//     if($_SERVER['REMOTE_ADDR'] == '52.89.130.89' || $_SERVER['REMOTE_ADDR'] ==  '52.41.247.32' || $_SERVER['REMOTE_ADDR'] ==  '52.11.161.195'){

//     }else{
//         session_start();
//         if( !isset( $_SESSION['auth_header'] ) ){
//         $valid_passwords = array ("hotdealindonesia" => "hotdeal202201");
//         $valid_users = array_keys($valid_passwords);

//         $user = $_SERVER['PHP_AUTH_USER'];
//         $pass = $_SERVER['PHP_AUTH_PW'];

//         $validated = (in_array($user, $valid_users)) && ($pass == $valid_passwords[$user]);

//         if (!$validated) {
//             header('WWW-Authenticate: Basic realm="Modoc partner"');
//             header('HTTP/1.0 401 Unauthorized');
//             die ("Not authorized");
//             unset($_SESSION['auth_header']);
//         }else{
//             $_SESSION['auth_header'] = 1;
//         }
//         }
//     }
    
// }
/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */

define('LARAVEL_START', microtime(true));

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels great to relax.
|
*/

require __DIR__.'/../vendor/autoload.php';

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
