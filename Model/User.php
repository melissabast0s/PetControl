<?php
namespace Model;

use PDO;

/**
 * Classe User (Model)
 * Responsável pela manipulação dos dados dos usuários no banco de dados
 */
class User {
  
    private PDO $db;

    /**
     * Construtor da classe User.
     * Inicializa a conexão com o banco de dados através da classe Connection
     */
    public function __construct() {
        $this->db = Connection::getInstance();
    }

    /**
     * Cria um novo usuário no banco de dados com a senha criptografada
     *
     * @param string $nome Nome completo do usuário
     * @param string $email E-mail do usuário
     * @param string $senha Senha em texto puro, que depois vai ser gerado hash)
     * @return bool Retorna true em caso de sucesso ou false em falha
     */
    public function create(string $nome, string $email, string $senha): bool {
        // Criptografa a senha utilizando o algoritmo BCRYPT
        $hash = password_hash($senha, PASSWORD_BCRYPT);
        
        $stmt = $this->db->prepare("INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)");
        return $stmt->execute([
            ':nome' => $nome,
            ':email' => $email,
            ':senha' => $hash
        ]);
    }

    /**
     * Busca os dados de um usuário registrado pelo seu endereço de e-mail.
     *
     * @param string $email E-mail a ser pesquisado
     * @return array|null Dados do usuário em array associativo ou null se não encontrado
     */
    public function getByEmail(string $email): ?array {
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $result ?: null;
    }
}