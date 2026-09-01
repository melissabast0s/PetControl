<?php
namespace Controller;

use Model\Animal;

/**
 * Classe AnimalController
 * Gerencia as operações de CRUD para os animais vinculados ao usuário autenticado.
 */
class AnimalController {
    /**
     * @var Animal Instância do modelo de dados de Animal.
     */
    private Animal $animalModel;

    /**
     * Construtor da classe AnimalController.
     * Valida a autenticação do usuário antes de permitir o acesso às rotas.
     */
    public function __construct() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }
        $this->animalModel = new Animal();
    }

    /**
     * Exibe o painel principal com a listagem dos animais do usuário logado.
     *
     * @return void
     */
    public function dashboard(): void {
        $animais = $this->animalModel->getAllByUser($_SESSION['user_id']);
        require_once __DIR__ . '/../View/painelAnimais.php';
    }

    /**
     * Exibe o formulário de cadastro e processa a criação de um novo animal.
     *
     * @return void
     */
    public function create(): void {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $especie = filter_input(INPUT_POST, 'especie', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $raca = filter_input(INPUT_POST, 'raca', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $idade = filter_input(INPUT_POST, 'idade', FILTER_VALIDATE_INT);
            $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if ($nome && $especie && $raca && $idade !== false && $status) {
                $this->animalModel->create($_SESSION['user_id'], $nome, $especie, $raca, $idade, $status);
                header('Location: index.php?action=dashboard');
                exit;
            }
            $error = "Preencha os campos com valores válidos.";
        }
        $animal = null;
        require_once __DIR__ . '/../View/formAnimal.php';
    }

    /**
     * Exibe o formulário com dados carregados e processa a atualização do animal.
     *
     * @return void
     */
    public function edit(): void {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $animal = $this->animalModel->getById($id, $_SESSION['user_id']);

        if (!$animal) {
            header('Location: index.php?action=dashboard');
            exit;
        }

        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $especie = filter_input(INPUT_POST, 'especie', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $raca = filter_input(INPUT_POST, 'raca', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
            $idade = filter_input(INPUT_POST, 'idade', FILTER_VALIDATE_INT);
            $status = filter_input(INPUT_POST, 'status', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            if ($nome && $especie && $raca && $idade !== false && $status) {
                $this->animalModel->update($id, $_SESSION['user_id'], $nome, $especie, $raca, $idade, $status);
                header('Location: index.php?action=dashboard');
                exit;
            }
            $error = "Preencha os campos com valores válidos.";
        }
        require_once __DIR__ . '/../View/formAnimal.php';
    }

    /**
     * Processa a remoção de um registro de animal.
     *
     * @return void
     */
    public function delete(): void {
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        if ($id) {
            $this->animalModel->delete($id, $_SESSION['user_id']);
        }
        header('Location: index.php?action=dashboard');
        exit;
    }
}