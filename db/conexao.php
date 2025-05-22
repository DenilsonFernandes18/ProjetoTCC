<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "iot_irrigacao";

$con = new mysqli($host, $user, $pass, $db);

// Verificar conexão
if ($con->connect_error) {
    die("Erro de conexão: " .$con->connect_error);
}
?>
