<?php
require_once __DIR__ . "/config/conexao.php";
require_once __DIR__ . "/config/sessao.php";
if (!empty($_SESSION["usuario_id"])) { header("Location: dashboard.php"); exit; }
$erro = "";
if (isset($_GET["expirado"])) $erro = "Sua sessão expirou por inatividade. Faça login novamente.";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? "");
    $senha = trim($_POST["senha"] ?? "");
    if ($email === "" || $senha === "") {
        $erro = "Informe e-mail e senha.";
    } else {
        $stmt = $conn->prepare("SELECT id, nome, email, senha, perfil FROM usuarios WHERE email = ? LIMIT 1");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $res = $stmt->get_result();
        if ($res->num_rows === 1) {
            $u = $res->fetch_assoc();
            if (password_verify($senha, $u["senha"])) {
                $_SESSION["usuario_id"] = $u["id"];
                $_SESSION["usuario_nome"] = $u["nome"];
                $_SESSION["usuario_email"] = $u["email"];
                $_SESSION["usuario_perfil"] = $u["perfil"];
                $_SESSION["ultimo_acesso"] = time();
                header("Location: dashboard.php");
                exit;
            }
        }
        $erro = "E-mail ou senha inválidos.";
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>Login — VetCare</title>
<link rel="stylesheet" href="css/style.css">
</head>
<body class="login-page">
<div class="login-card">
  <h1>VetCare</h1>
  <p>Clínica Veterinária — Tutores, Pets e Agendamentos</p>
  <?php if ($erro): ?><div class="alert error"><?= htmlspecialchars($erro) ?></div><?php endif; ?>
  <form method="post" autocomplete="off">
    <label for="email">E-mail</label>
    <input type="email" id="email" name="email" required value="<?= htmlspecialchars($_POST["email"] ?? "") ?>">
    <label for="senha">Senha</label>
    <input type="password" id="senha" name="senha" required>
    <button type="submit">Entrar</button>
  </form>
</div>
</body>
</html>
