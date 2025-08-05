<?php 

namespace Controllers;

use MVC\Router;

class AdminController {
    public static function index ( Router $router ){
        //session_start();

        //Consultar la BD 
        

        $router->render('admin/index', [
            'nombre' => $_SESSION['nombre']

        ]);
    }
}