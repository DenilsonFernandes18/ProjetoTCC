const modoAuto = document.getElementById("modoAuto");

modoAuto.addEventListener("change", function () {
    if (modoAuto.checked) {
        Swal.fire({
            icon: 'success',
            title: 'Modo Automático ativado!',
            showConfirmButton: false,
            timer: 1500
        });
    } else {
        Swal.fire({
            icon: 'error',
            title: 'Modo Automático desativado!',
            showConfirmButton: false,
            timer: 1500
        });
    }

    
});

function iniciarIrrigacao() {
    Swal.fire({
        icon: 'success',
        title: 'Irrigação iniciada!',
        showConfirmButton: false,
        timer: 1500
    });
}

function pararIrrigacao() {
    Swal.fire({
        icon: 'error',
        title: 'Irrigação parada!',
        showConfirmButton: false,
        timer: 1500
    });
}
