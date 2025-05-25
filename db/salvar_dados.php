<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $temperatura = $_POST['temperatura'];
    $umidade = $_POST['umidade'];
    $token = $_POST['token'];

    $con = new mysqli("localhost", "root", "", "iot_irrigacao");

    if ($con->connect_error) {
        die("Falha na conexão: " . $con->connect_error);
    }
    // Buscar o usuário com base no token
    $stmt = $con->prepare("SELECT id FROM usuarios WHERE api_token = ?");
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->bind_result($usuario_id);
    $stmt->fetch();
    $stmt->close();

    if (!$usuario_id) {
        echo "Token inválido";
        $con->close();
        exit;
    }

    // Inserir os dados
    $sql = "INSERT INTO sensores (temperatura, umidade, usuario_id) VALUES (?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ddi", $temperatura, $umidade, $usuario_id);

    if ($stmt->execute()) {
        echo "OK";
    } else {
        echo "Erro: " . $stmt->error;
    }
    echo "Erro: " . $conn->error;
    /*if (isset($_POST['status'])) { 
    $status = $_POST['status'];
    file_put_contents('status.txt', $status);
    echo "Status salvo: $status";
    exit;
    }
    */

    $stmt->close();
    $con->close();
}
?>
