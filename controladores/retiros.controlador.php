<?php
require_once '../modelos/retiros.modelo.php';


class ControladorRetiros
{

    static public $tabla = "retiros";


    static public function ctrObtenerRetiro()
    {
        $respuesta = ModeloRetiros::mdlObtenerRetiro(self::$tabla);
        return $respuesta;
    }

    static  public function ctrAgregarRetiro($datos)
    {
        unset($datos['accion']);
        $respuesta = ModeloRetiros::mdlAgregarRetiro(self::$tabla, $datos);
        return $respuesta;
    }

    static public function ctrEditarRetiro($datos)
    {
        unset($datos['accion']);
        $respuesta = ModeloRetiros::mdlEditarRetiro(self::$tabla, $datos);
        return $respuesta;
    }
    static public function ctrEliminarRetiro($datos)
    {
        unset($datos['accion']);
        $id = $datos['id'];
        $respuesta = ModeloRetiros::mdlEliminarRetiro(self::$tabla, $id);
        return $respuesta;
    }

    static public function ctrTotalRetiro()
    {
        $respuesta = ModeloRetiros::mdlTotalRetiro(self::$tabla);
        return $respuesta;
    }
}


if (isset($_GET['accion']) && $_GET['accion'] == 'obtenerRetiros') {
    $respuesta = ControladorRetiros::ctrObtenerRetiro();
    echo json_encode(['data' => $respuesta]);
}

if (isset($_POST['accion']) && $_POST['accion'] == 'agregarRetiro') {
    $respuesta = ControladorRetiros::ctrAgregarRetiro($_POST);
}

if (isset($_POST['accion']) && $_POST['accion'] == 'editarRetiro') {
    $respuesta = ControladorRetiros::ctrEditarRetiro($_POST);
}

if (isset($_POST['accion']) && $_POST['accion'] == 'eliminarRetiro') {
    $respuesta = ControladorRetiros::ctrEliminarRetiro($_POST);
}

if (isset($_POST['accion']) && $_POST['accion'] == 'obtenerTotalRetiro') {
    $respuesta = ControladorRetiros::ctrTotalRetiro();
    echo json_encode($respuesta);
}
