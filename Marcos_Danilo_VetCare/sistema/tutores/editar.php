<?php
require_once __DIR__ . "/../config/conexao.php";
require_once __DIR__ . "/../config/crypto.php";
require_once __DIR__ . "/../config/sessao.php";
exigir_login();
$id = (int)($_GET["id"] ?? $_POST["id"] ?? 0);
if ($id<=0) { header("Location: listar.php"); exit; }
$erro=""; $titulo_pagina="Editar tutor";
if ($_SERVER["REQUEST_METHOD"]==="POST") {
    $nome=trim($_POST["nome"]??""); $cpf=trim($_POST["cpf"]??"");
    $telefone=trim($_POST["telefone"]??""); $email=trim($_POST["email"]??"");
    if ($nome===""||$cpf==="") $erro="Nome e CPF são obrigatórios.";
    else {
        $cpf_c=criptografar_cpf($cpf);
        $stmt=$conn->prepare("UPDATE tutores SET nome=?,cpf=?,telefone=?,email=? WHERE id=?");
        $stmt->bind_param("ssssi",$nome,$cpf_c,$telefone,$email,$id);
        if ($stmt->execute()) { header("Location: listar.php?msg=ok"); exit; }
        $erro="Não foi possível salvar.";
    }
}
$stmt=$conn->prepare("SELECT * FROM tutores WHERE id=? LIMIT 1");
$stmt->bind_param("i",$id); $stmt->execute();
$t=$stmt->get_result()->fetch_assoc();
if (!$t) { header("Location: listar.php"); exit; }
$cpf_ex=descriptografar_cpf($t["cpf"]);
if ($_SERVER["REQUEST_METHOD"]==="POST" && $erro) {
    $t["nome"]=$_POST["nome"]??$t["nome"]; $cpf_ex=$_POST["cpf"]??$cpf_ex;
    $t["telefone"]=$_POST["telefone"]??$t["telefone"]; $t["email"]=$_POST["email"]??$t["email"];
}
require __DIR__ . "/../includes/header.php";
?>
<h1 class="page-title">Editar tutor #<?= (int)$t["id"] ?></h1>
<?php if ($erro): ?><div class="alert error"><?= htmlspecialchars($erro) ?></div><?php endif; ?>
<div class="card">
<form method="post">
  <input type="hidden" name="id" value="<?= (int)$t["id"] ?>">
  <div class="form-grid">
    <div><label for="nome">Nome *</label><input type="text" id="nome" name="nome" required value="<?= htmlspecialchars($t["nome"]) ?>"></div>
    <div><label for="cpf">CPF * (armazenado criptografado)</label><input type="text" id="cpf" name="cpf" required value="<?= htmlspecialchars($cpf_ex) ?>" placeholder="000.000.000-00"></div>
    <div><label for="telefone">Telefone</label><input type="tel" id="telefone" name="telefone" value="<?= htmlspecialchars($t["telefone"]??"") ?>"></div>
    <div><label for="email">E-mail</label><input type="email" id="email" name="email" value="<?= htmlspecialchars($t["email"]??"") ?>"></div>
  </div>
  <div class="form-actions">
    <button type="submit" class="btn">Salvar alterações</button>
    <a class="btn btn-secondary" href="listar.php">Cancelar</a>
  </div>
</form>
</div>
<?php require __DIR__ . "/../includes/footer.php"; ?>
