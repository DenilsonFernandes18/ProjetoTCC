<?php
session_start();
require_once 'conexao.php';

header('Content-Type: application/json');

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode([]);
    exit;
}

$usuario_id = $_SESSION['usuario_id'];

$sql = "SELECT data_hora, status, origem FROM historico WHERE usuario_id = ? ORDER BY data_hora DESC LIMIT 20";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$result = $stmt->get_result();

$historico = [];
while ($row = $result->fetch_assoc()) {
    $historico[] = $row;
}

echo json_encode($historico);
?>
