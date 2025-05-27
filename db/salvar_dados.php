<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $temperatura = $_POST['temperatura'];
    $umidade    = $_POST['umidade'];

    // Conexão
    $con = new mysqli("localhost", "root", "", "iot_irrigacao");
    if ($con->connect_error) {
        die("Falha na conexão: " . $con->connect_error);
    }

    // Inserir dados em sensores
    $sql  = "INSERT INTO sensores (temperatura, umidade, data_hora) VALUES (?, ?, NOW())";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("dd", $temperatura, $umidade);

    if ($stmt->execute()) {
        echo "OK";
    } else {
        echo "Erro ao salvar sensor: " . $stmt->error;
    }

    $stmt->close();
    $con->close();
}
?>

