<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PetControl - Formuário do Animal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h4 class="mb-0"><?= isset($animal) ? '✏️ Editar Animal' : '➕ Cadastrar Novo Animal' ?></h4>
                </div>
                <div class="card-body">
                    <?php if (!empty($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome do Pet</label>
                            <input type="text" id="nome" name="nome" class="form-control" required value="<?= htmlspecialchars($animal['nome'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label for="especie" class="form-label">Espécie</label>
                            <input type="text" id="especie" name="especie" class="form-control" placeholder="Ex: Cão, Gato, Pássaro" required value="<?= htmlspecialchars($animal['especie'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label for="raca" class="form-label">Raça</label>
                            <input type="text" id="raca" name="raca" class="form-control" placeholder="Ex: SRD, Poodle, Siames" required value="<?= htmlspecialchars($animal['raca'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label for="idade" class="form-label">Idade (em anos)</label>
                            <input type="number" id="idade" name="idade" class="form-control" min="0" required value="<?= htmlspecialchars($animal['idade'] ?? '') ?>">
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select id="status" name="status" class="form-select">
                                <option value="Disponível" <?= (isset($animal) && $animal['status'] === 'Disponível') ? 'selected' : '' ?>>Disponível</option>
                                <option value="Adotado" <?= (isset($animal) && $animal['status'] === 'Adotado') ? 'selected' : '' ?>>Adotado</option>
                                <option value="Tratamento" <?= (isset($animal) && $animal['status'] === 'Tratamento') ? 'selected' : '' ?>>Tratamento</option>
                            </select>
                        </div>
                        <div class="d-flex justify-content-between">
                            <a href="index.php?action=dashboard" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-success">Salvar Registro</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>