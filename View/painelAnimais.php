<?php
session_start();
require_once __DIR__ . '/../vendor/autoload.php';

// Proteção da página: se não estiver logado, redireciona para a raiz
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

$userNome = $_SESSION['user_nome'] ?? 'joao@exemplo.com';
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetControl - Painel de Animais</title>
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- CSS Global e do Painel -->
    <link rel="stylesheet" href="../templates/css/global.css">
    <link rel="stylesheet" href="../templates/css/painelAnimais.css">
</head>
<body class="bg-light">

    <!-- Navbar Verde Esmeralda Profissional -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-emerald shadow-sm py-3">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center gap-2 fw-bold fs-4" href="#">
                <i class="fa-solid fa-paw text-light"></i>
                <span>PetControl</span>
            </a>
            
            <div class="d-flex align-items-center gap-3">
                <div class="user-badge d-flex align-items-center gap-2 text-white bg-emerald-dark px-3 py-1-5 rounded-pill shadow-sm">
                    <i class="fa-solid fa-circle-user fs-5 text-light"></i>
                    <span class="small fw-semibold"><?= htmlspecialchars($userNome) ?></span>
                </div>
                
                <a href="../index.php?action=logout" class="btn btn-outline-light btn-sm px-3 rounded-pill fw-semibold border-2 d-flex align-items-center gap-1">
                    <i class="fa-solid fa-right-from-bracket"></i> Sair
                </a>
            </div>
        </div>
    </nav>

    <!-- Conteúdo do Painel -->
    <main class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="fw-bold text-success mb-1">Painel de Controle</h2>
                <p class="text-muted small mb-0">Gerencie e acompanhe o status dos seus animais cadastrados.</p>
            </div>
            <a href="formAnimal.php" class="btn btn-success px-4 py-2 rounded-3 shadow-sm fw-semibold d-flex align-items-center gap-2">
                <i class="fa-solid fa-plus"></i> Novo Animal
            </a>
        </div>

        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-emerald text-white">
                            <tr>
                                <th class="py-3 px-4">Nome</th>
                                <th class="py-3">Espécie</th>
                                <th class="py-3">Raça</th>
                                <th class="py-3">Idade</th>
                                <th class="py-3">Status</th>
                                <th class="py-3 px-4 text-end">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($animais) && count($animais) > 0): ?>
                                <?php foreach ($animais as $animal): ?>
                                <tr>
                                    <td class="px-4 fw-semibold text-dark"><?= htmlspecialchars($animal['nome']) ?></td>
                                    <td><?= htmlspecialchars($animal['especie']) ?></td>
                                    <td><?= htmlspecialchars($animal['raca']) ?></td>
                                    <td><?= htmlspecialchars($animal['idade']) ?> anos</td>
                                    <td>
                                        <span class="badge bg-success-subtle text-success border border-success-subtle rounded-pill px-3 py-1">
                                            <?= htmlspecialchars($animal['status']) ?>
                                        </span>
                                    </td>
                                    <td class="px-4 text-end">
                                        <a href="formAnimal.php?id=<?= $animal['id'] ?>" class="btn btn-sm btn-outline-primary rounded-circle me-1" title="Editar">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <a href="../index.php?action=delete&id=<?= $animal['id'] ?>" class="btn btn-sm btn-outline-danger rounded-circle" title="Excluir" onclick="return confirm('Deseja realmente excluir este registro?');">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="6" class="text-center py-5 text-muted">
                                        <i class="fa-solid fa-folder-open fs-1 d-block mb-2 text-secondary opacity-50"></i>
                                        Nenhum animal cadastrado até o momento.
                                    </td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </main>

</body>
</html>