<?php
namespace Controller;

use Model\Animal;


class AnimalController {
   
    private Animal $animalModel;

   
    public function __construct() {
        $this->animalModel = new Animal();
    }

    /**
     * Lista todos os animais dos usuários da sessão
     *
     * @return array Retorna a lista de animais cadastrados para o usuário atual
     */
    public function index(): array {
        $userId = $_SESSION['user_id'] ?? 0;
        return $this->animalModel->getAllByUserId($userId);
    }

    /**
     * Busca os dados de um animal específico pelo id e valida
     *
     * @param int $id ID do animal que está sendo consultado
     * @return array|null Retorna os dados do animal em array ou null se não encontrado ou autorizado
     */
    public function show(int $id): ?array {
        $userId = $_SESSION['user_id'] ?? 0;
        return $this->animalModel->getById($id, $userId);
    }

    /**
     * Valida o usuário logado e solicita a criação de um registro novo do animal
     *
     * @param string $nome Nome do animal
     * @param string $especie Espécie do animal
     * @param string $raca Raça do animal
     * @param int $idade Idade do animal
     * @param string $status Status atual do animal
     * @return bool Retorna true se foi cadastrado com sucesso ou false se falhar
     */
    public function store(string $nome, string $especie, string $raca, int $idade, string $status): bool {
        $userId = $_SESSION['user_id'] ?? 0;
        if ($userId === 0) return false;
        return $this->animalModel->create($nome, $especie, $raca, $idade, $status, $userId);
    }

    /**
     * Valida o usuário logado e envia as atualizações do registro
     *
     * @param int $id ID do animal 
     * @param string $nome Novo nome do animal
     * @param string $especie Nova espécie do animal
     * @param string $raca Nova raça do animal
     * @param int $idade Nova idade do animal
     * @param string $status Novo status do animal
     * @return bool Retorna true se foi atualizado com sucesso ou false se falhar
     */
    public function update(int $id, string $nome, string $especie, string $raca, int $idade, string $status): bool {
        $userId = $_SESSION['user_id'] ?? 0;
        if ($userId === 0) return false;
        return $this->animalModel->update($id, $nome, $especie, $raca, $idade, $status, $userId);
    }

    /**
     * Valida o usuário logado e solicita a exclusão de um registro do animal
     *
     * @param int $id ID do animal que vai excluir
     * @return bool Retorna true se for excluído com sucesso ou false se falhar
     */
    public function delete(int $id): bool {
        $userId = $_SESSION['user_id'] ?? 0;
        if ($userId === 0) return false;
        return $this->animalModel->delete($id, $userId);
    }
}