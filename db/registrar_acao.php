<?php
session_start();
require_once 'conexao.php'; // define $con

// Verifica sessão
if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo 'Usuário não autenticado.';
    exit;
}

// Verifica dados
if (!isset($_POST['status']) || !isset($_POST['origem'])) {
    http_response_code(400);
    echo 'Dados incompletos.';
    exit;
}

$status     = $_POST['status'];
$origem     = $_POST['origem'];
$usuario_id = $_SESSION['usuario_id'];

// Busca o último sensor inserido (para associar o sensor_id)
$result = $con->query("SELECT id FROM sensores ORDER BY id DESC LIMIT 1");
$sensor_id = ($row = $result->fetch_assoc()) ? $row['id'] : null;

// Monta o INSERT incluindo sensor_id e usuário
$sql  = "INSERT INTO historico (status, origem, usuario_id, sensor_id, data_hora) 
         VALUES (?, ?, ?, ?, NOW())";
$stmt = $con->prepare($sql);
$stmt->bind_param("ssii", $status, $origem, $usuario_id, $sensor_id);

if ($stmt->execute()) {
    echo 'Ação registrada com sucesso.';
} else {
    http_response_code(500);
    echo 'Erro ao registrar ação: ' . $stmt->error;
}

$stmt->close();
$con->close();
?>


