<?php

session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use Controller\AnimalController;

// verifica se o usuário é autenticado
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

$animalController = new AnimalController();

// pra excluir o animal
$action = filter_input(INPUT_GET, 'action', FILTER_DEFAULT);
$deleteId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($action === 'delete' && $deleteId) {
    $animalController->delete($deleteId);
    header('Location: painelAnimais.php');
    exit();
}

// navegação (todos e os disponiveis)
$aba = filter_input(INPUT_GET, 'aba', FILTER_DEFAULT) ?? 'todos';

if ($aba === 'disponiveis') {
    $animais = $animalController->disponiveis();
} else {
    $animais = $animalController->index();
}

/**
 * converte os caracteres especiais do html no banco de dados
 * @param string|null $texto texto do banco de dados
 * @return string texto decodificado
 */
function formatarTexto(?string $texto): string {
    if (empty($texto)) return '';
    return htmlspecialchars(
        html_entity_decode(
            html_entity_decode($texto, ENT_QUOTES, 'UTF-8'),
            ENT_QUOTES,
            'UTF-8'
        ),
        ENT_QUOTES,
        'UTF-8'
    );
}

/**
 * idade do animal
 * @param float|int|string $idade
 * @return string 
 */
function formatarIdade($idade): string {
    $num = (float)$idade;

    // se o for menor que 1 (ex: 0.1, 0.6, 0.11), são meses
    if ($num < 1.0) {
        $meses = (int)round($num * 10);

        if ($num >= 0.10 && $num < 0.12) {
            $meses = (int)round($num * 100);
        }

        if ($meses === 0) {
            return 'Menos de 1 mês';
        }

        return $meses . ($meses === 1 ? ' mês' : ' meses');
    }

    // pra 1 ano ou mais
    $anos = ($num == (int)$num) ? (int)$num : $num;
    return $anos . ($anos == 1 ? ' ano' : ' anos');
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetControl - Painel de Controle</title>
   
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../templates/css/global.css">
    <link rel="stylesheet" href="../templates/css/painelAnimais.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom py-3 mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold fs-4 d-flex align-items-center gap-2" href="#">
                <i class="fa-solid fa-paw"></i> PetControl
            </a>
            <div class="d-flex align-items-center gap-3">
                <span class="btn btn-sm btn-light rounded-pill px-3 disabled text-dark border-0 opacity-100 fw-medium">
                    <i class="fa-solid fa-user me-1 text-secondary"></i> <?= htmlspecialchars($_SESSION['user_nome'] ?? 'Usuário') ?>
                </span>
                <a href="../index.php?action=logout" class="btn btn-outline-light btn-sm rounded-pill px-3">
                    <i class="fa-solid fa-right-from-bracket me-1"></i> Sair
                </a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <h2 class="fw-bold text-custom-green mb-1">Painel de Controle</h2>
                <p class="text-secondary mb-0">Gerencie e acompanhe o status dos animais cadastrados.</p>
            </div>
            <a href="formAnimal.php" class="btn btn-custom-green fw-semibold px-3 py-2 rounded-3">
                + Novo Animal
            </a>
        </div>
        <ul class="nav nav-tabs mb-4 border-bottom-0">
            <li class="nav-item">
                <a class="nav-link <?= $aba === 'todos' ? 'active fw-bold text-custom-green' : 'text-secondary' ?>" href="painelAnimais.php?aba=todos">
                    <i class="fa-solid fa-list me-1"></i> Todos os Animais
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?= $aba === 'disponiveis' ? 'active fw-bold text-custom-green' : 'text-secondary' ?>" href="painelAnimais.php?aba=disponiveis">
                 Disponíveis para Adoção
                </a>
            </li>
        </ul>
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <div class="table-responsive">
                <table class="table align-middle mb-0">
                    <thead class="table-light border-bottom">
                        <tr class="text-secondary small fw-bold">
                            <th class="ps-4 py-3">NOME</th>
                            <th class="py-3">ESPÉCIE</th>
                            <th class="py-3">RAÇA</th>
                            <th class="py-3">IDADE</th>
                            <th class="py-3">STATUS</th>
                            <th class="pe-4 py-3 text-end">AÇÕES</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($animais)): ?>
                            <tr>
                                <td colspan="6" class="text-center py-5 text-secondary">
                                    <div class="mb-2">
                                        <i class="fa-solid fa-folder-closed fa-3x text-muted opacity-50"></i>
                                    </div>
                                    <p class="mb-0">Nenhum animal cadastrado até o momento.</p>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($animais as $animal): ?>
                                <tr>
                                    <td class="ps-4 fw-semibold text-dark">
                                        <div class="d-flex align-items-center gap-2">
                                            <?php if (!empty($animal['foto'])): ?>
                                                <img src="../uploads/<?= htmlspecialchars($animal['foto']) ?>" alt="<?= formatarTexto($animal['nome']) ?>" style="width: 38px; height: 38px; object-fit: cover; border-radius: 50%;">
                                            <?php else: ?>
                                                <div class="bg-light d-flex align-items-center justify-content-center text-muted rounded-circle border" style="width: 38px; height: 38px;">
                                                    <i class="fa-solid fa-paw small"></i>
                                                </div>
                                            <?php endif; ?>
                                            <span><?= formatarTexto($animal['nome']) ?></span>
                                        </div>
                                    </td>
                                    <td><?= formatarTexto($animal['especie']) ?></td>
                                    <td><?= formatarTexto($animal['raca']) ?></td>
                                    <td><?= formatarIdade($animal['idade']) ?></td>
                                    <td>
                                        <?php
                                            $st = formatarTexto($animal['status'] ?? 'Disponível');
                                            $badgeClass = 'badge-status-disponivel';
                                            if (mb_strtolower($st) === 'adotado') $badgeClass = 'badge-status-adotado';
                                            if (mb_strtolower($st) === 'tratamento') $badgeClass = 'badge-status-tratamento';
                                        ?>
                                        <span class="badge rounded-pill px-3 py-2 <?= $badgeClass ?>"><?= $st ?></span>
                                    </td>
                                    <td class="pe-4 text-end">
                                        <a href="formAnimal.php?id=<?= $animal['id'] ?>" class="btn btn-sm btn-outline-primary me-1" title="Editar">
                                            <i class="fa-solid fa-pen"></i>
                                        </a>
                                        <a href="painelAnimais.php?action=delete&id=<?= $animal['id'] ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Deseja realmente excluir este animal?');" title="Excluir">
                                            <i class="fa-solid fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</body>
</html>