
 
 <h1 class="nombre-pagina">Desde crear cuenta php</h1>
 <p class="descripcion-pagina">Ingresa tus datos para crear tu cuenta</p>

 <form class="formulario" method="POST" action="/crear-cuenta">

    <div class="campo">
        <label for="nombre">Nombre</label>
        <input 
            type="text"
            id="nombre"
            name="nombre"
            placeholder="Tú nombre"

            />
    </div>

    <div class="campo">
        <label for="apellido">Apellido</label>
        <input 
            type="text"
            id="apellido"
            name="apellido"
            placeholder="Tú apellido"

            />
    </div>

    <div class="campo">
        <label for="telefono">Teléfono</label>
        <input 
            type="tel"
            id="telefono"
            name="telefono"
            placeholder="Tú teléfono"

            />
    </div>

    <div class="campo">
        <label for="email">Email</label>
        <input 
            type="email"
            id="email"
            name="email"
            placeholder="Tú email"

            />
    </div>

    <div class="campo">
        <label for="password">Password</label>
        <input 
            type="password"
            id="password"
            name="password"
            placeholder="Tú password"

            />
    </div>

    <input type="submit" value="Crear cuenta" class="boton">

 </form>

 <div class="acciones">
    <a href="/">¿Ya tienes una cuenta? Incia Sesión</a>
    <a href="/olvide">¿Olvidaste tu password?</a>
</div>