<?php
require_once '../modelos/contabilidad.modelo.php';


class ControladorContabilidad
{

    /* ############################# Obetner contabilidad ############################# */
    static public function ctrObtenerContabilidad()
    {
        $respuesta = ModeloContabilidad::mdlObtenerContabilidad();
        return $respuesta;
    }
}


if (isset($_GET['accion']) && $_GET['accion'] == 'obtenerContabilidad') {
    $respuesta = ControladorContabilidad::ctrObtenerContabilidad();
    echo json_encode(['data' => $respuesta]);
}
