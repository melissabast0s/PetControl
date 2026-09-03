<?php
namespace Model;

use PDO;

class User {
  
    private PDO $db;

    public function __construct() {
        $this->db = Connection::getInstance();
    }

    /**
     * cria um novo usuário no banco de dados com a senha criptografada
     *
     * @param string $nome nome completo do usuário
     * @param string $email e-mail do usuário
     * @param string $senha senha normal
     * @return bool retorna true em caso de sucesso ou false em falha
     */
    public function create(string $nome, string $email, string $senha): bool {
        $hash = password_hash($senha, PASSWORD_BCRYPT);
        
        $stmt = $this->db->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)");
        return $stmt->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':senha' => $hash
        ]);
    }

    /**
     * busca os dados de um usuário pelo e-mail
     *
     * @param string $email e-mail 
     * @return array|null dados do usuário em array ou null se não encontrado
     */
    public function getByEmail(string $email): ?array {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result ?: null;
    }
}