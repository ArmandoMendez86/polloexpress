<?php
require_once '../modelos/inventario.modelo.php';

class ControladorInventario
{

    static public $tabla = "stock";
    static public function ctrObtenerInventario()
    {
        $respuesta = ModeloInventario::mdlObtenerInventario(self::$tabla);
        return $respuesta;
    }

    static public function ctrEditarInventario($datos)
    {
        unset($datos['accion']);
        $respuesta = ModeloInventario::mdlEditarInventario(self::$tabla, $datos);
        return $respuesta;
    }
}


if (isset($_GET['accion']) && $_GET['accion'] == 'obtenerInventario') {
    $respuesta = ControladorInventario::ctrObtenerInventario();
    echo json_encode(['data' => $respuesta]);
}

if (isset($_POST['accion']) && $_POST['accion'] == 'editarInventario') {
    $respuesta = ControladorInventario::ctrEditarInventario($_POST);
}
