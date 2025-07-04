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
            debuguear($auth);
        }
        
        $router->render('auth/login', [
            'alertas' => $alertas
        ]);
    

    }

    
    public static function logout(){
        echo "desde logout .l.";
    }

    
    public static function olvide(Router $router){
        $router->render('auth/olvide_password', [

        ]);
    }
    
    public static function recuperar(){
        echo "desde recuperar";
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