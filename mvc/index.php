<?php
session_start();

class Animal
{
    public function __construct(
        public string $nome,
        public string $especie,
        public int $idade
    ) {
    }
}

class ZoologicoModel
{
    public function __construct()
    {
        if (!isset($_SESSION['mvc_animais'])) {
            $_SESSION['mvc_animais'] = [
                ['nome' => 'Leão Simba', 'especie' => 'Leão', 'idade' => 5],
                ['nome' => 'Mimi', 'especie' => 'Macaco', 'idade' => 2],
            ];
        }
    }

    /** @return Animal[] */
    public function listarAnimais(?string $filtroEspecie = null): array
    {
        $dados = $_SESSION['mvc_animais'];
        if ($filtroEspecie) {
            $dados = array_filter($dados, fn($a) => stripos($a['especie'], $filtroEspecie) !== false);
        }

        return array_map(fn($a) => new Animal($a['nome'], $a['especie'], (int) $a['idade']), $dados);
    }

    public function adicionarAnimal(string $nome, string $especie, int $idade): void
    {
        $_SESSION['mvc_animais'][] = ['nome' => $nome, 'especie' => $especie, 'idade' => $idade];
    }
}

class ZoologicoController
{
    public function __construct(private ZoologicoModel $model)
    {
    }

    public function handle(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->model->adicionarAnimal(trim($_POST['nome']), trim($_POST['especie']), (int) $_POST['idade']);
            header('Location: index.php');
            exit;
        }

        $filtro = $_GET['especie'] ?? null;
        $animais = $this->model->listarAnimais($filtro ?: null);
        require __DIR__ . '/view.php';
    }
}

(new ZoologicoController(new ZoologicoModel()))->handle();
