<?php
session_start();

if (!isset($_SESSION['login_success'])) {
    header("Location: login.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="pt">
    <head>
        <meta charset="UTF-8">
        <title>Entrando...</title>
        <link rel="icon" href="../img/smartagro.png">
        <style>
            body {
                margin: 0;
                background-color: white;
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
            }
            img {
                width: 150px;
                height: 150px;
                border-radius: 50%;
                animation: fade 2s ease-in-out;
            }
            @keyframes fade {
                0% { opacity: 0; transform: scale(0.9); }
                100% { opacity: 1; transform: scale(1); }
            }
        </style>
    </head>
    <body>
        <img src="../img/smartagro.png" alt="Logo">
        <script>
            // Redireciona para o dashboard após 2.5 segundos
            setTimeout(function() {
                window.location.href = 'dashboard.php?login=sucesso';
            }, 3000);
        </script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </body>
</html>
