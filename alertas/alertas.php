<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<?php
$alertas = [
    'senha_error' => ['icon' => 'error', 'title' => 'Erro ao alterar a senha.'],
    'criar_error' => ['icon' => 'error', 'title' => 'Erro ao criar usuário.'],
    'senha_sucesso' => ['icon' => 'success', 'title' => 'Senha alterada com sucesso!'],
    'criar_sucesso' => ['icon' => 'success', 'title' => 'Conta criada com sucesso!'],
];

foreach ($alertas as $key => $data) {
    if (isset($_SESSION[$key])) {
        $mensagem = $_SESSION[$key];
        unset($_SESSION[$key]);

        echo "<script>
            Swal.fire({
                icon: '{$data['icon']}',
                title: '{$data['title']}',
                text: '{$mensagem}',
                timer: 2000,
                showConfirmButton: false,
                timerProgressBar: true,
                didClose: () => {
                    " . (strpos($key, '_sucesso') !== false ? "window.location.href = 'login.php';" : "") . "
                }
            });
        </script>";
    }
}
?>

