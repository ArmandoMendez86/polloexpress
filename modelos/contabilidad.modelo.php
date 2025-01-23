<?php
require_once 'conexion.php';

class ModeloContabilidad
{

    static public function mdlObtenerContabilidad()
    {
        $stmt = Conexion::conectar()->prepare("SELECT p.cat, p.cod, p.des, p.precio, p.porcion, v.empleado, v.n_orden, dv.cantidad, (p.porcion * dv.cantidad) as cantidad_neta, v.fecha from productos as p
        inner join detalle_venta as dv ON dv.id_producto = p.id
        inner join ventas as v ON v.id = dv.id_venta");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}
