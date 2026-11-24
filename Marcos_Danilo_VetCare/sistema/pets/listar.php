<?php
require_once __DIR__ . "/../config/conexao.php";
require_once __DIR__ . "/../config/sessao.php";
exigir_login();
$titulo_pagina="Pets"; $msg=$_GET["msg"]??"";
$sql="SELECT p.id,p.nome,p.especie,p.raca,p.idade,t.nome AS tutor_nome FROM pets p INNER JOIN tutores t ON t.id=p.tutor_id ORDER BY p.nome";
$rows=$conn->query($sql);
require __DIR__ . "/../includes/header.php";
?>
<h1 class="page-title">Pets</h1>
<?php if ($msg==="ok"): ?><div class="alert success">Pet atualizado com sucesso.</div>
<?php elseif ($msg==="cad"): ?><div class="alert success">Pet cadastrado com sucesso.</div><?php endif; ?>
<div class="card">
  <div class="toolbar"><a class="btn" href="cadastrar.php">Novo pet</a></div>
  <?php if ($rows->num_rows===0): ?><p class="empty">Nenhum pet cadastrado.</p>
  <?php else: ?>
  <table class="data">
    <thead><tr><th>ID</th><th>Nome</th><th>Espécie</th><th>Raça</th><th>Idade</th><th>Tutor</th><th>Ações</th></tr></thead>
    <tbody>
    <?php while ($p=$rows->fetch_assoc()): ?>
      <tr>
        <td><?= (int)$p["id"] ?></td>
        <td><?= htmlspecialchars($p["nome"]) ?></td>
        <td><?= htmlspecialchars($p["especie"]) ?></td>
        <td><?= htmlspecialchars($p["raca"]??"-") ?></td>
        <td><?= $p["idade"]!==null?(int)$p["idade"]." anos":"-" ?></td>
        <td><?= htmlspecialchars($p["tutor_nome"]) ?></td>
        <td><a class="btn btn-sm" href="editar.php?id=<?= (int)$p["id"] ?>">Editar</a></td>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
  <?php endif; ?>
</div>
<?php require __DIR__ . "/../includes/footer.php"; ?>
