function atualizarDados() {
    fetch("../db/conexao.php")
      .then(response => response.json())
      .then(data => {
        document.getElementById("temp").textContent = data.temperatura;
        document.getElementById("umi").textContent = data.umidade;
      })
      .catch(error => console.error("Erro ao buscar dados:", error));
  }

  //setInterval(atualizarDados, 2000); Atualiza a cada 2 segundos
  atualizarDados(); // Chama uma vez no início