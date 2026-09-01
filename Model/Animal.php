<?php
namespace Model;

use PDO;

class Animal {
    private PDO $db;

    public function __construct() {
        $this->db = Connection::getInstance();
    }

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

    public function getAllByUserId(int $userId): array {
        $stmt = $this->db->prepare("SELECT * FROM animais WHERE user_id = :user_id ORDER BY id DESC");
        $stmt->execute([':user_id' => $userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById(int $id, int $userId): ?array {
        $stmt = $this->db->prepare("SELECT * FROM animais WHERE id = :id AND user_id = :user_id");
        $stmt->execute([':id' => $id, ':user_id' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

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

    public function delete(int $id, int $userId): bool {
        $stmt = $this->db->prepare("DELETE FROM animais WHERE id = :id AND user_id = :user_id");
        return $stmt->execute([':id' => $id, ':user_id' => $userId]);
    }
}