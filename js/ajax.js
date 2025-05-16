/*
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
*/

function atualizarDados() {
  fetch("../db/conexao.php")
    .then(response => response.json())
    .then(data => {
      // Atualiza a temperatura e umidade no dashboard
      document.getElementById("temp").textContent = data.temperatura + " °C";
      document.getElementById("umi").textContent = data.umidade + " %";
    })
    .catch(error => console.error("Erro ao buscar dados:", error));
}

// Chama a função inicial e depois a cada 2 segundos (2000 ms)
setInterval(atualizarDados, 2000); 
atualizarDados();  // Chama uma vez no início
