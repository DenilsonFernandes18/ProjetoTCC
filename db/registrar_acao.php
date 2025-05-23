<?php
session_start();
require_once 'conexao.php'; // Caminho correto para conexao.php

// Verifica se o usuário está autenticado
if (!isset($_SESSION['usuario_id'])) {
    http_response_code(401);
    echo 'Usuário não autenticado.';
    exit;
}

// Verifica se os dados foram enviados corretamente
if (!isset($_POST['status']) || !isset($_POST['origem'])) {
    http_response_code(400);
    echo 'Dados incompletos.';
    exit;
}

// Filtra os dados recebidos
$status = $_POST['status'];
$origem = $_POST['origem'];
$usuario_id = $_SESSION['usuario_id'];

// Insere no banco de dados
$sql = "INSERT INTO historico (status, origem, usuario_id) VALUES (?, ?, ?)";
$stmt = $con->prepare($sql);
$stmt->bind_param("ssi", $status, $origem, $usuario_id);

if ($stmt->execute()) {
    echo 'Ação registrada com sucesso.';
} else {
    http_response_code(500);
    echo 'Erro ao registrar ação: ' . $stmt->error;
}
?>


