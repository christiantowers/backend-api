<?php

namespace App\Controllers;

use Config\Services;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

class Product extends ResourceController
{
    protected $modelName = 'App\Models\ProductModel';
    protected $format = 'json';

    // Retorna todos os produtos com cache
    public function showAll()
    {
        $redis = Services::cache();

        // Verifica se os produtos já estão armazenados em cache
        $cachedProducts = $redis->get("products_all");

        if ($cachedProducts) {
            return $this->respond(json_decode($cachedProducts, true));
        }

        // Se não estiver em cache, busca no banco
        $products = $this->model->findAll();

        if (!$products) {
            return $this->failNotFound('Nenhum produto encontrado');
        }

        // Armazena no cache por 10 minutos
        $redis->save("products_all", json_encode($products), 600);

        return $this->respond($products);
    }

    // Retorna um produto específico sem cache
    public function show($id = null)
    {
        if (!$id) {
            return $this->failNotFound('ID do produto não fornecido');
        }

        // Busca diretamente no banco sem cache
        $product = $this->model->find($id);

        if (!$product) {
            return $this->failNotFound('Produto não encontrado');
        }

        return $this->respond($product);
    }

    // Criar produto
    public function create()
    {
        $data = $this->request->getJSON(true);

        $this->model->insert($data);

        // Limpa o cache de todos os produtos
        Services::cache()->delete("products_all");

        return $this->respondCreated(['message' => 'Produto cadastrado com sucesso']);
    }

    // Atualizar produto
    public function update($id = null)
    {
        if (!$id) {
            log_message('error', 'Falha no update: ID não fornecido.');
            return $this->fail('ID do produto não fornecido', 400);
        }

        // Verifica se o produto existe
        if (!$this->model->find($id)) {
            log_message('error', "Falha no update: Produto com ID {$id} não encontrado.");
            return $this->failNotFound('Produto não encontrado');
        }

        // Obtém os dados enviados na requisição
        $data = $this->request->getJSON(true);

        try {
            if ($this->model->update($id, $data)) {
                Services::cache()->delete("products_all"); // Limpa o cache
                log_message('info', "Produto com ID {$id} atualizado com sucesso.");
                return $this->respond(['message' => 'Produto atualizado com sucesso']);
            }

            return $this->fail('Falha ao atualizar o produto');
        } catch (\Exception $e) {
            log_message('critical', "Exceção no update do ID {$id}: " . $e->getMessage());
            return $this->failServerError('Erro interno ao atualizar produto.');
        }
    }




    // Excluir produto
    public function delete($id = null)
    {
        if ($this->model->delete($id)) {
            Services::cache()->delete("products_all");
            return $this->respondDeleted(['message' => 'Produto excluído com sucesso']);
        }
        return $this->failNotFound('Produto não encontrado');
    }
}
