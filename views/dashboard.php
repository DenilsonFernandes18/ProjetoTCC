<!DOCTYPE html>
<html lang="pt">
    <head>
        <meta charset="UTF-8">
        <title>Painel - IoT Irrigação</title>
        <link rel="stylesheet" href="../css/style.css">
        <link rel="icon" href="../img/smartagro.png">
    </head>
    
    <body>
        <div style="display: flex; gap: 8px;">
            <span>Bem-Vindo(a)</span>
            <p class="user">Denilson</p>
            <a href="logout.php" style="position: absolute; top: 10px; right: 35px; text-decoration: none; background: red; color: white; padding: 5px 10px; border-radius: 5px;">
                Sair
            </a>
        </div>
        <!--Inicio Dashboard-->
        <div class="dashboard">
            <h1>Painel de Monitoramento</h1>
            <div class="card-container">
                <div class="card" id="tempCard">
                    🌡️ Temperatura: <p id="temp">--</p>
                    <small id="sensacaoTemp">Sensação: --</small>
                </div>
                <div class="card" id="umiCard">💧 Humidade do Solo: <span id="umi">--</span></div>
                <div class="card">🌱 Modo Automático: <span id="modoAutoTexto"></span></div>
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
                            <th>Data/Hora</th>
                            <th>Ação</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>10/04 08:00</td>
                            <td>Início</td>
                            <td>Sucesso</td>
                        </tr>
                        <tr>
                            <td>10/04 08:10</td>
                            <td>Parada</td>
                            <td>Sucesso</td>
                        </tr>
                    </tbody>
                </table>
                <button onclick="closeModal()" class="btnClose">Fechar</button>
            </div>
        </div>
        
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="../js/script.js"></script>
        <script src="../js/swal.js"></script>
    </body>
</html>

