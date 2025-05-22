<?php
require_once '../db/conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST["nome"];
    $email = $_POST["email"];
    $telefone = $_POST["telefone"];
    $senha = password_hash($_POST["senha"], PASSWORD_DEFAULT);

    $sql = "INSERT INTO usuarios (nome, email, telefone, senha) VALUES (?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("ssss", $nome, $email, $telefone, $senha);
    if ($stmt->execute()) {
        echo "Usuário cadastrado com sucesso.";
    } else {
        echo "Erro ao cadastrar usuário.";
    }
}
?>
