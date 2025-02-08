<?php

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Config\JWT as JWTConfig;

if (! function_exists('generate_jwt')) {
    function generate_jwt($data)
    {
        $issuedAt = time();
        $expirationTime = $issuedAt + (new JWTConfig())->tokenExpiryTime;
        $payload = array(
            'iat' => $issuedAt,
            'exp' => $expirationTime,
            'data' => $data
        );

        // Defina o algoritmo de assinatura 'HS256' no terceiro argumento
        $jwt = JWT::encode($payload, (new JWTConfig())->secretKey, 'HS256');
        return $jwt;
    }
}

if (! function_exists('decode_jwt')) {
    function decode_jwt($jwt)
    {
        try {
            $decoded = JWT::decode($jwt, new Key((new JWTConfig())->secretKey, 'HS256'));
    
            return (array) $decoded->data;
        } catch (\Firebase\JWT\ExpiredException $e) {
            var_dump('Token expirado');
            return null;
        } catch (\Exception $e) {  // Corrigido para capturar corretamente a exceção
            var_dump('Erro na decodificação: ' . $e->getMessage());
            return null;
        }
    }
}
