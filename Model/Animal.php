<?php
namespace Model;

use PDO;

class Animal {
 
    private PDO $db;

    public function __construct() {
        $this->db = Connection::getInstance();
    }

    /**
     * Insere um novo registro do animal no banco de dados
     *
     * @param string $nome Nome do animal
     * @param string $especie Espécie do animal 
     * @param string $raca Raça do animal
     * @param int $idade Idade do animal em anos
     * @param string $status Status do animal - Disponível, Adotado, Tratamento
     * @param int $userId ID do usuário proprietário/responsável
     * @return bool Retorna true em caso de sucesso ou false em caso de falha
     */
    public function create(string $nome, string $especie, string $raca, int $idade, string $status, int $userId): bool {
        $stmt = $this->db->prepare(
            "INSERT INTO animais (nome, especie, raca, idade, status, user_id) 
             VALUES (:nome, :especie, :raca, :idade, :status, :user_id)"
        );
        return $stmt->execute([
            ':nome' => $nome,
            ':especie' => $especie,
            ':raca' => $raca,
            ':idade' => $idade,
            ':status' => $status,
            ':user_id' => $userId
        ]);
    }

    /**
     * Busca todos os animais de um usuário específico
     * @param int $userId ID do usuário
     * @return array Array com os animais encontrados
     */
    public function getAllByUserId(int $userId): array {
        $stmt = $this->db->prepare("SELECT * FROM animais WHERE user_id = :user_id ORDER BY id DESC");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca um único animal pelo seu ID e ID do usuário 
     *
     * @param int $id ID do animal
     * @param int $userId ID do usuário
     * @return array|null Dados do animal na array ou null se não for encontrado
     */
    public function getById(int $id, int $userId): ?array {
        $stmt = $this->db->prepare("SELECT * FROM animais WHERE id = :id AND user_id = :user_id");
        $stmt->execute([':id' => $id, ':user_id' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Atualiza os dados de um animal existente pertencente ao usuário especificado
     *
     * @param int $id ID do animal pra atualizar
     * @param string $nome Novo nome do animal
     * @param string $especie espécie do animal
     * @param string $raca  raça do animal
     * @param int $idade idade do animal
     * @param string $status  status do animal
     * @param int $userId ID do usuário 
     * @return bool Retorna true se a atualização for bem-sucedida ou false caso contrário
     */
    public function update(int $id, string $nome, string $especie, string $raca, int $idade, string $status, int $userId): bool {
        $stmt = $this->db->prepare(
            "UPDATE animais 
             SET nome = :nome, especie = :especie, raca = :raca, idade = :idade, status = :status 
             WHERE id = :id AND user_id = :user_id"
        );
        return $stmt->execute([
            ':id' => $id,
            ':nome' => $nome,
            ':especie' => $especie,
            ':raca' => $raca,
            ':idade' => $idade,
            ':status' => $status,
            ':user_id' => $userId
        ]);
    }

    /**
     * Remove o registro de um animal do banco de dados
     *
     * @param int $id ID do animal pra removido
     * @param int $userId ID do usuário 
     * @return bool Retorna true em caso de sucesso na exclusão ou false em caso de erro
     */
    public function delete(int $id, int $userId): bool {
        $stmt = $this->db->prepare("DELETE FROM animais WHERE id = :id AND user_id = :user_id");
        return $stmt->execute([':id' => $id, ':user_id' => $userId]);
    }
}