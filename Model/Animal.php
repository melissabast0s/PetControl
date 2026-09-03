<?php
namespace Model;

use PDO;
use PDOException;

class Animal {
 
    private PDO $db;

    public function __construct() {
        $this->db = Connection::getInstance();
    }

    /**
     * insere um registro novo do animal no banco de dados
     *
     * @param string $nome nome do animal
     * @param string $especie espécie do animal
     * @param string $raca raça do animal
     * @param float $idade idade do animal 
     * @param string $status status do animal 
     * @param string|null $foto nome do arquivo da foto do animal
     * @param int $userId ID do usuário responsável
     * @return bool retorna true em caso de sucesso ou false só se falhar
     */
    public function create(string $nome, string $especie, string $raca, float $idade, string $status, ?string $foto, int $userId): bool {
        try {
            $stmt = $this->db->prepare(
                "INSERT INTO animais (nome, especie, raca, idade, status, foto, user_id)
                 VALUES (:nome, :especie, :raca, :idade, :status, :foto, :user_id)"
            );
            return $stmt->execute([
                ':nome' => $nome,
                ':especie' => $especie,
                ':raca' => $raca,
                ':idade' => $idade,
                ':status' => $status,
                ':foto' => $foto,
                ':user_id' => $userId
            ]);
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * busca todos os animais de um usuário específico
     * @param int $userId ID do usuário
     * @return array array com os animais encontrados
     */
    public function getAllByUserId(int $userId): array {
        try {
            $stmt = $this->db->prepare("SELECT * FROM animais WHERE user_id = :user_id ORDER BY id DESC");
            $stmt->execute([':user_id' => $userId]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result ?: [];
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * busca apenas os animais disponíveis de um usuário
     * @param int $userId ID do usuário
     * @return array array com os animais com status disponivel
     */
    public function getDisponiveisByUserId(int $userId): array {
        try {
            $stmt = $this->db->prepare("SELECT * FROM animais WHERE user_id = :user_id AND status = 'Disponível' ORDER BY id DESC");
            $stmt->execute([':user_id' => $userId]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            return $result ?: [];
        } catch (PDOException $e) {
            return [];
        }
    }

    /**
     * busca um único animal pelo seu ID e ID do usuário
     *
     * @param int $id ID do animal
     * @param int $userId ID do usuário
     * @return array|null dados do animal na array ou null se não for encontrado
     */
    public function getById(int $id, int $userId): ?array {
        try {
            $stmt = $this->db->prepare("SELECT * FROM animais WHERE id = :id AND user_id = :user_id");
            $stmt->execute([':id' => $id, ':user_id' => $userId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ?: null;
        } catch (PDOException $e) {
            return null;
        }
    }

    /**
     * atualiza os dados do animal 
     *
     * @param int $id ID do animal pra atualizar
     * @param string $nome novo nome do animal
     * @param string $especie espécie do animal
     * @param string $raca  raça do animal
     * @param float $idade idade do animal
     * @param string $status  status do animal
     * @param string|null $foto nome do arquivo da foto do animal
     * @param int $userId ID do usuário
     * @return bool retorna true se a atualização acontecer ou false se falhar
     */
    public function update(int $id, string $nome, string $especie, string $raca, float $idade, string $status, ?string $foto, int $userId): bool {
        try {
            $stmt = $this->db->prepare(
                "UPDATE animais
                 SET nome = :nome, especie = :especie, raca = :raca, idade = :idade, status = :status, foto = :foto
                 WHERE id = :id AND user_id = :user_id"
            );
            $sucesso = $stmt->execute([
                ':id' => $id,
                ':nome' => $nome,
                ':especie' => $especie,
                ':raca' => $raca,
                ':idade' => $idade,
                ':status' => $status,
                ':foto' => $foto,
                ':user_id' => $userId
            ]);
            return $sucesso && $stmt->rowCount() >= 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * remove o registro de um animal do banco de dados
     *
     * @param int $id ID do animal pra ser removido
     * @param int $userId ID do usuário
     * @return bool retorna true em caso de sucesso na exclusão ou false se falhar
     */
    public function delete(int $id, int $userId): bool {
        try {
            $stmt = $this->db->prepare("DELETE FROM animais WHERE id = :id AND user_id = :user_id");
            $stmt->execute([':id' => $id, ':user_id' => $userId]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }
}