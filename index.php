<?php

session_start();

require_once __DIR__ . '/vendor/autoload.php'; 

use Controller\UserController;

$userController = new UserController();


$action = $_GET['action'] ?? 'login';


if ($action === 'sair') {
    $userController->logout();
    exit();
}

if ($action === 'painel') {
    
    if (isset($_SESSION['user_id'])) {
        header('Location: View/painelAnimais.php');
        exit();
    } else {
        header('Location: index.php');
        exit();
    }
}

$loginMessage = '';

//processamento do formulário de login 
 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $senha = $_POST['senha'] ?? '';

    if ($email && !empty($senha)) {
        
        if ($userController->login($email, $senha)) {
            header('Location: View/painelAnimais.php');
            exit();
        }
    }
    
    
    $loginMessage = 'E-mail ou senha inválidos!';
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetControl - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="stylesheet" href="templates/css/global.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center vh-100">

    
    <div class="card shadow p-4" style="width: 100%; max-width: 400px;">
        <h3 class="text-center mb-4 text-success fw-bold">PetControl</h3>

        <form method="POST" action="index.php">
            <div class="mb-3">
                <label for="email" class="form-label">E-mail</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="senha" class="form-label">Senha</label>
                <input type="password" class="form-control" id="senha" name="senha" required>
            </div>
            <button type="submit" class="btn btn-success w-100">Entrar</button>
        </form>

        <div class="text-center mt-3">
            <a href="View/register.php" class="text-decoration-none">Não tem uma conta? Cadastre-se</a>
        </div>
    </div>

   
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <?php if (!empty($loginMessage)): ?>
    <script>
        Toastify({
            text: "<?= $loginMessage ?>",
            duration: 3000,
            gravity: "top",
            position: "right",
            style: { background: "#dc3545" }
        }).showToast();
    </script>
    <?php endif; ?>
</body>
</html>