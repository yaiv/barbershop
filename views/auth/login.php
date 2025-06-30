<h1 class="nombre-pagina">Login</h1>
<p class="descripcion-pagina">Inicia Sesión con tus datos</p>

<form class="formulario" method="POST" action="/">
    <div class="campo">
        <label for="email">Email</label>
        <input
            type="email"
            id="email"
            placeholder="Tú email"
            name="email"
        />
    </div>
    <div class="campo">
        <label for="password">Password</label>
        <input 
            type="password"
            id="password"
            placeholder="Tú contraseña"
            name="password"
        />
    </div>

    <input type="submit" class="boton" value="Inicia Sesión">
</form>

<div class="acciones">
    <a href="/crear-cuenta">¿Aún no tienes una cuenta? Crear una</a>
    <a href="/olvide">¿Olvidaste tu password?</a>
</div>