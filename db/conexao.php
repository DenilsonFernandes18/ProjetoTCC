<?php
    $host = "localhost";
    $user = "root";         // ou seu usuário do MySQL
    $pass = "";             // ou sua senha do MySQL
    $db = "irrigacao";      // nome do banco de dados

    $conn = new mysqli($host, $user, $pass, $db);
    if ($conn->connect_error) {
    die("Erro de conexão: " . $conn->connect_error);
    }

    $sql = "SELECT temperatura, umidade, data_hora FROM sensores ORDER BY id DESC LIMIT 1";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
    echo json_encode($result->fetch_assoc());
    } else {
    echo json_encode(["temperatura" => "N/A", "humidade" => "N/A"]);
    }
    
?>
