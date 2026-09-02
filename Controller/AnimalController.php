<?php
namespace Controller;

use Model\Animal;

class AnimalController {
   
    private Animal $animalModel;

   
    public function __construct() {
        $this->animalModel = new Animal();
    }

    /**
     * @return array lista de animais cadastrados para o usuário atual
     */
    public function index(): array {
        $userId = $_SESSION['user_id'] ?? 0;
        if ($userId <= 0) {
            return [];
        }
        return $this->animalModel->getAllByUserId($userId);
    }

    /**
     * mostra os animais com status Disponível
     * 
     * @return array a lista de animais para adoção
     */
    public function disponiveis(): array {
        $userId = $_SESSION['user_id'] ?? 0;
        if ($userId <= 0) {
            return [];
        }
        return $this->animalModel->getDisponiveisByUserId($userId);
    }

    /**
     * Busca os dados de um animal específico pelo id 
     *
     * @param int $id ID do animal 
     * @return array|null Retorna os dados do animal em array ou null se não encontrado ou autorizado
     */
    public function show(int $id): ?array {
        $userId = $_SESSION['user_id'] ?? 0;
        if ($id <= 0 || $userId <= 0) {
            return null;
        }
        return $this->animalModel->getById($id, $userId);
    }

    /**
     * Valida o usuário logado e solicita a criação de um registro novo do animal
     *
     * @param string $nome Nome do animal
     * @param string $especie Espécie do animal
     * @param string $raca Raça do animal
     * @param float $idade Idade do animal 
     * @param string $status Status atual do animal
     * @param string|null $foto Nome do arquivo da imagem
     * @return bool Retorna true se foi cadastrado com sucesso ou false se falhar
     */
    public function store(string $nome, string $especie, string $raca, float $idade, string $status, ?string $foto = null): bool {
        $userId = $_SESSION['user_id'] ?? 0;
        if ($userId === 0) return false;

        $nome = trim(filter_var($nome, FILTER_SANITIZE_SPECIAL_CHARS));
        $especie = trim(filter_var($especie, FILTER_SANITIZE_SPECIAL_CHARS));
        $raca = trim(filter_var($raca, FILTER_SANITIZE_SPECIAL_CHARS));
        $status = trim(filter_var($status, FILTER_SANITIZE_SPECIAL_CHARS));

        if (empty($nome) || empty($especie) || empty($raca) || empty($status) || $idade < 0) {
            return false;
        }

        return $this->animalModel->create($nome, $especie, $raca, $idade, $status, $foto, $userId);
    }

    /**
     * Valida o usuário logado e envia as atualizações do registro
     *
     * @param int $id ID do animal
     * @param string $nome  nome do animal
     * @param string $especie espécie do animal
     * @param string $raca raça do animal
     * @param float $idade  idade do animal 
     * @param string $status  status do animal
     * @param string|null $foto arquivo de imagem 
     * @return bool Retorna true se foi atualizado com sucesso ou false se falhar
     */
    public function update(int $id, string $nome, string $especie, string $raca, float $idade, string $status, ?string $foto = null): bool {
        $userId = $_SESSION['user_id'] ?? 0;
        if ($userId === 0 || $id <= 0) return false;

        $nome = trim(filter_var($nome, FILTER_SANITIZE_SPECIAL_CHARS));
        $especie = trim(filter_var($especie, FILTER_SANITIZE_SPECIAL_CHARS));
        $raca = trim(filter_var($raca, FILTER_SANITIZE_SPECIAL_CHARS));
        $status = trim(filter_var($status, FILTER_SANITIZE_SPECIAL_CHARS));

        if (empty($nome) || empty($especie) || empty($raca) || empty($status) || $idade < 0) {
            return false;
        }

        return $this->animalModel->update($id, $nome, $especie, $raca, $idade, $status, $foto, $userId);
    }

    /**
     * Valida o usuário logado e solicita a exclusão de um registro do animal
     *
     * @param int $id ID do animal que vai excluir
     * @return bool Retorna true se for excluído com sucesso ou false se falhar
     */
    public function delete(int $id): bool {
        $userId = $_SESSION['user_id'] ?? 0;
        if ($userId === 0 || $id <= 0) return false;

        return $this->animalModel->delete($id, $userId);
    }
}