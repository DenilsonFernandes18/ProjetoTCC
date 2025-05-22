<?php
require_once 'proteger.php';
?>


<!DOCTYPE html>
<html lang="pt">
    <head>
        <meta charset="UTF-8">
        <title>Painel - IoT Irrigação</title>
        <link rel="stylesheet" href="../css/style.css">
        <link rel="icon" href="../img/smartagro.png">
    </head>
    <body>
        <div style="display: flex;" class="top-bar">
            <div class="user">
                <h4>Bem-vindo, <span style="color:rgb(44, 111, 64)"><?php echo $_SESSION['usuario_nome']; ?>!</span></h4>
            </div>
            <div class="sair">
                <a href="logout.php">Sair</a>
            </div>
        </div>
        <!--Inicio Dashboard-->
        <div class="dashboard">
            <h1>Painel de Monitoramento</h1>
            <div class="card-container">
                <div class="card" id="tempCard">🌡️ Temperatura: 
                    <p id="temp" class="valor">--</p>
                    <small id="sensacaoTemp">Sensação: --</small>
                </div>
                <div class="card" id="umiCard">💧 Humidade do Solo: 
                    <p id="umi" class="valor">--</p>
                </div>
                <div class="card">🌱 Modo Automático: 
                    <p id="modoAutoTexto"></p>
                </div>
            </div>
            <!-- Botão para abrir a modal -->
            <div class="nav">
                <button  id="btnAbrirHistorico">Histórico</button> 
            </div>
        </div>
        <!--Fim Dashboard-->
        <!--Inicio Controle-->
        <div class="controle">
            <h1>Controle de Irrigação</h1>

            <button onclick="iniciarIrrigacao()" class="on">Ligar(ON)</button>
            <button onclick="pararIrrigacao()" class="off">Desligar(OFF)</button>

            <div class="switch-container">
                <span class="switch-label">Modo Automático</span>
                <label class="switch">
                    <input type="checkbox" id="modoAuto" checked>
                    <span class="slider"></span>
                </label>
            </div>
        </div>
        
        <!--Fim Controle-->
        <!-- Modal -->
        <div id="modalHistorico" class="modal">
            <div class="modal-conteudo">
                <span class="fechar" id="btnFecharModal">&times;</span>
                <!--Inicio Histórico-->
                <h2>Histórico de Atividades</h2>
                <table>
                    <thead>
                        <tr>
                            <th>🕒Data/Hora</th>
                            <th>⚙️Ação</th>
                            <th>💡Status</th>
                        </tr>
                    </thead>
                    <tbody id="Historico">
                    </tbody>
                </table>
                <button onclick="closeModal()" class="btnClose">Fechar</button>
            </div>
        </div>
        <script src="../js/status.js"></script>
        <script src="../js/ajax.js"></script>
        <script src="../js/ajaxHistorico.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="../js/script.js"></script>
        <script src="../js/swal.js"></script>
        <?php if (isset($_SESSION['login_success'])): ?>
            <script>
                Swal.fire({
                    icon: 'success',
                    title: 'Login realizado com sucesso!',
                    showConfirmButton: false,
                    timer: 2500
                });
            </script>
            <?php unset($_SESSION['login_success']); ?>
        <?php endif; ?>

    </body>
</html>

