function atualizarSensores() {
  fetch('../db/conexao.php')
    .then(res => res.json())
    .then(data => {
      const temperatura = data.temperatura;
      const umidade = data.umidade;

      const tempEl = document.getElementById('temp');
      const umidEl = document.getElementById('umi');
      const sensacaoTempEl = document.getElementById('sensacaoTemp');

      // Atualiza valores
      tempEl.textContent = `${temperatura}°C`;
      umidEl.textContent = `${umidade}%`;

      // Limpa classes antigas
      tempEl.classList.remove('vermelho', 'verde', 'laranja');
      umidEl.classList.remove('vermelho', 'verde', 'laranja');

      // Aplica cor à temperatura
      if (temperatura > 35) {
        tempEl.classList.add('vermelho');
        sensacaoTempEl.textContent = 'Sensação: Muito quente';
      } else if (temperatura < 15) {
        tempEl.classList.add('verde');
        sensacaoTempEl.textContent = 'Sensação: Frio';
      } else {
        tempEl.classList.add('verde');
        sensacaoTempEl.textContent = 'Sensação: Agradável';
      }

      // Aplica cor à umidade
      if (umidade < 30) {
        umidEl.classList.add('vermelho');
      } else if (umidade <= 60) {
        umidEl.classList.add('verde');
      } else {
        umidEl.classList.add('laranja');
      }
    })
    .catch(err => console.error('Erro ao buscar dados:', err));
}

// Atualiza imediatamente e depois a cada 1 segundos
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
const modoAutoCheckbox = document.getElementById('modoAuto');
const modoAutoTexto = document.getElementById('modoAutoTexto');

// Função para atualizar visual e salvar no localStorage
function atualizarModoAutomatico() {
  const isChecked = modoAutoCheckbox.checked;

  // Atualiza texto e cor
  modoAutoTexto.textContent = isChecked ? 'ON' : 'OFF';
  modoAutoTexto.style.color = isChecked ? 'green' : 'red';

  // Salva o estado
  localStorage.setItem('modoAutomatico', isChecked ? 'true' : 'false');
}

// Ao carregar a página, restaurar o estado salvo
document.addEventListener('DOMContentLoaded', () => {
  const estadoSalvo = localStorage.getItem('modoAutomatico');

  // Se existir estado salvo, aplica
  if (estadoSalvo !== null) {
    modoAutoCheckbox.checked = (estadoSalvo === 'true');
  }

  // Atualiza a interface com base no estado atual
  atualizarModoAutomatico();
});

// Atualiza e salva sempre que mudar
modoAutoCheckbox.addEventListener('change', atualizarModoAutomatico);





