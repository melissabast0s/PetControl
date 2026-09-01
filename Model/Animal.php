<?php
namespace Model;

use PDO;

/**
 * Classe Animal
 * Responsável pelo CRUD de animais associados aos usuários no banco de dados.
 */
class Animal {
    /**
     * @var PDO Conexão com o banco de dados.
     */
    private PDO $db;

    /**
     * Construtor da classe Animal.
     * Inicializa a conexão com o banco de dados.
     */
    public function __construct() {
        $this->db = Connection::getInstance();
    }

    /**
     * Cadastra um novo animal associado ao usuário logado.
     *
     * @param int $usuarioId ID do usuário proprietário.
     * @param string $nome Nome do animal.
     * @param string $especie Espécie (ex: Cão, Gato).
     * @param string $raca Raça do animal.
     * @param int $idade Idade em anos.
     * @param string $status Status do animal (ex: Disponível, Adotado, Tratamento).
     * @return bool Retorna true em caso de sucesso ou false em falha.
     */
    public function create(int $usuarioId, string $nome, string $especie, string $raca, int $idade, string $status): bool {
        $stmt = $this->db->prepare("INSERT INTO animais (usuario_id, nome, especie, raca, idade, status) VALUES (:usuario_id, :nome, :especie, :raca, :idade, :status)");
        return $stmt->execute([
            ':usuario_id' => $usuarioId,
            ':nome' => $nome,
            ':especie' => $especie,
            ':raca' => $raca,
            ':idade' => $idade,
            ':status' => $status
        ]);
    }

    /**
     * Retorna todos os animais cadastrados pertencentes a um usuário específico.
     *
     * @param int $usuarioId ID do usuário.
     * @return array Lista de animais cadastrados.
     */
    public function getAllByUser(int $usuarioId): array {
        $stmt = $this->db->prepare("SELECT * FROM animais WHERE usuario_id = :usuario_id ORDER BY id DESC");
        $stmt->execute([':usuario_id' => $usuarioId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Busca um único animal pelo ID garantindo que pertença ao usuário.
     *
     * @param int $id ID do registro do animal.
     * @param int $usuarioId ID do usuário proprietário.
     * @return array|null Dados do animal ou null se não encontrado.
     */
    public function getById(int $id, int $usuarioId): ?array {
        $stmt = $this->db->prepare("SELECT * FROM animais WHERE id = :id AND usuario_id = :usuario_id");
        $stmt->execute([':id' => $id, ':usuario_id' => $usuarioId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    /**
     * Atualiza os dados de um registro de animal existente.
     *
     * @param int $id ID do registro do animal.
     * @param int $usuarioId ID do usuário proprietário.
     * @param string $nome Nome atualizado do animal.
     * @param string $especie Espécie atualizada.
     * @param string $raca Raça atualizada.
     * @param int $idade Idade atualizada em anos.
     * @param string $status Status atualizado.
     * @return bool
     */
    public function update(int $id, int $usuarioId, string $nome, string $especie, string $raca, int $idade, string $status): bool {
        $stmt = $this->db->prepare("UPDATE animais SET nome = :nome, especie = :especie, raca = :raca, idade = :idade, status = :status WHERE id = :id AND usuario_id = :usuario_id");
        return $stmt->execute([
            ':id' => $id,
            ':usuario_id' => $usuarioId,
            ':nome' => $nome,
            ':especie' => $especie,
            ':raca' => $raca,
            ':idade' => $idade,
            ':status' => $status
        ]);
    }

    /**
     * Exclui um animal do banco de dados.
     *
     * @param int $id ID do registro do animal.
     * @param int $usuarioId ID do usuário proprietário.
     * @return bool
     */
    public function delete(int $id, int $usuarioId): bool {
        $stmt = $this->db->prepare("DELETE FROM animais WHERE id = :id AND usuario_id = :usuario_id");
        return $stmt->execute([':id' => $id, ':usuario_id' => $usuarioId]);
    }
}