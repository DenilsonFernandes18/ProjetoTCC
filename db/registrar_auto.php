<?php
session_start();

require_once 'conexao.php';

if (!isset($_SESSION['usuario_id'])) {
    exit('Usuário não autenticado.');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit("Método inválido.");
}

// Verifica se recebeu todos os dados
if (
    !isset($_POST['temperatura']) ||
    !isset($_POST['umidade']) ||
    !isset($_POST['modo_automatico'])
) {
    exit('Dados incompletos.');
}

$temperatura = floatval($_POST['temperatura']);
$umidade = floatval($_POST['umidade']);
$modoAutomatico = ($_POST['modo_automatico'] === '1' || strtolower($_POST['modo_automatico']) === 'true');
$usuario_id = $_SESSION['usuario_id'];

if (!$modoAutomatico) {
    exit('Modo automático desativado.');
}

if ($temperatura >= 15 && $temperatura <= 30 && $umidade <= 34) {
    $status = "ligada";
} elseif ($umidade > 34) {
    $status = "desligada";
} else {
    exit('Condições fora dos critérios.');
}

// Verifica o último status
$sql = "SELECT status FROM historico WHERE usuario_id = ? ORDER BY id DESC LIMIT 1";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$stmt->bind_result($ultimoStatus);
$stmt->fetch();
$stmt->close();

if ($ultimoStatus === $status) {
    exit('Sem mudança de status.');
}

// Inserir na tabela sensores
$sqlSensor = "INSERT INTO sensores (temperatura, umidade) VALUES (?, ?)";
$stmtSensor = $con->prepare($sqlSensor);
$stmtSensor->bind_param("id",  $temperatura, $umidade);
$stmtSensor->execute();
$sensor_id = $stmtSensor->insert_id;
$stmtSensor->close();

// Inserir na tabela historico com sensor_id
$sqlHist = "INSERT INTO historico (usuario_id, sensor_id, status, origem) VALUES (?, ?, ?, 'automatica')";
$stmtHist = $con->prepare($sqlHist);
$stmtHist->bind_param("iis", $usuario_id, $sensor_id, $status);
$inseriu = $stmtHist->execute();
$stmtHist->close();

if ($inseriu) {
    echo "Ação automática registrada: $status";
} else {
    echo "Erro ao registrar no histórico.";
}





