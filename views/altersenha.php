<?php
require_once '../db/conexao.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = $_POST["login"];
    $nova_senha = $_POST["senha"];

    $stmt = $con->prepare("SELECT id, senha FROM usuarios WHERE email = ? OR telefone = ?");
    $stmt->bind_param("ss", $login, $login);
    $stmt->execute();
    $resultado = $stmt->get_result();

    if ($resultado->num_rows === 1) {
        $usuario = $resultado->fetch_assoc();
        $id_usuario = (int)$usuario['id'];
        $senha_atual_hash = $usuario['senha'];

        // Verifica se a nova senha é igual à atual
        if (password_verify($nova_senha, $senha_atual_hash)) {
            $_SESSION['senha_error'] = "A nova senha é igual à senha atual. Por favor, escolha uma senha diferente.";
        } else {
            $senha_hash = password_hash($nova_senha, PASSWORD_DEFAULT);
            $update = $con->prepare("UPDATE usuarios SET senha = ? WHERE id = ?");
            $update->bind_param("si", $senha_hash, $id_usuario);

            if ($update->execute()) {
                $_SESSION['senha_sucesso'] = "Senha alterada com sucesso!";
            } else {
                $_SESSION['senha_error'] = "Erro ao alterar a senha: " . $update->error;
            }
        }
    } else {
        $_SESSION['senha_error'] = "Usuário não encontrado.";
    }
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Alterar Senha-SmartAgro</title>
      <link rel="stylesheet" href="../css/style_login.css">
      <link rel="icon" href="../img/smartagro.png">
      <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
  </head>
  <body>
      <div class="center">
          <h2>Alterar Senha</h2>
          <form method="post" action="altersenha.php">
            <div class="txt_field">
              <input type="text" name="login" required>
              <span></span>
              <label for="">E-mail ou Telefone</label>
            </div>
            <div class="txt_field">
              <input type="password" name="senha" required>
              <span></span>
              <label for="">Nova senha</label>
            </div>
            <div class="signup_link">
              <a href="login.php">Entrar na minha conta</a>
            </div>
            <div class="criar">
                  <input type="submit" value="Alterar Senha">
            </div><br>
            
          </form>
        </div>
        <?php include '../alertas/alertas.php'; ?>
  </body>
</html>
