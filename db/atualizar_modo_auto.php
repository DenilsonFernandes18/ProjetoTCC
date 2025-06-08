<?php
require_once 'conexao.php';
session_start();

if (!isset($_SESSION['usuario_id'])) {
    echo json_encode(['success' => false]);
    exit;
}

$estado = isset($_POST['estado']) ? intval($_POST['estado']) : null;
if (!in_array($estado, [0, 1], true)) {
    echo json_encode(['success' => false]);
    exit;
}

$stmt = $con->prepare("
    INSERT INTO modo_automatico (usuario_id, estado, updated)
    VALUES (?, ?, NOW())
    ON DUPLICATE KEY UPDATE estado = VALUES(estado), updated = NOW()
");
$stmt->bind_param("ii", $_SESSION['usuario_id'], $estado);
$sucesso = $stmt->execute();

echo json_encode(['success' => $sucesso]);
