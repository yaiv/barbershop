document.addEventListener('DOMContentLoaded', function() {
    iniciaApp();
});

function iniciaApp() {
    buscarPorFecha();
}

function buscarPorFecha() {
    const fechaInput = document.querySelector('#fecha');
    fechaInput.addEventListener('change', function(e) {
        const fechaSeleccionada = e.target.value;
        console.log('Fecha seleccionada:', fechaSeleccionada);

        window.location = `?fecha=${fechaSeleccionada}`;
    });

}