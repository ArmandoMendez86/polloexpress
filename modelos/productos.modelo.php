<?php
require_once 'conexion.php';

class ModeloProductos
{

    static public function mdlObtenerProductos($tabla)
    {
        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla WHERE status = 1");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    static public function mdlObtenerMenu($tabla)
    {
        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    static public function mdlAgregarMenu($tabla, $datos)
    {
        $conexion = Conexion::conectar();
        try {
            $conexion->beginTransaction();
            $imagen = $datos['img'] !== '' ? $datos['img'] : 'assets/img/generic_product.png';

            $stmt = $conexion->prepare("INSERT INTO $tabla(cod, des, precio, cat, img, status, porcion) VALUES(:cod, :des, :precio, :cat, :img, :status, :porcion)");
            $stmt->bindParam(":cod", $datos['cod'], PDO::PARAM_STR);
            $stmt->bindParam(":des", $datos['des'], PDO::PARAM_STR);
            $stmt->bindParam(":precio", $datos['precio'], PDO::PARAM_STR);
            $stmt->bindParam(":cat", $datos['cat'], PDO::PARAM_STR);
            $stmt->bindParam(":img", $imagen, PDO::PARAM_STR);
            $stmt->bindParam(":status", $datos['status'], PDO::PARAM_STR);
            $stmt->bindParam(":porcion", $datos['porcion'], PDO::PARAM_STR);

            if (!$stmt->execute()) {
                throw new Exception("Error al insertar en $tabla");
            }

            $stmtStock = $conexion->prepare("INSERT INTO stock(cat, des, stock_actual, stock_minimo, unidad_medida) VALUES(:cat, :des, :stock_actual, :stock_minimo, :unidad_medida)");
            $stmtStock->bindParam(":cat", $datos['cat'], PDO::PARAM_STR);
            $stmtStock->bindParam(":des", $datos['des'], PDO::PARAM_STR);
            $stmtStock->bindValue(":stock_actual", 0, PDO::PARAM_INT);
            $stmtStock->bindValue(":stock_minimo", 10, PDO::PARAM_INT);
            $stmtStock->bindValue(":unidad_medida", 'piezas', PDO::PARAM_STR);

            if (!$stmtStock->execute()) {
                throw new Exception("Error al insertar en stock");
            }
            $conexion->commit();
            return 'ok';
        } catch (Exception $e) {
            $conexion->rollBack();
            return "Error: " . $e->getMessage();
        }
    }

    static public function mdlEditarMenu($tabla, $datos)
    {

        if ($datos["img"] != '') {
            $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET cod =:cod, porcion=:porcion, status=:status, cat=:cat, precio=:precio, des=:des, img=:img WHERE id = :id");
            $stmt->bindParam(":id", $datos["id"], PDO::PARAM_INT);
            $stmt->bindParam(":cod", $datos["cod"], PDO::PARAM_STR);
            $stmt->bindParam(":porcion", $datos["porcion"], PDO::PARAM_STR);
            $stmt->bindParam(":status", $datos["status"], PDO::PARAM_STR);
            $stmt->bindParam(":cat", $datos["cat"], PDO::PARAM_STR);
            $stmt->bindParam(":precio", $datos["precio"], PDO::PARAM_STR);
            $stmt->bindParam(":des", $datos["des"], PDO::PARAM_STR);
            $stmt->bindParam(":img", $datos["img"], PDO::PARAM_STR);
        } else {
            $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET cod =:cod, porcion=:porcion, status=:status, cat=:cat, precio=:precio, des=:des WHERE id = :id");
            $stmt->bindParam(":id", $datos["id"], PDO::PARAM_INT);
            $stmt->bindParam(":cod", $datos["cod"], PDO::PARAM_STR);
            $stmt->bindParam(":porcion", $datos["porcion"], PDO::PARAM_STR);
            $stmt->bindParam(":status", $datos["status"], PDO::PARAM_STR);
            $stmt->bindParam(":cat", $datos["cat"], PDO::PARAM_STR);
            $stmt->bindParam(":precio", $datos["precio"], PDO::PARAM_STR);
            $stmt->bindParam(":des", $datos["des"], PDO::PARAM_STR);
        }
        if ($stmt->execute()) {
            return 'ok';
        } else {
            return 'error';
        }
    }

    public static function mdlEliminarMenu($tabla, $id)
    {
        $stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id =:id");
        $stmt->bindParam(":id", $id, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return 'ok';
        } else {
            return 'error';
        }
    }
}
