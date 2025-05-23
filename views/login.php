<?php
  session_start();
  
  if (isset($_SESSION['usuario_id'])) {
      header("Location: dashboard.php");
      exit;
  }

  require_once '../db/conexao.php';

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
      $login = $_POST["login"];
      $senha = $_POST["senha"];

      $sql = "SELECT * FROM usuarios WHERE email = ? OR telefone = ?";
      $stmt = $con->prepare($sql);
      $stmt->bind_param("ss", $login, $login);
      $stmt->execute();
      $resultado = $stmt->get_result();

      if ($resultado->num_rows === 1) {
          $usuario = $resultado->fetch_assoc();

          if (password_verify($senha, $usuario['senha'])) {
              $_SESSION['usuario_id'] = $usuario['id'];
              $_SESSION['usuario_nome'] = $usuario['nome'];
              $_SESSION['login_success'] = true;
              header("Location: dashboard.php");
              exit;
          } else {
              // Senha incorreta
              $_SESSION['login_error'] = "Usuário ou Senha incorretos!";
          }
      } else {
          // Usuário não encontrado
          $_SESSION['login_error'] = "Usuário ou Senha incorretos!";
      }
  }

?>

<!DOCTYPE html>
<html lang="pt">
  <head>
    <meta charset="UTF-8">
    <title>Login</title>
    <link rel="stylesheet" href="../css/style_login.css">
    <link rel="icon" href="../img/smartagro.png">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
  </head>
  <body>
      <div class="center">
        <h2>Login</h2>
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
          <div class="pass"><a href="altersenha.php">Esqueceu a senha?</a></div>
          <input type="submit" value="Entrar">
          <div class="signup_link">
            Já possui uma conta?<a href="criar_usuario.php">Criar conta</a>
          </div>
        </form>
      </div>
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      <?php if (isset($_SESSION['login_error'])): ?>
        <script>
          Swal.fire({
              icon: 'error',
              title: 'Erro no login',
              text: '<?php echo $_SESSION['login_error']; ?>',
              confirmButtonText: 'Tentar novamente'
          });
        </script>
        <?php unset($_SESSION['login_error']); ?>
      <?php endif; ?>
    
  </body>
</html>
