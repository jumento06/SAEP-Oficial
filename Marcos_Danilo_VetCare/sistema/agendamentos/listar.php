<?php
require_once __DIR__ . "/../config/conexao.php";
require_once __DIR__ . "/../config/sessao.php";
exigir_login();
$titulo_pagina="Agendamentos"; $msg=$_GET["msg"]??"";
$sql="SELECT a.id,a.data_agendamento,a.servico,a.status,a.observacoes,
      t.nome AS tutor_nome, t.telefone AS tutor_telefone,
      p.nome AS pet_nome, p.especie, p.raca
      FROM agendamentos a
      INNER JOIN tutores t ON t.id=a.tutor_id
      INNER JOIN pets p ON p.id=a.pet_id
      ORDER BY a.data_agendamento ASC";
$rows=$conn->query($sql);
require __DIR__ . "/../includes/header.php";
?>
<h1 class="page-title">Agendamentos</h1>
<?php if ($msg==="ok"): ?><div class="alert success">Data do agendamento atualizada com sucesso.</div>
<?php elseif ($msg==="cad"): ?><div class="alert success">Agendamento cadastrado com sucesso.</div><?php endif; ?>
<div class="card">
  <div class="toolbar"><a class="btn" href="cadastrar.php">Novo agendamento</a></div>
  <?php if ($rows->num_rows===0): ?><p class="empty">Nenhum agendamento cadastrado.</p>
  <?php else: ?>
  <table class="data">
    <thead><tr><th>Data</th><th>Tutor</th><th>Pet</th><th>Serviço</th><th>Status</th><th>Ações</th></tr></thead>
    <tbody>
    <?php while ($a=$rows->fetch_assoc()): ?>
      <tr>
        <td><?= date("d/m/Y H:i", strtotime($a["data_agendamento"])) ?></td>
        <td><?= htmlspecialchars($a["tutor_nome"]) ?><br><small style="color:#64748b"><?= htmlspecialchars($a["tutor_telefone"]??"") ?></small></td>
        <td><?= htmlspecialchars($a["pet_nome"]) ?><br><small style="color:#64748b"><?= htmlspecialchars($a["especie"]." — ".($a["raca"]??"")) ?></small></td>
        <td><?= htmlspecialchars($a["servico"]) ?></td>
        <td><?= htmlspecialchars($a["status"]) ?></td>
        <td><a class="btn btn-sm" href="editar.php?id=<?= (int)$a["id"] ?>">Atualizar data</a></td>
      </tr>
    <?php endwhile; ?>
    </tbody>
  </table>
  <?php endif; ?>
</div>
<?php require __DIR__ . "/../includes/footer.php"; ?>
