<?php
require_once __DIR__ . "/../config/conexao.php";
require_once __DIR__ . "/../config/sessao.php";
exigir_login();
$id=(int)($_GET["id"]??$_POST["id"]??0);
if ($id<=0) { header("Location: listar.php"); exit; }
$titulo_pagina="Editar pet"; $erro="";
$tutores=$conn->query("SELECT id,nome FROM tutores ORDER BY nome");
if ($_SERVER["REQUEST_METHOD"]==="POST") {
    $tutor_id=(int)($_POST["tutor_id"]??0);
    $nome=trim($_POST["nome"]??""); $especie=trim($_POST["especie"]??"");
    $raca=trim($_POST["raca"]??""); $idade=trim($_POST["idade"]??"");
    if ($tutor_id<=0||$nome===""||$especie==="") $erro="Tutor, nome e espécie são obrigatórios.";
    else {
        $idade_v=$idade!==""?(int)$idade:null;
        $stmt=$conn->prepare("UPDATE pets SET tutor_id=?,nome=?,especie=?,raca=?,idade=? WHERE id=?");
        $stmt->bind_param("isssii",$tutor_id,$nome,$especie,$raca,$idade_v,$id);
        if ($stmt->execute()) { header("Location: listar.php?msg=ok"); exit; }
        $erro="Não foi possível salvar.";
    }
}
$stmt=$conn->prepare("SELECT * FROM pets WHERE id=? LIMIT 1");
$stmt->bind_param("i",$id); $stmt->execute();
$p=$stmt->get_result()->fetch_assoc();
if (!$p) { header("Location: listar.php"); exit; }
require __DIR__ . "/../includes/header.php";
?>
<h1 class="page-title">Editar pet #<?= (int)$p["id"] ?></h1>
<?php if ($erro): ?><div class="alert error"><?= htmlspecialchars($erro) ?></div><?php endif; ?>
<div class="card">
<form method="post">
  <input type="hidden" name="id" value="<?= (int)$p["id"] ?>">
  <div class="form-grid">
    <div>
      <label for="tutor_id">Tutor *</label>
      <select id="tutor_id" name="tutor_id" required>
        <?php $tutores->data_seek(0); while ($t=$tutores->fetch_assoc()): ?>
          <option value="<?= (int)$t["id"] ?>" <?= (int)$p["tutor_id"]===(int)$t["id"]?"selected":"" ?>><?= htmlspecialchars($t["nome"]) ?></option>
        <?php endwhile; ?>
      </select>
    </div>
    <div><label for="nome">Nome *</label><input type="text" id="nome" name="nome" required value="<?= htmlspecialchars($p["nome"]) ?>"></div>
    <div><label for="especie">Espécie *</label><input type="text" id="especie" name="especie" required value="<?= htmlspecialchars($p["especie"]) ?>"></div>
    <div><label for="raca">Raça</label><input type="text" id="raca" name="raca" value="<?= htmlspecialchars($p["raca"]??"") ?>"></div>
    <div><label for="idade">Idade (anos)</label><input type="number" id="idade" name="idade" min="0" max="40" value="<?= $p["idade"]!==null?(int)$p["idade"]:"" ?>"></div>
  </div>
  <div class="form-actions">
    <button type="submit" class="btn">Salvar alterações</button>
    <a class="btn btn-secondary" href="listar.php">Cancelar</a>
  </div>
</form>
</div>
<?php require __DIR__ . "/../includes/footer.php"; ?>
