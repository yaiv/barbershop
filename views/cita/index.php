<h1 class="nombre-pagina">Crear cita</h1>

<p class="descripcion-pagina">Elige tus servicios y coloca tus datos</p>

<div id="app">


    <div id="paso-1" class="seccion">
        <h1>Servicios</h1>
        <p>Elige tus servicios a continuacion</p>
        <div id="servicios" class="listado-servicios"></div>
    </div>
    
    <div id="paso-2" class="seccion">
        <h1>Tus datos y Cita</h1>
        <p>Coloca tus datos y fecha de tu cita</p>
    </div>

    <form class="formulario" action="">
        <div class="campo">
            <label for="nombre">Nombre</label>
            <input
                id="nombre"
                type="text"
                placeholder="Tu nombre"
                value="<?php echo $nombre ?>"
                disabled
            >
        </div>
         <div class="campo">
            <label for="fecha">Fecha</label>
            <input
                id="fecha"
                type="date"            >
        </div>
        <div class="campo">
            <label for="hora">Hora</label>
            <input
                id="hora"
                type="time"
            >
        </div>
    </form>
    
    <div id="paso-3" class="seccion">
        <h1>Resumen</h1>
        <p>Verifica que la información sea correcta</p>
    </div>
</div>