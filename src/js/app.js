let paso = 1;
let pasoInicial= 1;
let pasoFinal = 3;

const cita = {
    nombre: '',
    fecha: '',
    hora: '',
    servicios: []
}

document.addEventListener('DOMContentLoaded', function(){
    iniciarApp();
});

    function iniciarApp(){
        mostrarSeccion(); //Muestra y oculta las secciones
        tabs();//Cambia la seccion cuando se presionen los tabs
        botonesPaginador(); //Agrega o quita los botones del paginador 
        paginaSiguiente();
        paginaAnterior();

        consultarAPI(); //Conuslta la API en el backend de php 

        nombreCliente();    //Añade el nombre del cliente al objeto de cita

        seleccionarFecha(); //Añade la fecha de la cita en el objeto 

        seleccionarHora(); //Añade la hora de la cita en el objeto

        mostrarResumen(); //
    };

    function mostrarSeccion(){

        //Ocultar la seccion que tenga la clase de mostrar
        const seccionAnterior = document.querySelector('.mostrar');
        if(seccionAnterior){
            seccionAnterior.classList.remove('mostrar');
        }

       //Seleccionar la seccion con el paso..
       const pasoSelector = `#paso-${paso}`;
       const seccion = document.querySelector(pasoSelector);
       seccion.classList.add('mostrar');

       //Quita la clase de actual al tab anterior 
       const tabAnterior = document.querySelector('.actual');
       if(tabAnterior){
        tabAnterior.classList.remove('actual');
       }
       //Resalta el tab actual
       const tab = document.querySelector(`[data-paso="${paso}"]`);
       tab.classList.add('actual');
    }

    function tabs(){
        const botones = document.querySelectorAll('.tabs button');

        botones.forEach( boton => {
            boton.addEventListener('click', function(e) {
                paso = parseInt ( e.target.dataset.paso );
                mostrarSeccion();
                botonesPaginador();
                
            });
        });
    }

    function botonesPaginador(){
        const paginaAnterior = document.querySelector('#anterior');
        const paginaSiguiente = document.querySelector('#siguiente');

        if(paso ===1 ){
            paginaAnterior.classList.add('ocultar');
            paginaSiguiente.classList.remove('ocultar')
        }else if (paso ===3){
            paginaAnterior.classList.remove('ocultar');
            paginaSiguiente.classList.add('ocultar');
            mostrarResumen();
        }else {
            paginaAnterior.classList.remove('ocultar');
            paginaSiguiente.classList.remove('ocultar');
        }
        mostrarSeccion();
    }

    function paginaAnterior(){
        const paginaAnterior = document.querySelector('#anterior');
        paginaAnterior.addEventListener('click', function(){

            if(paso <= pasoInicial) return;

            paso--;

            botonesPaginador();
          
        });
    };
    
    function paginaSiguiente(){
        const paginaSiguiente = document.querySelector('#siguiente');
        paginaSiguiente.addEventListener('click', function(){

            if(paso >= pasoFinal) return;

            paso++;

            botonesPaginador();
            
        });

    };


    async function consultarAPI(){

        try {
            const url = 'http://localhost:3000/api/servicios';
            const resultado = await fetch(url); //Se obtiene del servidor
            const servicios = await resultado.json();
            mostrarServicios(servicios);

        } catch (error) {
            console.log(error);
        }
    }

    function mostrarServicios(servicios){
        servicios.forEach( servicio => {
            const {id, nombre, precio} = servicio;
            
            const nombreServicio = document.createElement('P');
            nombreServicio.classList.add('nombre-servicio');
            nombreServicio.textContent = nombre;

            const precioServicio = document.createElement ('P');
            precioServicio.classList.add('precio-servicio');
            precioServicio.textContent = `$${precio}`;

            const servicioDiv = document.createElement('DIV');
            servicioDiv.classList.add('servicio');
            servicioDiv.dataset.idServicio = id;
            servicioDiv.onclick = function() {
                seleccionarServicio(servicio);
            }

            servicioDiv.appendChild(nombreServicio);
            servicioDiv.appendChild(precioServicio);


            document.querySelector('#servicios').appendChild(servicioDiv)
        })
    }

    function seleccionarServicio(servicio){
        const { id } = servicio;
        const { servicios } = cita;
        //Identificar el elemento al que se le da click
        const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);

        //Comprobar si un servicio ya fue agregado o quitarlo 

        if( servicios.some( agregado => agregado.id === id ) ){
            //Eliminarlo
            cita.servicios = servicios.filter( agregado => agregado.id !== id )
            divServicio.classList.remove('seleccionado');
        }else{
            //Agregarlo
             cita.servicios = [...servicios, servicio];
            divServicio.classList.add('seleccionado');

        }

        //console.log(cita);
    }

    function nombreCliente(){
        cita.nombre = document.querySelector('#nombre').value;
        
    }

    function seleccionarFecha(){
        const inputFecha = document.querySelector('#fecha');
        inputFecha.addEventListener('input', function(e){


            const dia = new Date(e.target.value).getUTCDay();
            //Se controla el flujo de dias permitido 
            if( [6, 0].includes(dia) ){
                e.target.value = '';
                mostrarAlerta('Fin de semana sin servicio', 'error', '.formulario');
            }else {
                cita.fecha = e.target.value;
            }
        });
    }

        function seleccionarHora(){
            const inputHora = document.querySelector('#hora')
            inputHora.addEventListener('input', function(e){

                const horaCita = e.target.value;
                const hora = horaCita.split(":")[0];
                if(hora < 10 || hora > 18 ){
                    e.target.value = '';
                    mostrarAlerta('Hora no valida', 'error', '.formulario');
                } else {
                    cita.hora =  e.target.value;
                    // console.log(cita);
                }
            })
        }

    function mostrarAlerta(mensaje, tipo, elemento, desaparece = true){
        //Previene que no haya mas de una alerta
        const alertaPrevia = document.querySelector('.alerta');
        if(alertaPrevia){
            alertaPrevia.remove();
        }

        //Se construye alerta con la clase 
        const alerta = document.createElement('DIV');
        alerta.textContent = mensaje;
        alerta.classList.add('alerta');
        alerta.classList.add(tipo);

        //Posicion de la alerta 
        const referencia = document.querySelector(elemento);
        referencia.appendChild(alerta);

        //Duracion de la alerta, se elimina paso el tiempo 

        if(desaparece){
        setTimeout(() => {
            alerta.remove();
        }, 5000); }

    }

    function mostrarResumen(){
        const resumen = document.querySelector('.contenido-resumen');

        //Limpiar el contenido de Resumen

        while(resumen.firstChild){
            resumen.removeChild(resumen.firstChild);
        }
        //Validacion de servicios e info cita
        if(Object.values(cita).includes('') || cita.servicios.length === 0){
            mostrarAlerta('Faltan datos de servicios, Fecha u Hora', 'error', '.contenido-resumen',
                false );

                return;
        } 

        //Formatear el div de resumen
        const {nombre, fecha, hora, servicios} = cita;

        //Heading para Servicios en Resumen
        const headingServicios = document.createElement('H3');
        headingServicios.textContent = 'Resumen de Servicios';
        resumen.appendChild(headingServicios);

        //Iterando y mostrando los servicios
        servicios.forEach(servicio => {
            const { id, precio, nombre } = servicio;
            const contenedorServicio = document.createElement('DIV');
            contenedorServicio.classList.add('contenedor-servicio');

            const textoServicio = document.createElement('P');
            textoServicio.textContent = nombre;
            
            const precioServicio = document.createElement('P');
            precioServicio.innerHTML = `<span>Precio:</span> $${precio}`;

            contenedorServicio.appendChild(textoServicio);
            contenedorServicio.appendChild(precioServicio);

            resumen.appendChild(contenedorServicio);

        })
        //Heading para Cita en Resumen
        const headingCita = document.createElement('H3');
        headingCita.textContent = 'Resumen de Cita';
        resumen.appendChild(headingCita);

        const nombreCliente = document.createElement('P');
        nombreCliente.innerHTML = `<span>Nombre:</span> ${nombre}`;

        //Formatear la fecha en español
        const fechaObj = new Date(fecha);
        const mes = fechaObj.getMonth();
        const dia = fechaObj.getDate() + 2;
        const year = fechaObj.getFullYear();

        const fechaUTC = new Date( Date.UTC(year, mes, dia));
        
        const opciones = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
        const fechaFormateada = fechaUTC.toLocaleDateString('es-MX', opciones);
       

        const fechaCita = document.createElement('P');
        fechaCita.innerHTML = `<span>Fecha:</span> ${fechaFormateada}`;

        const horaCita = document.createElement('P');
        horaCita.innerHTML = `<span>Hora:</span> ${hora} Horas`;

        //Boton para Crear una Cita 
        const botonReservar = document.createElement('BUTTON');
        botonReservar.classList.add('boton');
        botonReservar.textContent = 'Reservar Cita'
        botonReservar.onclick = reservarCita;

        resumen.appendChild(nombreCliente);
        resumen.appendChild(fechaCita);
        resumen.appendChild(horaCita);

        resumen.appendChild(botonReservar);


    }

    async  function reservarCita(){

        const {nombre, fecha, hora, servicios} = cita;

        const idServicios = servicios.map(servicio => servicio.id);
        //console.log(idServicios);
        

        //lo que se va a mandar al servidor 
        const datos = new FormData();
        datos.append('nombre', nombre );
        datos.append('fecha', fecha );
        datos.append('hora', hora );
        datos.append('servicios', idServicios );

        //console.log([...datos]);
        
        //Peticion hacia la API 
        const url = 'http://localhost:3000/api/citas'

        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos
        });

        const resultado = await respuesta.json();
        console.log(resultado);
        //console.log([...datos]);
    }

