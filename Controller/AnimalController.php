<?php
namespace Controller;

use Model\Animal;

class AnimalController {
    private Animal $animalModel;

    public function __construct() {
        $this->animalModel = new Animal();
    }

    public function index(): array {
        $userId = $_SESSION['user_id'] ?? 0;
        return $this->animalModel->getAllByUserId($userId);
    }

    public function show(int $id): ?array {
        $userId = $_SESSION['user_id'] ?? 0;
        return $this->animalModel->getById($id, $userId);
    }

    public function store(string $nome, string $especie, string $raca, int $idade, string $status): bool {
        $userId = $_SESSION['user_id'] ?? 0;
        if ($userId === 0) return false;
        return $this->animalModel->create($nome, $especie, $raca, $idade, $status, $userId);
    }

    public function update(int $id, string $nome, string $especie, string $raca, int $idade, string $status): bool {
        $userId = $_SESSION['user_id'] ?? 0;
        if ($userId === 0) return false;
        return $this->animalModel->update($id, $nome, $especie, $raca, $idade, $status, $userId);
    }

    public function delete(int $id): bool {
        $userId = $_SESSION['user_id'] ?? 0;
        if ($userId === 0) return false;
        return $this->animalModel->delete($id, $userId);
    }
}