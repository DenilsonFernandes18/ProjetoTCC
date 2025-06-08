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

// Verifica o último status e se foi uma ação manual recente
$sql = "SELECT status, origem, data_hora FROM historico WHERE usuario_id = ? AND origem IN ('usuario', 'automatica') ORDER BY id DESC LIMIT 1";
$stmt = $con->prepare($sql);
$stmt->bind_param("i", $usuario_id);
$stmt->execute();
$stmt->bind_result($ultimoStatus, $ultimaOrigem, $ultimaDataHora);
$stmt->fetch();
$stmt->close();

// Se a última ação foi do usuário e ocorreu nos últimos 5 segundos, não executa ação automática
if ($ultimaOrigem === 'usuario') {
    $ultimaData = new DateTime($ultimaDataHora);
    $agora = new DateTime();
    $diferenca = $ultimaData->diff($agora);
    
    // Se a última ação manual foi há menos de 10 segundos, respeita a decisão do usuário
    if ($diferenca->s < 10) {
        exit('Respeitando ação manual recente.');
    }
}

// Se não houver mudança de status necessária, não registra
if ($ultimoStatus === $status) {
    exit('Sem mudança de status necessária.');
}

// Verifica se o último status foi desligado manualmente
if (isset($_POST['ultimo_status']) && $_POST['ultimo_status'] === 'desligada') {
    exit('Sistema foi desligado manualmente. Modo automático suspenso.');
}

// Inserir na tabela sensores
$sqlSensor = "INSERT INTO sensores (temperatura, umidade) VALUES (?, ?)";
$stmtSensor = $con->prepare($sqlSensor);
$stmtSensor->bind_param("dd", $temperatura, $umidade);
$stmtSensor->execute();
$sensor_id = $stmtSensor->insert_id;
$stmtSensor->close();

// Inserir na tabela historico com sensor_id - usando prepared statement
$sqlHist = "INSERT INTO historico (usuario_id, sensor_id, status, origem, data_hora) VALUES (?, ?, ?, 'automatica', NOW())";
$stmtHist = $con->prepare($sqlHist);
$stmtHist->bind_param("iis", $usuario_id, $sensor_id, $status);
$inseriu = $stmtHist->execute();
$stmtHist->close();

if ($inseriu) {
    echo "Ação automática registrada: $status";
} else {
    echo "Erro ao registrar no histórico.";
}





