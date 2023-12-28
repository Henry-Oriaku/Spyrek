<?php

use src\Core\Auth;
use src\Core\Request;
use src\Core\Response;
use src\Core\Route;

function show(...$msg)
{
    echo ('<pre>');
    print_r($msg);
    echo ('</pre>');
}

function esc($str)
{
    return htmlspecialchars($str);
}
function redirect($path)
{
    header("Location: " . ROOT . "/" . $path);
    die;
}



function encrypt($original, $enc_key)
{
    $key = hex2bin($enc_key);
    $nonceSize = openssl_cipher_iv_length("aes-256-ctr");
    $nonce = openssl_random_pseudo_bytes($nonceSize);

    $ciphertext = openssl_encrypt(
        $original,
        "aes-256-ctr",
        $key,
        OPENSSL_RAW_DATA,
        $nonce
    );

    //var_dump(base64_encode($nonce)) or die('error');
    return base64_encode($nonce . $ciphertext);
}

function decrypt($encrypted, $enc_key)
{
    $key = hex2bin($enc_key);

    $message = base64_decode($encrypted);
    $nonceSize = openssl_cipher_iv_length("aes-256-ctr");
    $nonce = mb_substr($message, 0, $nonceSize, "8bit");
    $cipherText = mb_substr($message, $nonceSize, null, "8bit");

    $plainText = openssl_decrypt($cipherText, "aes-256-ctr", $key, OPENSSL_RAW_DATA, $nonce);

    return $plainText;
}


/** get the logged in user data 
 * @return array|bool The user details
 */
function getLoggedInUser()
{
    return $_SESSION['USER'] ?? false;
}

/** Save the logged in user data */
function setLoggedInUser($data)
{
    $_SESSION['USER'] = $data;
}

/** Log The User Out */
function logoutUser()
{
    if (!empty($_SESSION['USER']))
        unset($_SESSION['USER']);
}

function request()
{
    return new Request();
}
function response()
{
    return new Response();
}
function auth()
{
    return new Auth();
}
function route()
{
    return new Route();
}
function logger($message)
{
    $logger_path = __DIR__ .'/../../logs/easidev.log';
    $file = fopen($logger_path, "w+");
    fwrite($file, "\n".$message);
}
