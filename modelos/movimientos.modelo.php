<?php
require_once 'conexion.php';

class ModeloMovimientos
{

    static public function mdlObtenerMovimientos($tabla)
    {
        $stmt = Conexion::conectar()->prepare("SELECT idventa, p.cod, p.des, cantidad, p.porcion, existia, existe, tipo, v.empleado, v.fecha, v.n_orden from $tabla
        inner join productos as p on movimientos.idproducto = p.id
        inner join ventas as v on movimientos.idventa = v.id");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
