<?php 

namespace Controllers;

use Model\Cita;
use Model\CitaServicio;
use Model\Servicio;

class APIController{

    public static function eliminar(){
        if($_SERVER['REQUEST_METHOD'] === 'POST'){
            $id = $_POST['id'];
            $cita = Cita::find($id);
            $cita->eliminar();
        header("Location: " .$_SERVER['HTTP_REFERER']);
        }
    }

    public static function index (){
        $servicios = Servicio::all();
        echo json_encode($servicios);
    }

    public static function guardar(){

        //Almacena la Cita y devuelve el id 
        $cita = new Cita($_POST);
        $resultado = $cita -> guardar();
        $id = $resultado['id'];

        //alamacena las Citas y los servicios 
        //Almacena los servicios con el Id de la cita 
        $idServicios = explode(',', $_POST['servicios']);
        foreach($idServicios as $idServicio){
            $args = [
                'citaId' => $id,
                'servicioId' => $idServicio

            ];

            $citaServicio = new CitaServicio($args);
            $citaServicio->guardar();
        }

        echo json_encode( ['resultado' => $resultado] );
    }
}