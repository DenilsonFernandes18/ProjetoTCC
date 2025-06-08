function atualizarSensores() {
  fetch('../db/dados.php')
    .then(res => res.json())
    .then(data => {
      const temperatura = data.temperatura;
      const umidade = data.umidade;

      const tempEl = document.getElementById('temp');
      const umidEl = document.getElementById('umi');
      const sensacaoTempEl = document.getElementById('sensacaoTemp');

      // Limpa classes antigas
      tempEl.classList.remove('vermelho', 'verde', 'laranja', 'preto');
      umidEl.classList.remove('vermelho', 'verde', 'laranja', 'preto');

      // Atualiza valores e cor da temperatura
      if (temperatura === "--") {
        tempEl.textContent = "--";
        tempEl.classList.add('preto');
        sensacaoTempEl.textContent = 'sensação:--';
      } else {
        tempEl.textContent = `${temperatura}°C`;

        if (parseFloat(temperatura) > 35) {
          tempEl.classList.add('vermelho');
          sensacaoTempEl.textContent = 'Sensação: Muito quente';
        } else if (parseFloat(temperatura) < 15) {
          tempEl.classList.add('laranja');
          sensacaoTempEl.textContent = 'Sensação: Frio';
        } else {
          tempEl.classList.add('verde');
          sensacaoTempEl.textContent = 'Sensação: Agradável';
        }
      }

      // Atualiza valores e cor da umidade
      if (umidade === "--") {
        umidEl.textContent = "--";
        umidEl.classList.add('preto');
      } else {
        umidEl.textContent = `${umidade}%`;

        if (umidade < 30) {
          umidEl.classList.add('vermelho');
        } else if (umidade <= 60) {
          umidEl.classList.add('verde');
        } else {
          umidEl.classList.add('laranja');
        }
      }
    })
    .catch(err => console.error('Erro ao buscar dados:', err));
}

// Atualiza imediatamente e depois a cada 2 segundos
atualizarSensores();
setInterval(atualizarSensores, 2000);

//Modal
document.getElementById("btnAbrirHistorico").addEventListener("click", () => {
  document.getElementById("modalHistorico").style.display = "block";
  carregarHistorico(); // Função para buscar dados do histórico, se existir
});

document.getElementById("btnFecharModal").addEventListener("click", () => {
  document.getElementById("modalHistorico").style.display = "none";
});
function closeModal() {
  document.getElementById("modalHistorico").style.display = "none";
}

window.onclick = (event) => {
  const modal = document.getElementById("modalHistorico");
  if (event.target === modal) {
    modal.style.display = "none";
  }
};

//Irrigação modo automático
document.addEventListener('DOMContentLoaded', function () {
  const modoAutoCheckbox = document.getElementById('modoAuto');
  const modoAutoTexto = document.getElementById('modoAutoTexto');

  function atualizarUI(ativo) {
      modoAutoCheckbox.checked = ativo;
      modoAutoTexto.textContent = ativo ? 'ON' : 'OFF';
      modoAutoTexto.style.color = ativo ? 'green' : 'red';
  }

  // Inicializa estado com valor do PHP
  atualizarUI(window.MODO_AUTO_INICIAL);

  modoAutoCheckbox.addEventListener('change', () => {
      const novoEstado = modoAutoCheckbox.checked ? 1 : 0;

      fetch('../db/atualizar_modo_auto.php', {
          method: 'POST',
          headers: {
              'Content-Type': 'application/x-www-form-urlencoded',
          },
          body: 'estado=' + novoEstado
      })
      .then(res => res.json())
      .then(data => {
          if (data.success) {
              atualizarUI(novoEstado);
          } else {
              alert('Erro ao atualizar estado no servidor.');
              atualizarUI(!modoAutoCheckbox.checked);
          }
      })
      .catch(() => {
          alert('Erro de rede.');
          atualizarUI(!modoAutoCheckbox.checked);
      });
  });
});







