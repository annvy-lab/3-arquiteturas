<?php
session_start();

interface ZoologicoViewInterface
{
    public function pegarEntrada(): array;
    public function render(array $animais, string $filtro): void;
    public function redirect(): void;
}

class ZoologicoModel
{
    public function __construct()
    {
        $_SESSION['mvp_animais'] ??= [
            ['nome' => 'Leão Simba', 'especie' => 'Leão', 'idade' => 5],
            ['nome' => 'Mimi', 'especie' => 'Macaco', 'idade' => 2],
        ];
    }

    public function adicionar(array $a): void
    {
        $_SESSION['mvp_animais'][] = $a;
    }

    public function listar(string $filtro = ''): array
    {
        return array_values(array_filter($_SESSION['mvp_animais'], fn($a) => $filtro === '' || stripos($a['especie'], $filtro) !== false));
    }
}

class ZoologicoPresenter
{
    public function __construct(private ZoologicoModel $model, private ZoologicoViewInterface $view)
    {
    }

    public function run(): void
    {
        $in = $this->view->pegarEntrada();
        if ($in['method'] === 'POST') {
            $this->model->adicionar(['nome' => $in['nome'], 'especie' => $in['especie'], 'idade' => (int) $in['idade']]);
            $this->view->redirect();
        }
        $this->view->render($this->model->listar($in['filtro']), $in['filtro']);
    }
}

class ZoologicoView implements ZoologicoViewInterface
{
    public function pegarEntrada(): array
    {
        return [
            'method' => $_SERVER['REQUEST_METHOD'],
            'nome' => trim($_POST['nome'] ?? ''),
            'especie' => trim($_POST['especie'] ?? ''),
            'idade' => (int) ($_POST['idade'] ?? 0),
            'filtro' => trim($_GET['especie'] ?? ''),
        ];
    }

    public function redirect(): void
    {
        header('Location: index.php');
        exit;
    }

    public function render(array $animais, string $filtro): void
    {
        ?>
        <!doctype html><html lang="pt-BR"><head><meta charset="UTF-8"><title>Zoológico MVP</title><link rel="stylesheet" href="../ui.css"></head><body>
        <div class="container"><h1>MVP</h1><p>Presenter controla fluxo; View apenas exibe e coleta entrada.</p>
        <form method="post" class="card"><h2>Novo animal</h2><input name="nome" required placeholder="Nome"><input name="especie" required placeholder="Espécie"><input name="idade" type="number" min="0" required placeholder="Idade"><button>Adicionar</button></form>
        <form method="get" class="card"><h2>Filtrar espécie</h2><input name="especie" value="<?= htmlspecialchars($filtro) ?>"><button>Filtrar</button></form>
        <ul class="card"><?php foreach ($animais as $a): ?><li><?= htmlspecialchars($a['nome']) ?> - <?= htmlspecialchars($a['especie']) ?> (<?= (int) $a['idade'] ?> anos)</li><?php endforeach; ?></ul></div>
        </body></html>
        <?php
    }
}

(new ZoologicoPresenter(new ZoologicoModel(), new ZoologicoView()))->run();
