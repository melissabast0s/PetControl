<?php
namespace Controller;

use Model\User;

class UserController {
    
    private User $userModel;

    
    public function __construct() {
        $this->userModel = new User();
    }

    /**
     * autentica a sessão do usuário
     *
     * @param string $email e-mail informado no login
     * @param string $senha senha informada no login
     * @return bool retorna true se a autenticação der certo, falso se falhar
     */
    public function login(string $email, string $senha): bool {
        $user = $this->userModel->getByEmail($email);

        // verifica a existência do usuário e valida senha
        if ($user && password_verify($senha, $user['senha'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nome'] = $user['nome'];
            return true;
        }

        return false;
    }

    /**
     * cadastra um usuário novo se o e-mail ainda não foi cadastrado
     *
     * @param string $nome nome do usuário
     * @param string $email e-mail do usuário
     * @param string $senha senha do usuário
     * @return bool retorna true se for registrado com sucesso, falso se o e-mail já existir
     */
    public function register(string $nome, string $email, string $senha): bool {
        // impede 2 cadastros por e-mail
        if ($this->userModel->getByEmail($email)) {
            return false;
        }

        return $this->userModel->create($nome, $email, $senha);
    }

    /**
     * tira a sessão atual do usuário e redireciona pra página inicial
     *
     * @return void
     */
    public function logout(): void {
        session_destroy();
        header('Location: ../index.php');
        exit();
    }
}