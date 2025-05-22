<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $temperatura = $_POST['temperatura'];
    $umidade = $_POST['umidade'];

    $con = new mysqli("localhost", "root", "", "iot_irrigacao");

    if ($con->connect_error) {
        die("Falha na conexão: " . $con->connect_error);
    }

    $sql = "INSERT INTO sensores (temperatura, umidade) VALUES ('$temperatura', '$umidade')";

    if ($con->query($sql) === TRUE) {
        echo "OK";
    } else {
        echo "Erro: " . $conn->error;
    }
    /*if (isset($_POST['status'])) { 
    $status = $_POST['status'];
    file_put_contents('status.txt', $status);
    echo "Status salvo: $status";
    exit;
    }
    */

    $con->close();
}
?>
