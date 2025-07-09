<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController{

    public static function login(Router $router){
        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);

            $alertas = $auth->validarLogin();

            if(empty($alertas)){
                //Verificar que el usuario exista
                $usuario = Usuario::where('email', $auth->email);

                if($usuario){
                    //Verificar el password
                   if ($usuario->comprobarPasswordAndVerificado($auth->password)){
                    //Autenticar el usuario
                   session_start();
                   $_SESSION['id'] = $usuario->id;
                   $_SESSION['nombre'] = $usuario->nombre . " " . $usuario->apellido;
                   $_SESSION['email'] = $usuario->email;
                   $_SESSION['login'] = true;

                   //Redireccionamiento
                   if($usuario->admin === '1'){
                        $_SESSION['admin'] = $usuario->admin ?? null;

                        header('Location: /admin');
                   } else {
                        header('Location: /cita');
                   }

                   
                   }

                } else {
                    Usuario::setAlerta('error', 'Usuario no encontrado');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('auth/login', [
            'alertas' => $alertas
        ]);
    

    }

    
    public static function logout(){
        echo "desde logout .l.";
    }

    
    public static function olvide(Router $router){

        $alertas = [];

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();

            if(empty($alertas)){
                $usuario = Usuario::where('email', $auth->email);

                if($usuario && $usuario->confirmado === "1"){

                    //Generar token de un solo uso 
                    $usuario->crearToken();
                    $usuario->guardar();

                    //Enviar el email 

                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    $email->enviarInstrucciones();


                    //Alerta de exito
                    Usuario::setAlerta('exito', 'Revisa tu email');
                }else {
                    Usuario::setAlerta('error', 'El Usuario no existe o no esta confirmado');
                    
                }
            }
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/olvide_password', [
            'alertas' => $alertas
        ]);
    }
    
    public static function recuperar(Router $router){
        
        $alertas = [];
        $error = false;

        $token = s($_GET['token']);
       

        //Buscar usuario por su token 
        $usuario = Usuario::where('token', $token);

        if(empty($usuario)){
            Usuario::setAlerta('error', 'Token no Válido');
            $error = true;
        }

        if($_SERVER['REQUEST_METHOD'] === 'POST'){
        
            //Leer password y guardarlo
        $password = new Usuario($_POST);
        $password->validarPassword();

            if(empty($alertas)){
                $usuario->password = null;

                //debuguear($password);
                $usuario->password = $password->password;
                $usuario->hashPassword();
                $usuario->token = null;

                $resultado = $usuario->guardar();
                if($resultado){
                    header('Location: /');
                }
            }
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/recuperar-password', [
            'alertas' => $alertas,
            'error' => $error

        ]);
    }

    public static function crear(Router $router){
        
        $usuario = new Usuario;

        //Alertas vacias 
        $alertas = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            //Revisar que alerta este vacio
            if(empty($alertas)){
                //Verificar que el usuario no este registrado 

                $resultado = $usuario->existeUsuario();

                if($resultado->num_rows){
                    $alertas = Usuario::getAlertas();
                }else{
                    //Hasear el password
                    $usuario->hashPassword();

                    //Generar un token unico 
                    $usuario->crearToken();

                    //Enviar el email 
                    $email = new Email($usuario->nombre, $usuario->email, $usuario->token);
                    $email->enviarConfirmacion();

                    //Crear el usuario 
                    $resultado = $usuario->guardar();

                      //debuguear($usuario);

                    if($resultado){
                        header('Location: /mensaje');
                    }                  
                    

                }

            }
        }

        $router->render('auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas

        ]);

    }

    public static function mensaje (Router $router){

        $router->render('auth/mensaje');

    }

    public static function confirmar(Router $router){
        $alertas =[];

        // Verificar si viene de una confirmación exitosa
        if(isset($_GET['exito']) && $_GET['exito'] == '1') {
            Usuario::setAlerta('exito', 'Cuenta verificada correctamente');
            $alertas = Usuario::getAlertas();
            $router->render('auth/confirmar-cuenta', [
                'alertas' => $alertas
            ]);
            return;
        }

        $token = s($_GET['token']);

        $usuario = Usuario::where('token', $token);
        

        if (empty($usuario)){
            //Mostrar mensaje de error 
            Usuario::setAlerta('error', 'Token no válido');
        }else{
            //Modificar a usuario confirmado
            $usuario->confirmado= "1";
            $usuario->token = '';
            $usuario->guardar();

            // Redirigir a una página específica para mostrar el mensaje de éxito
            header('Location: /confirmar-cuenta?exito=1');
            exit;
        }

        $alertas = Usuario::getAlertas();
        $router->render('auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }

}