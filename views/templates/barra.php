    <div class="barra">
        Hola: <?php echo $nombre ?? ''; ?>
        <a class="boton" href="/logout">Cerrar Sesión</a>
    </div>

    <?php 
    if (isset ($_SESSION['admin'])){ ?>
        <div class="barra-servicios">
        <a class="boton" href="/admin">Ver Citas</a>
        <a class="boton" href="/servicios">Ver Servicio</a>
        <a class="boton" href="/servicios/crear">Nuevo Servicio</a>
        </div>
        
    <?php
    }else{
        echo "No es admin";
    }
    ?>