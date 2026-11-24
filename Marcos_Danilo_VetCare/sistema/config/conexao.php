<?php
$servidor = "localhost";
$usuario  = "root";
$senha    = "";
$banco    = "vetcare";
$porta    = 3307; 

$conn = new mysqli($servidor, $usuario, $senha, $banco, $porta);
if ($conn->connect_error) {
    die("Erro ao conectar ao banco de dados: " . $conn->connect_error);
}
$conn->set_charset("utf8mb4");
