<?php
require_once '../db/conexao.php';
session_start();

// Buscar gêneros disponíveis
$generos = [];
$result = $con->query("SHOW COLUMNS FROM usuarios LIKE 'genero'");
if ($result) {
    $row = $result->fetch_assoc();
    preg_match("/^enum\(\'(.*)\'\)$/", $row['Type'], $matches);
    if (!empty($matches[1])) {
        $generos = explode("','", $matches[1]);
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $telefone = $_POST['telefone'];
    $genero = $_POST['genero'];
    $senha = $_POST['senha'];

    // Verifica se o e-mail ou telefone já existem
    $verifica = $con->prepare("SELECT id FROM usuarios WHERE email = ? OR telefone = ?");
    $verifica->bind_param("ss", $email, $telefone);
    $verifica->execute();
    $verifica->store_result();

    if ($verifica->num_rows > 0) {
        $_SESSION['criar_error'] = "E-mail ou telefone já cadastrado!";
    } else {
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        $stmt = $con->prepare("INSERT INTO usuarios (nome, email, telefone, genero, senha) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $nome, $email, $telefone, $genero, $senha_hash);

        if ($stmt && $stmt->execute()) {
            $novo_usuario_id = $con->insert_id;

            // Cria entrada na tabela modo_automatico
            $stmt2 = $con->prepare("INSERT INTO modo_automatico (usuario_id, estado, updated) VALUES (?, 0, NOW())");
            if ($stmt2) {
                $stmt2->bind_param("i", $novo_usuario_id);
                $stmt2->execute();
            }

            $_SESSION['criar_sucesso'] = "Conta criada com sucesso!";
            
        } else {
            $_SESSION['criar_error'] = "Erro ao criar usuário.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Criar Conta-SmartAgro</title>
        <link rel="stylesheet" href="../css/style_login.css">
        <link rel="icon" href="../img/smartagro.png">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
    </head>
    <body>
      <div class="center">
        <h2>Criar Conta</h2>
        <form method="post" action="criar_usuario.php">
          <div class="txt_field">
            <input type="text" name="nome" required autocomplete="name">
            <span></span>
            <label>Nome</label>
          </div>
          <div class="txt_field">
            <input type="email" name="email" required autocomplete="email">
            <span></span>
            <label>E-mail</label>
          </div>
          <div class="txt_field">
            <input type="text" name="telefone" pattern="\d{9}" title="Insira 9 dígitos" maxlength="9" required>
            <span></span>
            <label>Telefone</label>
          </div>
          <div class="txt_field ">
            <select name="genero" required>
              <option value="" disabled selected hidden>Selecione o gênero</option>
              <?php foreach ($generos as $g): ?>
                <option value="<?= $g ?>"><?= ucfirst($g) ?></option>
              <?php endforeach; ?>
            </select>
            <span></span>
          </div>

          <div class="txt_field">
            <input type="password" name="senha" required autocomplete="new-password">
            <span></span>
            <label>Senha</label>
          </div>
          <div class="signup_link">
            Já possui uma conta?<a href="login.php">Entrar</a>
          </div>
          <div class="criar">
                <input type="submit" value="Criar Conta">
          </div><br>
          
        </form>
      </div>
      

      <?php include '../alertas/alertas.php'; ?>

    </body>
</html>