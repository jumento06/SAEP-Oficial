<?php
require_once __DIR__ . "/../config/conexao.php";
require_once __DIR__ . "/../config/crypto.php";
require_once __DIR__ . "/../config/sessao.php";
exigir_login();
$titulo_pagina = "Tutores";
$termo = trim($_GET["q"] ?? "");
$msg = $_GET["msg"] ?? "";
if ($termo !== "") {
    $like = "%".$termo."%";
    $stmt = $conn->prepare("SELECT id,nome,cpf,telefone,email FROM tutores WHERE nome LIKE ? OR telefone LIKE ? OR email LIKE ? ORDER BY nome");
    $stmt->bind_param("sss", $like, $like, $like);
} else {
    $stmt = $conn->prepare("SELECT id,nome,cpf,telefone,email FROM tutores ORDER BY nome");
}
$stmt->execute();
$rows = $stmt->get_result();
require __DIR__ . "/../includes/header.php";
?>
<h1 class="page-title">Tutores</h1>
<?php if ($msg==="ok"): ?><div class="alert success">Tutor atualizado com sucesso.</div>
<?php elseif ($msg==="cad"): ?><div class="alert success">Tutor cadastrado com sucesso.</div><?php endif; ?>
<div class="card">
  <div class="toolbar">
    <form method="get" action="">
      <input type="search" name="q" placeholder="Buscar por nome, telefone ou e-mail..." value="<?= htmlspecialchars($termo) ?>">
      <button type="submit" class="btn">Buscar</button>
      <?php if ($termo!==""): ?><a class="btn btn-secondary" href="listar.php">Limpar</a><?php endif; ?>
    </form>
    <a class="btn" href="cadastrar.php">Novo tutor</a>
  </div>
  <?php if ($rows->num_rows===0): ?>
    <p class="empty">Nenhum tutor encontrado<?= $termo!==""?" para o termo informado":"" ?>.</p>
  <?php else: ?>
  <table class="data">
    <thead><tr><th>ID</th><th>Nome</th><th>CPF</th><th>Telefone</th><th>E-mail</th><th>Ações</th></tr></thead>
    <tbody>
    <?php while ($t=$rows->fetch_assoc()): ?>
      <tr>
        <td><?= (int)$t["id"] ?></td>
        <td><?= htmlspecialchars($t["nome"]) ?></td>
        <td><?= htmlspecialchars(descriptografar_cpf($t["cpf"])) ?></td>
        <td><?= htmlspecialchars($t["telefone"]??"") ?></td>
        <td><?= htmlspecialchars($t["email"]??"") ?></td>
        <td><a class="btn btn-sm" href="editar.php?id=<?= (int)$t["id"] ?>">Editar</a></td>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
  <?php endif; ?>
</div>
<?php require __DIR__ . "/../includes/footer.php"; ?>
