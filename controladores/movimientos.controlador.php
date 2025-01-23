<?php
require_once '../modelos/movimientos.modelo.php';

class ControladorMovimientos
{

    static public $tabla = "movimientos";

    /* ############################# Obetner menu ############################# */
    static public function ctrObtenerMovimientos()
    {
        $respuesta = ModeloMovimientos::mdlObtenerMovimientos(self::$tabla);
        return $respuesta;
    }
}


if (isset($_GET['accion']) && $_GET['accion'] == 'obtenerMovimientos') {
    $respuesta = ControladorMovimientos::ctrObtenerMovimientos();
    echo json_encode(['data' => $respuesta]);
}
