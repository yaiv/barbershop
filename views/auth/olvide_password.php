<h1 class="nombre-pagina">Olvide Password</h1>
<p class="descripcion-pagina">Restablece tu password escribiendo tú email a continuacion</p>

<?php include_once __DIR__ . '/../templates/alertas.php'; ?>

<form class="formulacio" action="/olvide" method="POST" >
    <div class="campo">
        <label for="email">Email</label>
        <input 
            type="email"
            id="email"
            name="email"
            placeholder="Tú email"
        
        />
    </div>

    <input type="submit" class="boton" value="Restablecer">

</form>

<div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Inicia Sesión</a>
    <a href="/olvide">¿Aún no tienes una cuenta? Crear Una</a>
</div>