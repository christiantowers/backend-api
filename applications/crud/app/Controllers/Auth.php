<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;
use Config\Services;

class Auth extends ResourceController
{
    public function login()
    {
        $data = $this->request->getJSON();
   
        // Verifique se o email e a senha foram enviados
        if (empty($data->email) || empty($data->password)) {
            return $this->failValidationErrors(['email' => 'Email é necessário.', 'password' => 'Senha é necessária.']);
        }

        // Valide as credenciais do usuário (isso é apenas um exemplo, você pode ajustar conforme necessário)
        $userModel = new UserModel();
        $user = $userModel->where('email', $data->email)->first();

        if ($user) {
            // Gerar o JWT
            $jwt = generate_jwt(['id' => $user['id'], 'email' => $user['email']]);

            return $this->respond(['token' => $jwt]);
        } else {
            return $this->failUnauthorized('Credenciais inválidas.');
        }
    }
}

