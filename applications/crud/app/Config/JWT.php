<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class JWT extends BaseConfig
{
    // Chave secreta para gerar os tokens
    public string $secretKey = 'apisecreta';
    
    // Tempo de expiração do token (em segundos)
    public int $tokenExpiryTime = 7200; // 1 hora
}
