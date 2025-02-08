<?php

namespace App\Controllers;

use Config\Services;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class User extends ResourceController
{
    protected $modelName = 'App\Models\UserModel';
    protected $format = 'json';

    // Retorna todos os usuários com cache
    public function showAll()
    {
        $redis = Services::cache();

        // Verifica se os usuários já estão armazenados em cache
        $cachedUsers = $redis->get("users_all");

        if ($cachedUsers) {
            return $this->respond(json_decode($cachedUsers, true));
        }

        // Se não estiver em cache, busca no banco
        $users = $this->model->findAll();

        if (!$users) {
            return $this->failNotFound('Nenhum usuário encontrado');
        }

        // Armazena no cache por 10 minutos
        $redis->save("users_all", json_encode($users), 600);

        return $this->respond($users);
    }

    // Retorna um usuário específico sem cache
    public function show($id = null)
    {
        if (!$id) {
            return $this->failNotFound('ID do usuário não fornecido');
        }

        // Busca diretamente no banco sem cache
        $user = $this->model->find($id);

        if (!$user) {
            return $this->failNotFound('Usuário não encontrado');
        }

        return $this->respond($user);
    }

    // Criar usuário
    public function create()
    {
        $data = $this->request->getJSON(true);
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        $this->model->insert($data);

        // Limpa o cache de todos os usuários
        Services::cache()->delete("users_all");

        return $this->respondCreated(['message' => 'Usuário cadastrado com sucesso']);
    }

    // Atualizar usuário
    public function update($id = null)
    {
        $data = $this->request->getJSON(true);
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

        if ($this->model->update($id, $data)) {
            $redis = Services::cache();
            $redis->delete("users_all"); // Remove cache geral
            return $this->respond(['message' => 'Usuário atualizado com sucesso']);
        }
        return $this->fail('Falha ao atualizar o usuário');
    }

    // Excluir usuário
    public function delete($id = null)
    {
        if ($this->model->delete($id)) {
            Services::cache()->delete("users_all");
            return $this->respondDeleted(['message' => 'Usuário excluído com sucesso']);
        }
        return $this->failNotFound('Usuário não encontrado');
    }
}
