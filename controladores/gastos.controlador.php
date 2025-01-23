<?php
require_once '../modelos/gastos.modelo.php';


class ControladorGasto
{

    static public $tabla = "gasto";


    static public function ctrObtenerGasto()
    {
        $respuesta = ModeloGasto::mdlObtenerGasto(self::$tabla);
        return $respuesta;
    }

    static  public function ctrAgregarGasto($datos)
    {
        unset($datos['accion']);
        $respuesta = ModeloGasto::mdlAgregarGasto(self::$tabla, $datos);
        return $respuesta;
    }

    static public function ctrEditarGasto($datos)
    {
        unset($datos['accion']);
        $respuesta = ModeloGasto::mdlEditarGasto(self::$tabla, $datos);
        return $respuesta;
    }
    static public function ctrEliminarGasto($datos)
    {
        unset($datos['accion']);
        $id = $datos['id'];
        $respuesta = ModeloGasto::mdlEliminarGasto(self::$tabla, $id);
        return $respuesta;
    }
    static public function ctrTotalGasto()
    {
        $respuesta = ModeloGasto::mdlTotalGasto(self::$tabla);
        return $respuesta;
    }
    static public function ctrCaja()
    {
        $respuesta = ModeloGasto::mdlCaja();
        return $respuesta;
    }
    static public function ctrAperturaCaja($datos)
    {
        unset($datos['accion']);
        $respuesta = ModeloGasto::aperturarCaja($datos);
        return $respuesta;
    }
}


if (isset($_GET['accion']) && $_GET['accion'] == 'obtenerGasto') {
    $respuesta = ControladorGasto::ctrObtenerGasto();
    echo json_encode(['data' => $respuesta]);
}

if (isset($_POST['accion']) && $_POST['accion'] == 'agregarGasto') {
    $respuesta = ControladorGasto::ctrAgregarGasto($_POST);
}

if (isset($_POST['accion']) && $_POST['accion'] == 'editarGasto') {
    $respuesta = ControladorGasto::ctrEditarGasto($_POST);
}
if (isset($_POST['accion']) && $_POST['accion'] == 'eliminarGasto') {
    $respuesta = ControladorGasto::ctrEliminarGasto($_POST);
}
if (isset($_POST['accion']) && $_POST['accion'] == 'obtenerTotalGasto') {
    $respuesta = ControladorGasto::ctrTotalGasto();
    echo json_encode($respuesta);
}
if (isset($_POST['accion']) && $_POST['accion'] == 'obtenerCaja') {
    $respuesta = ControladorGasto::ctrCaja();
    echo json_encode($respuesta);
}
if (isset($_POST['accion']) && $_POST['accion'] == 'aperturaCaja') {
    $respuesta = ControladorGasto::ctrAperturaCaja($_POST);
    echo json_encode($respuesta);
}
