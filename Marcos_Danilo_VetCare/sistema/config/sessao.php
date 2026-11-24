<?php
if (session_status() === PHP_SESSION_NONE) session_start();
define("SESSAO_TIMEOUT", 1800);

function base_url(string $path = ""): string {
    $script = str_replace("\\", "/", dirname($_SERVER["SCRIPT_NAME"] ?? ""));
    if (preg_match("#/(tutores|pets|agendamentos)$#", $script)) {
        $script = dirname($script);
    }
    $base = rtrim($script, "/");
    if ($base === "" || $base === ".") $base = "";
    $path = ltrim($path, "/");
    return $base . ($path !== "" ? "/" . $path : "");
}

function exigir_login(): void {
    if (empty($_SESSION["usuario_id"])) {
        header("Location: " . base_url("login.php"));
        exit;
    }
    $agora = time();
    if (isset($_SESSION["ultimo_acesso"]) && ($agora - (int)$_SESSION["ultimo_acesso"]) > SESSAO_TIMEOUT) {
        session_unset(); session_destroy();
        header("Location: " . base_url("login.php") . "?expirado=1");
        exit;
    }
    $_SESSION["ultimo_acesso"] = $agora;
}

function usuario_logado(): ?array {
    if (empty($_SESSION["usuario_id"])) return null;
    return [
        "id" => $_SESSION["usuario_id"],
        "nome" => $_SESSION["usuario_nome"] ?? "",
        "email" => $_SESSION["usuario_email"] ?? "",
        "perfil" => $_SESSION["usuario_perfil"] ?? "",
    ];
}
