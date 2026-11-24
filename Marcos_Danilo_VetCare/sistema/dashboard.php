<?php
require_once __DIR__ . "/config/conexao.php";
require_once __DIR__ . "/config/sessao.php";
exigir_login();
$u = usuario_logado();
$titulo_pagina = "Início";
$q_t = (int)$conn->query("SELECT COUNT(*) c FROM tutores")->fetch_assoc()["c"];
$q_p = (int)$conn->query("SELECT COUNT(*) c FROM pets")->fetch_assoc()["c"];
$q_a = (int)$conn->query("SELECT COUNT(*) c FROM agendamentos")->fetch_assoc()["c"];
require __DIR__ . "/includes/header.php";
?>
<h1 class="page-title">Olá, <?= htmlspecialchars($u["nome"]) ?>!</h1>
<p style="color:#475569;margin-top:-.5rem;margin-bottom:1.25rem;">
  Bem-vindo ao painel da clínica. Gerencie tutores, pets e agendamentos de consultas.
</p>
<div class="dashboard-grid">
  <div class="dash-card">
    <a href="tutores/listar.php">Tutores</a>
    <p><?= $q_t ?> cadastrado(s)<br>Busca, edição e CPF protegido</p>
  </div>
  <div class="dash-card">
    <a href="pets/listar.php">Pets</a>
    <p><?= $q_p ?> cadastrado(s)<br>Associados a um tutor</p>
  </div>
  <div class="dash-card">
    <a href="agendamentos/listar.php">Agendamentos</a>
    <p><?= $q_a ?> registro(s)<br>Ordenados por data</p>
  </div>
</div>
<div class="card" style="margin-top:1.5rem;">
  <h2 style="margin-top:0;font-size:1.1rem;color:#0c4a6e;">Acesso rápido</h2>
  <div class="toolbar">
    <a class="btn" href="tutores/listar.php">Gerenciar tutores</a>
    <a class="btn" href="pets/listar.php">Gerenciar pets</a>
    <a class="btn" href="agendamentos/listar.php">Gerenciar agendamentos</a>
    <a class="btn btn-secondary" href="logout.php">Sair do sistema</a>
  </div>
</div>
<?php require __DIR__ . "/includes/footer.php"; ?>
