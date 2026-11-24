<?php
require_once __DIR__ . "/../config/conexao.php";
require_once __DIR__ . "/../config/sessao.php";
exigir_login();
$id=(int)($_GET["id"]??$_POST["id"]??0);
if ($id<=0) { header("Location: listar.php"); exit; }
$titulo_pagina="Atualizar data do agendamento"; $erro="";
if ($_SERVER["REQUEST_METHOD"]==="POST") {
    $data=trim($_POST["data_agendamento"]??"");
    if ($data==="") $erro="Informe a nova data/hora.";
    else {
        $data_sql=str_replace("T"," ",$data);
        if (strlen($data_sql)===16) $data_sql.=":00";
        $stmt=$conn->prepare("UPDATE agendamentos SET data_agendamento=? WHERE id=?");
        $stmt->bind_param("si",$data_sql,$id);
        if ($stmt->execute()) { header("Location: listar.php?msg=ok"); exit; }
        $erro="Não foi possível atualizar a data.";
    }
}
$sql="SELECT a.*, t.nome AS tutor_nome, p.nome AS pet_nome, p.especie, p.raca
      FROM agendamentos a INNER JOIN tutores t ON t.id=a.tutor_id INNER JOIN pets p ON p.id=a.pet_id
      WHERE a.id=? LIMIT 1";
$stmt=$conn->prepare($sql); $stmt->bind_param("i",$id); $stmt->execute();
$ag=$stmt->get_result()->fetch_assoc();
if (!$ag) { header("Location: listar.php"); exit; }
$data_valor=date("Y-m-d\TH:i", strtotime($ag["data_agendamento"]));
require __DIR__ . "/../includes/header.php";
?>
<h1 class="page-title">Atualizar data — agendamento #<?= (int)$ag["id"] ?></h1>
<?php if ($erro): ?><div class="alert error"><?= htmlspecialchars($erro) ?></div><?php endif; ?>
<div class="card">
  <p><strong>Tutor:</strong> <?= htmlspecialchars($ag["tutor_nome"]) ?></p>
  <p><strong>Pet:</strong> <?= htmlspecialchars($ag["pet_nome"]." (".$ag["especie"]." — ".($ag["raca"]??"").")") ?></p>
  <p><strong>Serviço:</strong> <?= htmlspecialchars($ag["servico"]) ?></p>
  <p><strong>Status:</strong> <?= htmlspecialchars($ag["status"]) ?></p>
  <form method="post" style="margin-top:1rem;">
    <input type="hidden" name="id" value="<?= (int)$ag["id"] ?>">
    <div class="form-grid">
      <div>
        <label for="data_agendamento">Nova data/hora *</label>
        <input type="datetime-local" id="data_agendamento" name="data_agendamento" required value="<?= htmlspecialchars($data_valor) ?>">
      </div>
    </div>
    <div class="form-actions">
      <button type="submit" class="btn">Salvar nova data</button>
      <a class="btn btn-secondary" href="listar.php">Cancelar</a>
    </div>
  </form>
</div>
<?php require __DIR__ . "/../includes/footer.php"; ?>
