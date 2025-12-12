<?php 

namespace Controllers;
use MVC\Router;
use Model\Servicio;

class ServicioController {
    public static function index(Router $router) {



        $servicios = Servicio::all();
       
        // Lógica para mostrar la lista de servicios
        $router->render('servicios/index', [
            'nombre' =>  $_SESSION['nombre'] ?? '',
            'servicios' => $servicios
        ]);

    }

    public static function crear(Router $router) {


        $servicio = new Servicio;
        $alertas = [];
        
        // Lógica para crear un nuevo servicio
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $servicio->sincronizar($_POST);
                $alertas = $servicio->validar();
               if (empty($alertas)) {
                   // Guardar el servicio
                   $servicio->guardar();
                   // Redireccionar
                   header('Location: /servicios');
               }
               
    
        }

        $router->render('servicios/crear', [
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);

    }

    public static function actualizar(Router $router) {

        

        $id = $_GET['id'] ?? null;
        if (!is_numeric($id)) return;
    
        $servicio = Servicio::find($id);
        $alertas = [];
        // Lógica para actualizar un servicio existente
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $servicio->sincronizar($_POST);
            $alertas = $servicio->validar();
           if (empty($alertas)) {
               // Guardar el servicio
               $servicio->guardar();
               // Redireccionar
               header('Location: /servicios');
           }
       
        }

            $router->render('servicios/actualizar', [
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }

    public static function eliminar() {

        // Lógica para eliminar un servicio
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'];
            $servicio = Servicio::find($id);
            if ($servicio) {
                $servicio->eliminar();
                header('Location: /servicios');
            }
        }
    }
}