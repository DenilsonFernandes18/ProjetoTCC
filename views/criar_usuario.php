<?php
/*
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
*/
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Criar Conta</title>
        <link rel="stylesheet" href="../css/style_login.css">
        <link rel="icon" href="../img/smartagro.png">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
    </head>
    <body>
        <div class="center">
        <h2>Criar Conta</h2>
        <form method="post" action="login.php">
          <div class="txt_field">
            <input type="text" name="login" required>
            <span></span>
            <label for="">Email ou Telefone</label>
          </div>
          <div class="txt_field">
            <input type="password" name="senha" required>
            <span></span>
            <label for="">Senha</label>
          </div>
          <div class="criar">
                <input type="submit" value="Criar Conta">
          </div>
          
        </form>
      </div>
    </body>
</html>