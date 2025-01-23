<?php

require_once '../modelos/detalle_venta.modelo.php';

class ControladorDetalleVenta
{

    static public $tabla = "detalle_venta";
    static public function ctrObtenerDetalles($id)
    {
        $respuesta = ModeloDetalleVenta::mdlObtenerDetalles(self::$tabla, $id);
        return $respuesta;
    }
}


if (isset($_GET['accion']) && $_GET['accion'] == 'obtenerDetalles') {

    $respuesta = ControladorDetalleVenta::ctrObtenerDetalles($_GET['id']);
    echo json_encode($respuesta);
}
