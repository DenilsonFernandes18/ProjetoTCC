<?php
require_once 'proteger.php';
require_once '../db/conexao.php';


$modo_auto_db = false;

$stmt = $con->prepare("SELECT estado FROM modo_automatico WHERE usuario_id = ?");
$stmt->bind_param("i", $_SESSION['usuario_id']);
$stmt->execute();
$stmt->bind_result($estado);
if ($stmt->fetch()) {
    $modo_auto_db = (bool)$estado;
}
$stmt->close();


?>

<!DOCTYPE html>
<html lang="pt">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Painel - IoT Irrigação</title>
        <link rel="stylesheet" href="../css/style.css">
        <link rel="icon" href="../img/smartagro.png">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
        <script>window.MODO_AUTO_INICIAL = <?= $modo_auto_db ? 'true' : 'false' ?>;</script>
    </head>
    <body>
        <div style="display: flex;" class="top-bar">
            <div class="sair">
                <a href="#" id="btnAbrirEditar">Editar Dados</a>
                <span><a href="logout.php">Sair</a></span>
            </div>
        </div>
        <!--Inicio Dashboard-->
        <div class="dashboard">
            <div class="user">
                <h4>Bem-vindo, <span style="color:rgb(44, 111, 64)"><?php echo $_SESSION['usuario_nome']; ?>!</span></h4>
            </div>
            
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
            <button id="ligar" onclick="iniciarIrrigacao()" class="on">Ligar(ON)</button>
            <button id="desligar" onclick="pararIrrigacao()" class="off">Desligar(OFF)</button>
            <div class="switch-container">
                <span class="switch-label">Modo Automático</span>
                <label class="switch">
                    <input type="checkbox" id="modoAuto">
                    <span class="slider"></span>
                </label>
            </div>
        </div>
        
        <!--Fim Controle-->

        <!-- Modal Histórico -->
        <div id="modalHistorico" class="modal">
            <div class="modal-conteudo">
                <span class="fechar" id="btnFecharModal">&times;</span>
                <!--Inicio Histórico-->
                <h2>Histórico de Actividades</h2>
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

        <!-- Modal de edição -->
        <div id="modalEditar" class="modal">
            <div class="modal-conteudo">
                <span class="fechar" onclick="document.getElementById('modalEditar').style.display='none'">&times;</span>
                <h2>Editar Dados</h2>
                <table>
                <tr>
                    <td><i style="cursor:pointer" class="bi bi-pencil-square" onclick="abrirSubModal('nome')"></i></td>
                    <td>Nome</td>
                    <td><?php echo $_SESSION['usuario_nome']; ?></td>
                </tr>
                <tr>
                    <td><i style="cursor:pointer"  class="bi bi-pencil-square" onclick="abrirSubModal('email')"></i></td>
                    <td>Email</td>
                    <td><?php echo $_SESSION['usuario_email']; ?></td>
                </tr>
                <tr>
                    <td><i style="cursor:pointer"  class="bi bi-pencil-square" onclick="abrirSubModal('telefone')"></i></td>
                    <td>Telefone</td>
                    <td><?php echo $_SESSION['usuario_telefone']; ?></td>
                </tr>
                </table>
            </div>
        </div>

        <!-- Submodal de edição individual -->
        <div id="modalCampo" class="modal">
            <div class="modal-conteudo">
                <span class="fechar" onclick="document.getElementById('modalCampo').style.display='none'">&times;</span>
                <h3 id="tituloCampo"></h3>
                <input type="text" id="novoValor" />
                <button onclick="salvarEdicao()">Salvar</button>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="../js/ajax.js"></script>
        <script src="../js/ajaxHistorico.js"></script>
        <script src="../js/script.js"></script>
        <script src="../js/swal.js"></script>

        <style>
            /* Estilo para prevenir animação inicial do switch */
            .switch .slider {
                transition: none;
            }
            .switch.initialized .slider {
                transition: .4s;
            }
        </style>

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
        <!--Script para as modais-->
        <script>
            // Abrir modal editar
            document.getElementById('btnAbrirEditar').addEventListener('click', () => {
                document.getElementById('modalEditar').style.display = 'block';
            });

            // Abrir submodal para campo individual
            function abrirSubModal(campo) {
                campoAtual = campo;
                document.getElementById('tituloCampo').innerText = 'Atualizar ' + campo.charAt(0).toUpperCase() + campo.slice(1);
                document.getElementById('modalCampo').style.display = 'block';
            }

            // Salvar edição
            function salvarEdicao() {
                const valor = document.getElementById('novoValor').value;

                fetch('../db/atualizar_dados.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `campo=${campoAtual}&valor=${encodeURIComponent(valor)}`
                })
                .then(res => res.text())
                .then(msg => {
                    alert(msg);
                    location.reload(); // Atualiza a página para refletir os dados
                });
            }

            // Fechar modais ao clicar fora delas
            window.onclick = function(event) {
                const modais = document.querySelectorAll('.modal');
                modais.forEach(modal => {
                    if (event.target === modal) {
                        modal.style.display = 'none';
                    }
                });
            }

            // Função para atualizar o estado dos botões
            function atualizarEstadoBotoes(modoAutoAtivo) {
                const btnLigar = document.getElementById('ligar');
                const btnDesligar = document.getElementById('desligar');
                
                btnLigar.disabled = modoAutoAtivo;
                btnDesligar.disabled = modoAutoAtivo;
            }

            // Inicialização do modo automático
            document.addEventListener('DOMContentLoaded', function() {
                const modoAutoCheckbox = document.getElementById('modoAuto');
                const switchElement = modoAutoCheckbox.closest('.switch');
                
                // Remove a classe init-done (caso exista de um reload)
                switchElement.classList.remove('init-done');
                
                // Define o estado inicial sem animação
                // Use o valor vindo do PHP como fonte de verdade
                modoAutoCheckbox.checked = window.MODO_AUTO_INICIAL;


                // Atualiza o estado inicial dos botões
                atualizarEstadoBotoes(modoAutoCheckbox.checked);

                // Força um reflow antes de adicionar a classe que habilita as transições
                switchElement.offsetHeight;

                // Adiciona a classe que habilita as transições após um pequeno delay
                requestAnimationFrame(() => {
                    switchElement.classList.add('init-done');
                });

                // Atualiza o localStorage e estado dos botões quando o switch mudar
                modoAutoCheckbox.addEventListener('change', function() {
                    localStorage.setItem('modoAutomatico', this.checked);
                    atualizarEstadoBotoes(this.checked);
                });
            });

            // Modificar a função registrarAcao para verificar modo automático
            function registrarAcao(status) {
                const modoAuto = document.getElementById('modoAuto').checked;
                
                // Se modo automático estiver ativo, não registra ação manual
                if (modoAuto) {
                    return;
                }

                fetch("../db/registrar_acao.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded"
                    },
                    body: "status=" + encodeURIComponent(status) + "&origem=usuario"
                })
                .then(response => response.text())
                .then(data => {
                    console.log("Resposta do servidor:", data);
                })
                .catch(error => {
                    console.error("Erro ao registrar ação:", error);
                });
            }

            // Registrar ações ligar e desligar
            document.getElementById('ligar').addEventListener('click', function () {
                registrarAcao('ligada');
            });

            document.getElementById('desligar').addEventListener('click', function () {
                registrarAcao('desligada'); 
            });

            // Loop para registrar modo automático
            setInterval(() => {
                const modoAuto = document.getElementById('modoAuto').checked;
                
                // Se modo automático estiver desligado, não faz nada
                if (!modoAuto) return;
                
                const temperatura = parseFloat(document.getElementById('temp').textContent);
                const umidade = parseFloat(document.getElementById('umi').textContent);

                // Verifica o estado atual do sistema
                const statusAtual = document.querySelector('#Historico tr:first-child td:nth-child(3)');
                const ultimoStatus = statusAtual ? statusAtual.textContent.trim() : null;

                if (!isNaN(temperatura) && !isNaN(umidade)) {
                    fetch("../db/registrar_auto.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded"
                        },
                        body: `temperatura=${temperatura}&umidade=${umidade}&modo_automatico=${modoAuto}&ultimo_status=${ultimoStatus}`
                    })
                    .then(response => response.text())
                    .then(data => {
                        console.log("Automático:", data);
                        // Atualiza o histórico imediatamente se houver mudança
                        if (data.includes("registrada")) {
                            carregarHistorico();
                        }
                    })
                    .catch(error => {
                        console.error("Erro automático:", error);
                    });
                }
            }, 2000); // Reduzido para 2 segundos
        </script>
    </body>
</html>


