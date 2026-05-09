<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Zoológico MVC</title>
    <link rel="stylesheet" href="../ui.css">
</head>
<body>
<div class="container">
    <h1>MVC</h1>
    <p>Controller recebe ações, atualiza Model e envia dados para View.</p>

    <form method="post" class="card">
        <h2>Novo animal</h2>
        <input name="nome" placeholder="Nome" required>
        <input name="especie" placeholder="Espécie" required>
        <input name="idade" type="number" min="0" placeholder="Idade" required>
        <button type="submit">Adicionar</button>
    </form>

    <form method="get" class="card">
        <h2>Filtrar espécie</h2>
        <input name="especie" placeholder="Ex: Leão" value="<?= htmlspecialchars($_GET['especie'] ?? '') ?>">
        <button type="submit">Filtrar</button>
    </form>

    <ul class="card">
        <?php foreach ($animais as $animal): ?>
            <li><?= htmlspecialchars($animal->nome) ?> - <?= htmlspecialchars($animal->especie) ?> (<?= $animal->idade ?> anos)</li>
        <?php endforeach; ?>
    </ul>
</div>
</body>
</html>
