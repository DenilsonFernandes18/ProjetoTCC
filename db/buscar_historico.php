<?php
include 'conexao.php';

$sql = "SELECT data_hora, origem, status FROM historico ORDER BY id DESC LIMIT 10";
$result = $con->query($sql);

$historico = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $historico[] = $row;
    }
}

echo json_encode($historico);
$con->close();
?>
