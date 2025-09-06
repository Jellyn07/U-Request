<?php
// filepath: c:\xampp\htdocs\U--Request\app\config\encryption.php

define('ENCRYPT_METHOD', 'aes-256-cbc');
define('SECRET_KEY', '12345678901234567890123456789012');
define('SECRET_IV', '1234567890123456');

function encrypt($string) {
    return base64_encode(openssl_encrypt($string, ENCRYPT_METHOD, SECRET_KEY, 0, SECRET_IV));
}

function decrypt($string) {
    return openssl_decrypt(
        base64_decode($string),
        ENCRYPT_METHOD,
        SECRET_KEY,
        0,
        SECRET_IV
    );
}