<?php
require_once __DIR__ . "/../config/conexao.php";
require_once __DIR__ . "/../config/crypto.php";
require_once __DIR__ . "/../config/sessao.php";
exigir_login();
$titulo_pagina="Novo tutor"; $erro=""; $nome=$cpf=$telefone=$email="";
if ($_SERVER["REQUEST_METHOD"]==="POST") {
    $nome=trim($_POST["nome"]??""); $cpf=trim($_POST["cpf"]??"");
    $telefone=trim($_POST["telefone"]??""); $email=trim($_POST["email"]??"");
    if ($nome===""||$cpf==="") $erro="Nome e CPF são obrigatórios.";
    else {
        $cpf_c=criptografar_cpf($cpf);
        $stmt=$conn->prepare("INSERT INTO tutores (nome,cpf,telefone,email) VALUES (?,?,?,?)");
        $stmt->bind_param("ssss",$nome,$cpf_c,$telefone,$email);
        if ($stmt->execute()) { header("Location: listar.php?msg=cad"); exit; }
        $erro="Não foi possível cadastrar.";
    }
}
require __DIR__ . "/../includes/header.php";
?>
<h1 class="page-title">Novo tutor</h1>
<?php if ($erro): ?><div class="alert error"><?= htmlspecialchars($erro) ?></div><?php endif; ?>
<div class="card">
<form method="post">
  <div class="form-grid">
    <div><label for="nome">Nome *</label><input type="text" id="nome" name="nome" required value="<?= htmlspecialchars($nome) ?>"></div>
    <div><label for="cpf">CPF * (será criptografado)</label><input type="text" id="cpf" name="cpf" required value="<?= htmlspecialchars($cpf) ?>" placeholder="000.000.000-00"></div>
    <div><label for="telefone">Telefone</label><input type="tel" id="telefone" name="telefone" value="<?= htmlspecialchars($telefone) ?>"></div>
    <div><label for="email">E-mail</label><input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>"></div>
  </div>
  <div class="form-actions">
    <button type="submit" class="btn">Cadastrar</button>
    <a class="btn btn-secondary" href="listar.php">Cancelar</a>
  </div>
</form>
</div>
<?php require __DIR__ . "/../includes/footer.php"; ?>
