<?php
session_start();

class ZoologicoModel
{
    public function __construct()
    {
        $_SESSION['mvvm_animais'] ??= [
            ['nome' => 'Leão Simba', 'especie' => 'Leão', 'idade' => 5],
            ['nome' => 'Mimi', 'especie' => 'Macaco', 'idade' => 2],
        ];
    }

    public function all(): array { return $_SESSION['mvvm_animais']; }
    public function add(array $animal): void { $_SESSION['mvvm_animais'][] = $animal; }
}

class ZoologicoViewModel
{
    public array $linhas = [];
    public string $filtro = '';

    public function __construct(private ZoologicoModel $model) {}

    public function processar(array $req): void
    {
        if ($req['method'] === 'POST') {
            $this->model->add(['nome' => trim($req['nome']), 'especie' => trim($req['especie']), 'idade' => (int) $req['idade']]);
            header('Location: index.php');
            exit;
        }

        $this->filtro = trim($req['filtro']);
        $dados = $this->model->all();
        if ($this->filtro !== '') {
            $dados = array_filter($dados, fn($a) => stripos($a['especie'], $this->filtro) !== false);
        }
        $this->linhas = array_map(fn($a) => sprintf('%s - %s (%d anos)', $a['nome'], $a['especie'], $a['idade']), $dados);
    }
}

$vm = new ZoologicoViewModel(new ZoologicoModel());
$vm->processar([
    'method' => $_SERVER['REQUEST_METHOD'],
    'nome' => $_POST['nome'] ?? '',
    'especie' => $_POST['especie'] ?? '',
    'idade' => $_POST['idade'] ?? 0,
    'filtro' => $_GET['especie'] ?? '',
]);
?>
<!doctype html>
<html lang="pt-BR"><head><meta charset="UTF-8"><title>Zoológico MVVM</title><link rel="stylesheet" href="../ui.css"></head>
<body><div class="container"><h1>MVVM</h1><p>View consome estado pronto do ViewModel.</p>
<form method="post" class="card"><h2>Novo animal</h2><input name="nome" required placeholder="Nome"><input name="especie" required placeholder="Espécie"><input name="idade" type="number" min="0" required placeholder="Idade"><button>Adicionar</button></form>
<form method="get" class="card"><h2>Filtrar espécie</h2><input name="especie" value="<?= htmlspecialchars($vm->filtro) ?>"><button>Filtrar</button></form>
<ul class="card"><?php foreach ($vm->linhas as $l): ?><li><?= htmlspecialchars($l) ?></li><?php endforeach; ?></ul>
</div></body></html>
