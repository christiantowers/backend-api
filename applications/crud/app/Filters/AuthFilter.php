<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use \Config\Services;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $authHeader = $request->getHeaderLine('Authorization');
        if (empty($authHeader)) {
            return Services::response()->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED)->setJSON([
                'error' => 'Token não fornecido.'
            ]);
        }

        $matches = [];
        if (preg_match('/Bearer (.+)/', $authHeader, $matches)) {
            $jwt = $matches[1];
        } else {
            return Services::response()->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED)->setJSON([
                'error' => 'Token malformado.'
            ]);
        }

        $userData = decode_jwt($jwt);

        if (!$userData) {
            return Services::response()->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED)->setJSON([
                'error' => 'Token inválido.'
            ]);
        }

        // Passa os dados do usuário para o request
        $request->userData = $userData;

        return true; // Indica que a requisição pode continuar
    }


    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Não há necessidade de tratamento após a execução da ação
    }
}

