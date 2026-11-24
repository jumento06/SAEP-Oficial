<?php
if (!isset($titulo_pagina)) $titulo_pagina = "VetCare";
$u = usuario_logado();
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= htmlspecialchars($titulo_pagina) ?> — VetCare</title>
<link rel="stylesheet" href="<?= htmlspecialchars(base_url("css/style.css")) ?>">
</head>
<body>
<header class="topbar">
  <div class="brand"><a href="<?= htmlspecialchars(base_url("dashboard.php")) ?>">VetCare</a></div>
  <?php if ($u): ?>
  <nav class="nav">
    <a href="<?= htmlspecialchars(base_url("dashboard.php")) ?>">Início</a>
    <a href="<?= htmlspecialchars(base_url("tutores/listar.php")) ?>">Tutores</a>
    <a href="<?= htmlspecialchars(base_url("pets/listar.php")) ?>">Pets</a>
    <a href="<?= htmlspecialchars(base_url("agendamentos/listar.php")) ?>">Agendamentos</a>
  </nav>
  <div class="userbox">
    <span class="user-name"><?= htmlspecialchars($u["nome"]) ?></span>
    <span class="user-perfil">(<?= htmlspecialchars($u["perfil"]) ?>)</span>
    <a class="btn-sair" href="<?= htmlspecialchars(base_url("logout.php")) ?>">Sair</a>
  </div>
  <?php endif; ?>
</header>
<main class="container">
