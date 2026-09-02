<?php
/**
 * View de Cadastro de Usuário (Register)
 * Exibe o formulário de cadastro e processa o envio dos novos dados de conta
 */

session_start();

// Carrega o autoloader do Composer a partir da pasta View
require_once __DIR__ . '/../vendor/autoload.php';

use Controller\UserController;

$userController = new UserController();
$registerMessage = '';
$isSuccess = false;

/**
 * Processamento do Formulário de Cadastro com a requisição POST
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validação dos dados 
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $senha = $_POST['senha'] ?? '';

    if ($nome && $email && !empty($senha)) {
        if ($userController->register($nome, $email, $senha)) {
            $registerMessage = 'Cadastro realizado com sucesso! Redirecionando...';
            $isSuccess = true;
        } else {
            $registerMessage = 'O e-mail informado já está em uso.';
        }
    } else {
        $registerMessage = 'Preencha todos os campos corretamente.';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetControl - Cadastro</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="../templates/css/global.css">
    <link rel="stylesheet" href="../templates/css/register.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">

    
    <div class="card shadow p-4" style="width: 100%; max-width: 450px;">
        <h3 class="text-center mb-4 text-success fw-bold">Criar Conta</h3>

        <form method="POST" action="register.php">
            <div class="mb-3">
                <label for="nome" class="form-label">Nome Completo</label>
                <input type="text" class="form-control" id="nome" name="nome" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="senha" class="form-label">Senha</label>
                <input type="password" class="form-control" id="senha" name="senha" required>
            </div>
            <button type="submit" class="btn btn-success w-100">Cadastrar</button>
        </form>

        <div class="text-center mt-3">
            <a href="../index.php" class="text-decoration-none">Já tem uma conta? Faça login</a>
        </div>
    </div>


    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <?php if (!empty($registerMessage)): ?>
    <script>
        Toastify({
            text: "<?= $registerMessage ?>",
            duration: 3000,
            gravity: "top",
            position: "right",
            style: { background: "<?= $isSuccess ? '#198754' : '#dc3545' ?>" }
        }).showToast();

        <?php if ($isSuccess): ?>
       
        setTimeout(function() {
            window.location.href = "../index.php";
        }, 2000);
        <?php endif; ?>
    </script>
    <?php endif; ?>
</body>
</html>