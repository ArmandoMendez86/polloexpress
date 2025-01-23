<?php
date_default_timezone_set('America/Mexico_City');
require_once 'conexion.php';

class ModeloRetiros
{
    static public function mdlObtenerRetiro($tabla)
    {
        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    static public function mdlAgregarRetiro($tabla, $datos)
    {
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(monto, fecha) VALUES(:monto, :fecha)");
        $stmt->bindParam(":monto", $datos['monto'], PDO::PARAM_STR);
        $stmt->bindParam(":fecha", $datos['fecha'], PDO::PARAM_STR);
        if ($stmt->execute()) {
            return 'ok';
        } else {
            return 'error';
        }
    }
    static public function mdlEditarRetiro($tabla, $datos)
    {
        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET monto = :monto, fecha = :fecha WHERE id = :id");
        $stmt->bindParam(":id", $datos["id"], PDO::PARAM_INT);
        $stmt->bindParam(":monto", $datos["monto"], PDO::PARAM_STR);
        $stmt->bindParam(":fecha", $datos["fecha"], PDO::PARAM_STR);
        if ($stmt->execute()) {
            return 'ok';
        } else {
            return 'error';
        }
    }
    public static function mdlEliminarRetiro($tabla, $id)
    {
        $stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id =:id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return 'ok';
        } else {
            return 'error';
        }
    }
    static public function mdlTotalRetiro($tabla)
    {
        $fecha_actual = date('Y-m-d');
        $stmt = Conexion::conectar()->prepare("SELECT SUM(monto) AS total_monto FROM $tabla WHERE DATE(fecha) = '$fecha_actual'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
