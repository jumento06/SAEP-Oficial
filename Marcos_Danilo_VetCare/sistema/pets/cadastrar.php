<?php
require_once __DIR__ . "/../config/conexao.php";
require_once __DIR__ . "/../config/sessao.php";
exigir_login();
$titulo_pagina="Novo pet"; $erro=""; $tutor_id=0; $nome=$especie=$raca=""; $idade="";
$tutores=$conn->query("SELECT id,nome FROM tutores ORDER BY nome");
if ($_SERVER["REQUEST_METHOD"]==="POST") {
    $tutor_id=(int)($_POST["tutor_id"]??0);
    $nome=trim($_POST["nome"]??""); $especie=trim($_POST["especie"]??"");
    $raca=trim($_POST["raca"]??""); $idade=trim($_POST["idade"]??"");
    if ($tutor_id<=0) $erro="Selecione o tutor responsável pelo pet.";
    elseif ($nome===""||$especie==="") $erro="Nome e espécie são obrigatórios.";
    else {
        $idade_v=$idade!==""?(int)$idade:null;
        $stmt=$conn->prepare("INSERT INTO pets (tutor_id,nome,especie,raca,idade) VALUES (?,?,?,?,?)");
        $stmt->bind_param("isssi",$tutor_id,$nome,$especie,$raca,$idade_v);
        if ($stmt->execute()) { header("Location: listar.php?msg=cad"); exit; }
        $erro="Não foi possível cadastrar o pet.";
    }
}
require __DIR__ . "/../includes/header.php";
?>
<h1 class="page-title">Novo pet</h1>
<p style="color:#475569;margin-top:-.5rem;">Ao cadastrar, o pet deve ser associado a um tutor (RN07).</p>
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
    <div><label for="nome">Nome do pet *</label><input type="text" id="nome" name="nome" required value="<?= htmlspecialchars($nome) ?>"></div>
    <div><label for="especie">Espécie *</label><input type="text" id="especie" name="especie" required value="<?= htmlspecialchars($especie) ?>" placeholder="Cão, Gato..."></div>
    <div><label for="raca">Raça</label><input type="text" id="raca" name="raca" value="<?= htmlspecialchars($raca) ?>"></div>
    <div><label for="idade">Idade (anos)</label><input type="number" id="idade" name="idade" min="0" max="40" value="<?= htmlspecialchars((string)$idade) ?>"></div>
  </div>
  <div class="form-actions">
    <button type="submit" class="btn">Cadastrar</button>
    <a class="btn btn-secondary" href="listar.php">Cancelar</a>
  </div>
</form>
</div>
<?php require __DIR__ . "/../includes/footer.php"; ?>
