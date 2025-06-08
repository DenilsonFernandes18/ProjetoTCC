function atualizarDados() {
    fetch('../db/dados.php')
        .then(response => response.json())
        .then(data => {
            document.getElementById('temp').textContent = data.temperatura !== null ? data.temperatura + '°C' : '--';
            document.getElementById('umi').textContent = data.umidade !== null ? data.umidade + '%' : '--';
        })
        .catch(error => {
            console.error('Erro ao buscar dados:', error);
            document.getElementById('temp').textContent = '--';
            document.getElementById('umi').textContent = '--';
        });
}

// Atualiza os dados a cada 2 segundos
//setInterval(atualizarDados, 2000);

// Primeira atualização ao carregar a página
atualizarDados();
