<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alterar Senha</title>
    <link rel="stylesheet" href="../css/style_login.css">
    <link rel="icon" href="../img/smartagro.png">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat&display=swap" rel="stylesheet">
</head>
<body>
    <div class="center">
        <h2>Alterar Senha</h2>
        <form method="post" action="login.php">
          <div class="txt_field">
            <input type="text" name="login" required>
            <span></span>
            <label for="">Nova senha</label>
          </div>
          <div class="txt_field">
            <input type="password" name="senha" required>
            <span></span>
            <label for="">Confirmar senha</label>
          </div>
          <div class="criar">
                <input type="submit" value="Criar Conta">
          </div>
          
        </form>
      </div>
</body>
</html>
