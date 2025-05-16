<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "iot_irrigacao";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
}

$sql = "SELECT data_hora, origem, status FROM historico ORDER BY id DESC LIMIT 10";
$result = $conn->query($sql);

$historico = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $historico[] = $row;
    }
}

echo json_encode($historico);
$conn->close();
?>
