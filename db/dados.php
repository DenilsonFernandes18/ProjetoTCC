<?php
include 'conexao.php';

$sql = "SELECT temperatura, umidade, data_hora FROM sensores ORDER BY id DESC LIMIT 1";
$result = $con->query($sql);

if ($result->num_rows > 0) {
    echo json_encode($result->fetch_assoc());
} else {
    echo json_encode(["temperatura" => "--", "umidade" => "--"]);
}

$con->close();
?>




