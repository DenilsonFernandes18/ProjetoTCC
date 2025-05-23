function carregarHistorico() {
    fetch("../db/buscar_historico.php")
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById("Historico");
            tbody.innerHTML = ""; // Limpa o conteúdo antigo

            if (data.length === 0) {
                tbody.innerHTML = `<tr><td colspan="3" class="sem-registro">Nenhum registro encontrado.</td></tr>`;
                return;
            }

            // Exibe os dados na tabela
            data.forEach(item => {
                const linha = `
                    <tr>
                        <td>${item.data_hora}</td>
                        <td>${item.origem}</td>
                        <td>${item.status}</td>
                    </tr>
                `;
                tbody.innerHTML += linha;
            });
        })
        .catch(error => console.error("Erro ao carregar histórico:", error));
}
// Atualiza a cada 2 segundos 
setInterval(carregarHistorico, 2000);

// Carrega o histórico quando a modal for aberta
document.getElementById("btnAbrirHistorico").addEventListener("click", carregarHistorico);

