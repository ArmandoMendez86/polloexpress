<?php
date_default_timezone_set('America/Mexico_City');
require_once 'conexion.php';

class ModeloGasto
{
    static public function mdlObtenerGasto($tabla)
    {
        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    static public function mdlAgregarGasto($tabla, $datos)
    {
        $stmt = Conexion::conectar()->prepare("INSERT INTO $tabla(concepto, monto, fecha) VALUES(:concepto, :monto, :fecha)");
        $stmt->bindParam(":concepto", $datos['concepto'], PDO::PARAM_STR);
        $stmt->bindParam(":monto", $datos['monto'], PDO::PARAM_STR);
        $stmt->bindParam(":fecha", $datos['fecha'], PDO::PARAM_STR);
        if ($stmt->execute()) {
            return 'ok';
        } else {
            return 'error';
        }
    }
    static public function mdlEditarGasto($tabla, $datos)
    {
        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET concepto = :concepto, monto = :monto, fecha = :fecha WHERE id = :id");
        $stmt->bindParam(":id", $datos["id"], PDO::PARAM_INT);
        $stmt->bindParam(":concepto", $datos["concepto"], PDO::PARAM_STR);
        $stmt->bindParam(":monto", $datos["monto"], PDO::PARAM_STR);
        $stmt->bindParam(":fecha", $datos["fecha"], PDO::PARAM_STR);
        if ($stmt->execute()) {
            return 'ok';
        } else {
            return 'error';
        }
    }
    public static function mdlEliminarGasto($tabla, $id)
    {
        $stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id =:id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return 'ok';
        } else {
            return 'error';
        }
    }
    static public function mdlTotalGasto($tabla)
    {
        $fecha_actual = date('Y-m-d');
        $stmt = Conexion::conectar()->prepare("SELECT SUM(monto) AS total_monto
        FROM $tabla
        WHERE DATE(fecha) = '$fecha_actual' AND concepto != 'caja';");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    static public function mdlCaja()
    {
        $fecha_actual = date('Y-m-d');
        $stmt = Conexion::conectar()->prepare("SELECT * FROM caja WHERE DATE(fecha) = '$fecha_actual'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    static public function aperturarCaja($datos)
    {
        $stmt = Conexion::conectar()->prepare("INSERT INTO caja(monto, fecha) VALUES(:monto, :fecha)");
        $stmt->bindParam(":monto", $datos['monto'], PDO::PARAM_STR);
        $stmt->bindParam(":fecha", $datos['fecha'], PDO::PARAM_STR);
        if ($stmt->execute()) {
            return 'ok';
        } else {
            return 'error';
        }
    }
}
