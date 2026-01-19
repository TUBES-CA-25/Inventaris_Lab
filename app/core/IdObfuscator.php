<?php

class IdObfuscator
{
    public static function encode($id)
    {
        if (empty($id)) {
            return $id;
        }
        $encrypted = openssl_encrypt($id, 'aes-256-cbc', ID_ENCRYPTION_KEY, 0, ID_ENCRYPTION_IV);
        return str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($encrypted));
    }

    public static function decode($hash)
    {
        if (empty($hash)) {
            return $hash;
        }
        $data = str_replace(['-', '_'], ['+', '/'], $hash);
        $decrypted = openssl_decrypt(base64_decode($data), 'aes-256-cbc', ID_ENCRYPTION_KEY, 0, ID_ENCRYPTION_IV);
        
        return $decrypted !== false ? $decrypted : false;
    }
}
