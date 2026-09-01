<?php
namespace Controller;

use Model\User;

class UserController {
    
    private User $userModel;

    
    public function __construct() {
        $this->userModel = new User();
    }

    /**
     * Realiza a verificação de credenciais e autentica a sessão do usuário.
     *
     * @param string $email E-mail informado no login
     * @param string $senha Senha informada no login
     * @return bool Retorna true se a autenticação for bem-sucedida, falso caso contrário
     */
    public function login(string $email, string $senha): bool {
        $user = $this->userModel->getByEmail($email);

        // Verifica a existência do usuário e valida o hash da senha
        if ($user && password_verify($senha, $user['senha'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nome'] = $user['nome'];
            return true;
        }

        return false;
    }

    /**
     * Cadastra um novo usuário caso o e-mail informado ainda não esteja cadastrado
     *
     * @param string $nome Nome do usuário
     * @param string $email E-mail do usuário
     * @param string $senha Senha do usuário
     * @return bool Retorna true se registrado com sucesso, falso se e-mail já existir
     */
    public function register(string $nome, string $email, string $senha): bool {
        // Impede duplicidade de cadastros por e-mail
        if ($this->userModel->getByEmail($email)) {
            return false;
        }

        return $this->userModel->create($nome, $email, $senha);
    }

    /**
     * Tira a sessão atual do usuário e redireciona para a página inicial
     *
     * @return void
     */
    public function logout(): void {
        session_destroy();
        header('Location: ../index.php');
        exit();
    }
}