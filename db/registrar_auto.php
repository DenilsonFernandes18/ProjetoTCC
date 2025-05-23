<?php
require_once 'conexao.php';
require_once '../views/proteger.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $temperatura = floatval($_POST['temperatura']);
    $umidade = floatval($_POST['umidade']);
    $modoAutomatico = filter_var($_POST['modo_automatico'], FILTER_VALIDATE_BOOLEAN);
    $usuario_id = $_SESSION['usuario_id'];

    if ($modoAutomatico) {
        if ($temperatura >= 15 && $temperatura <= 30 && $umidade <= 34) {
            $status = "ligada";
        } elseif ($umidade > 34) {
            $status = "desligada";
        } else {
            exit; // Fora dos critérios — nenhuma ação
        }

        // Verificar se já foi registrado o mesmo status recentemente
        $verificar = $con->prepare("SELECT status FROM historico WHERE usuario_id = ? ORDER BY id DESC LIMIT 1");
        $verificar->bind_param("i", $usuario_id);
        $verificar->execute();
        $result = $verificar->get_result();
        $linha = $result->fetch_assoc();
        $ultimo = $linha['status'] ?? null;


        if ($ultimo !== $status) {
            $inserir = $con->prepare("INSERT INTO historico (usuario_id, status, origem) VALUES (?, ?, 'automatica')");
            $inserir->execute([$usuario_id, $status]);
            echo "Ação automática registrada: $status";
        } else {
            echo "Sem mudança de status.";
        }
    } else {
        echo "Modo automático desativado.";
    }
}
?>
