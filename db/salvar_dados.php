<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $temperatura = $_POST['temperatura'];
    $umidade = $_POST['umidade'];

    $conn = new mysqli("localhost", "root", "", "iot_irrigacao");

    if ($conn->connect_error) {
        die("Falha na conexão: " . $conn->connect_error);
    }

    $sql = "INSERT INTO sensores (temperatura, umidade) VALUES ('$temperatura', '$umidade')";

    if ($conn->query($sql) === TRUE) {
        echo "OK";
    } else {
        echo "Erro: " . $conn->error;
    }

    $conn->close();
}
?>
