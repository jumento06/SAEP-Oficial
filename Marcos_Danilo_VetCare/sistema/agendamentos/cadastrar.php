<?php
require_once __DIR__ . "/../config/conexao.php";
require_once __DIR__ . "/../config/sessao.php";
exigir_login();
$titulo_pagina="Novo agendamento"; $erro="";
$tutor_id=$pet_id=0; $servico=$status=$observacoes=$data_agendamento="";
$tutores=$conn->query("SELECT id,nome FROM tutores ORDER BY nome");
$pets=$conn->query("SELECT p.id,p.nome,p.especie,p.tutor_id,t.nome AS tutor_nome FROM pets p INNER JOIN tutores t ON t.id=p.tutor_id ORDER BY p.nome");
if ($_SERVER["REQUEST_METHOD"]==="POST") {
    $tutor_id=(int)($_POST["tutor_id"]??0); $pet_id=(int)($_POST["pet_id"]??0);
    $data_agendamento=trim($_POST["data_agendamento"]??"");
    $servico=trim($_POST["servico"]??""); $status=trim($_POST["status"]??"Agendado");
    $observacoes=trim($_POST["observacoes"]??"");
    if ($tutor_id<=0||$pet_id<=0||$data_agendamento===""||$servico==="") $erro="Tutor, pet, data e serviço são obrigatórios.";
    else {
        $data_sql=str_replace("T"," ",$data_agendamento);
        if (strlen($data_sql)===16) $data_sql.=":00";
        $stmt=$conn->prepare("INSERT INTO agendamentos (tutor_id,pet_id,data_agendamento,servico,status,observacoes) VALUES (?,?,?,?,?,?)");
        $stmt->bind_param("iissss",$tutor_id,$pet_id,$data_sql,$servico,$status,$observacoes);
        if ($stmt->execute()) { header("Location: listar.php?msg=cad"); exit; }
        $erro="Não foi possível cadastrar.";
    }
}
require __DIR__ . "/../includes/header.php";
?>
<h1 class="page-title">Novo agendamento</h1>
<?php if ($erro): ?><div class="alert error"><?= htmlspecialchars($erro) ?></div><?php endif; ?>
<div class="card">
<form method="post">
  <div class="form-grid">
    <div>
      <label for="tutor_id">Tutor *</label>
      <select id="tutor_id" name="tutor_id" required>
        <option value="">— Selecione —</option>
        <?php while ($t=$tutores->fetch_assoc()): ?>
          <option value="<?= (int)$t["id"] ?>" <?= $tutor_id===(int)$t["id"]?"selected":"" ?>><?= htmlspecialchars($t["nome"]) ?></option>
        <?php endwhile; ?>
      </select>
    </div>
    <div>
      <label for="pet_id">Pet *</label>
      <select id="pet_id" name="pet_id" required>
        <option value="">— Selecione —</option>
        <?php while ($p=$pets->fetch_assoc()): ?>
          <option value="<?= (int)$p["id"] ?>" <?= $pet_id===(int)$p["id"]?"selected":"" ?>><?= htmlspecialchars($p["nome"]." (".$p["especie"].") — ".$p["tutor_nome"]) ?></option>
        <?php endwhile; ?>
      </select>
    </div>
    <div><label for="data_agendamento">Data/hora *</label><input type="datetime-local" id="data_agendamento" name="data_agendamento" required value="<?= htmlspecialchars($data_agendamento) ?>"></div>
    <div><label for="servico">Serviço / consulta *</label><input type="text" id="servico" name="servico" required value="<?= htmlspecialchars($servico) ?>"></div>
    <div>
      <label for="status">Status</label>
      <select id="status" name="status">
        <?php foreach (["Agendado","Em andamento","Concluído"] as $st): ?>
          <option value="<?= $st ?>" <?= $status===$st?"selected":"" ?>><?= $st ?></option>
        <?php endforeach; ?>
      </select>
    </div>
    <div><label for="observacoes">Observações</label><input type="text" id="observacoes" name="observacoes" value="<?= htmlspecialchars($observacoes) ?>"></div>
  </div>
  <div class="form-actions">
    <button type="submit" class="btn">Cadastrar</button>
    <a class="btn btn-secondary" href="listar.php">Cancelar</a>
  </div>
</form>
</div>
<?php require __DIR__ . "/../includes/footer.php"; ?>
