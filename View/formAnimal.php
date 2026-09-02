<?php

session_start();
require_once __DIR__ . '/../vendor/autoload.php';

use Controller\AnimalController;


if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

$animalController = new AnimalController();
$erro = '';


$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT) ?: null;
$animal = $id ? $animalController->show($id) : null;

if ($id && !$animal) {
    header('Location: painelAnimais.php');
    exit();
}

/**
 * @param string|null $texto
 * @return string
 */
function limparTextoForm(?string $texto): string {
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


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
 
    $nome = filter_input(INPUT_POST, 'nome', FILTER_DEFAULT);
    $especie = filter_input(INPUT_POST, 'especie', FILTER_DEFAULT);
    $raca = filter_input(INPUT_POST, 'raca', FILTER_DEFAULT);
    $idade = filter_input(INPUT_POST, 'idade', FILTER_VALIDATE_FLOAT);
    $status = filter_input(INPUT_POST, 'status', FILTER_DEFAULT);

    $nome = $nome ? trim($nome) : null;
    $especie = $especie ? trim($especie) : null;
    $raca = $raca ? trim($raca) : null;
    $status = $status ? trim($status) : null;

    $statusValidos = ['Disponível', 'Adotado', 'Tratamento'];

    $nomeFoto = $animal['foto'] ?? null;

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $extensao = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        $extensoesPermitidas = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array($extensao, $extensoesPermitidas)) {
            $diretorioUpload = __DIR__ . '/../uploads/';
            if (!is_dir($diretorioUpload)) {
                mkdir($diretorioUpload, 0755, true);
            }

            $nomeFoto = uniqid('pet_') . '.' . $extensao;
            $caminhoDestino = $diretorioUpload . $nomeFoto;

            if (!move_uploaded_file($_FILES['foto']['tmp_name'], $caminhoDestino)) {
                $erro = "Falha ao salvar a imagem enviada.";
            }
        } else {
            $erro = "Formato de foto inválido. Use JPG, PNG ou WEBP.";
        }
    }

    if (empty($erro)) {
        if ($nome && $especie && $raca && $idade !== false && $idade >= 0 && $status && in_array($status, $statusValidos)) {
            if ($id) {
             
                $sucesso = $animalController->update($id, $nome, $especie, $raca, (float)$idade, $status, $nomeFoto);
            } else {
                $sucesso = $animalController->store($nome, $especie, $raca, (float)$idade, $status, $nomeFoto);
            }

            if ($sucesso) {
                header('Location: painelAnimais.php');
                exit();
            } else {
                $erro = "Erro ao salvar os dados no banco de dados.";
            }
        } else {
            if ($idade === false || $idade < 0) {
                $erro = "Informe uma idade válida (número maior ou igual a zero, ex: 0.1 para 1 mês ou 21 para 1 ano).";
            } elseif ($status && !in_array($status, $statusValidos)) {
                $erro = "Selecione um status válido da lista.";
            } else {
                $erro = "Preencha todos os campos corretamente.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetControl - Cadastrar Novo Animal</title>
 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../templates/css/global.css">
    <link rel="stylesheet" href="../templates/css/formAnimal.css">
</head>
<body class="bg-light d-flex align-items-center justify-content-center min-vh-100 py-4">

   
    <div class="card shadow-sm border-0" style="width: 100%; max-width: 500px;">
       
 
        <div class="header-card p-3 fs-5 fw-semibold d-flex align-items-center gap-2">
            <i class="fa-solid fa-plus text-purple"></i> <?= $id ? 'Editar Animal' : 'Cadastrar Novo Animal' ?>
        </div>

        <div class="card-body p-4">

           
            <?php if (!empty($erro)): ?>
                <div class="alert alert-danger mb-3"><?= $erro ?></div>
            <?php endif; ?>

         
            <form method="POST" action="formAnimal.php<?= $id ? '?id=' . $id : '' ?>" enctype="multipart/form-data">
               
               
                <div class="mb-3">
                    <label for="nome" class="form-label text-secondary small fw-medium">Nome do Pet</label>
                    <input type="text" class="form-control" id="nome" name="nome" value="<?= limparTextoForm($animal['nome'] ?? '') ?>" required>
                </div>

                <div class="mb-3">
                    <label for="foto" class="form-label text-secondary small fw-medium">Foto do Pet</label>
                    <input type="file" class="form-control" id="foto" name="foto" accept="image/png, image/jpeg, image/webp">
                    <?php if (!empty($animal['foto'])): ?>
                        <div class="mt-2 d-flex align-items-center gap-2">
                            <span class="small text-muted">Foto atual:</span>
                            <img src="../uploads/<?= htmlspecialchars($animal['foto']) ?>" alt="Foto do Pet" style="width: 40px; height: 40px; object-fit: cover; border-radius: 50%;">
                        </div>
                    <?php endif; ?>
                </div>

           
                <div class="mb-3">
                    <label for="especie" class="form-label text-secondary small fw-medium">Espécie</label>
                    <input type="text" class="form-control" id="especie" name="especie" placeholder="Ex: Cão, Gato, Pássaro" value="<?= limparTextoForm($animal['especie'] ?? '') ?>" required>
                </div>

             
                <div class="mb-3">
                    <label for="raca" class="form-label text-secondary small fw-medium">Raça</label>
                    <input type="text" class="form-control" id="raca" name="raca" placeholder="Ex: SRD, Poodle, Siames" value="<?= limparTextoForm($animal['raca'] ?? '') ?>" required>
                </div>
                <div class="mb-3">
                    <label for="idade" class="form-label text-secondary small fw-medium">Idade (em anos ou meses)</label>
                    <input type="number" step="0.1" class="form-control" id="idade" name="idade" min="0" value="<?= htmlspecialchars($animal['idade'] ?? '') ?>" placeholder="Ex: 0.1 ou 1" required>
                </div>

               
                <div class="mb-4">
                    <label for="status" class="form-label text-secondary small fw-medium">Status</label>
                    <select class="form-select" id="status" name="status" required>
                        <?php $currentStatus = limparTextoForm($animal['status'] ?? ''); ?>
                        <option value="Disponível" <?= ($currentStatus === 'Disponível' || empty($currentStatus)) ? 'selected' : '' ?>>Disponível</option>
                        <option value="Adotado" <?= $currentStatus === 'Adotado' ? 'selected' : '' ?>>Adotado</option>
                        <option value="Tratamento" <?= $currentStatus === 'Tratamento' ? 'selected' : '' ?>>Tratamento</option>
                    </select>
                </div>
                <div class="d-flex justify-content-between align-items-center border-top pt-3">
                    <a href="painelAnimais.php" class="btn btn-secondary px-3">Cancelar</a>
                    <button type="submit" class="btn btn-custom-green px-3">Salvar Registro</button>
                </div>

            </form>
        </div>

    </div>

</body>
</html>