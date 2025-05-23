<?php
session_start();
require_once 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    http_response_code(403);
    exit('Não autorizado');
}

$id = $_SESSION['usuario_id'];
$campo = $_POST['campo'];
$valor = trim($_POST['valor']);

$campos_permitidos = ['nome', 'email', 'telefone'];
$coluna = '';

switch ($campo) {
    case 'nome': $coluna = 'nome'; break;
    case 'email': $coluna = 'email'; break;
    case 'telefone': $coluna = 'telefone'; break;
    default: exit('Campo inválido');
}

$sql = "UPDATE usuarios SET $coluna = ? WHERE id = ?";
$stmt = $con->prepare($sql);
$stmt->bind_param("si", $valor, $id);
$stmt->execute();

$_SESSION["usuario_$coluna"] = $valor;

echo "Dados atualizados com sucesso.";
