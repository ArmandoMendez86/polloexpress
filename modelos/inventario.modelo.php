<?php
require_once 'conexion.php';

class ModeloInventario
{
    static public function mdlObtenerInventario($tabla)
    {
        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    static public function mdlEditarInventario($tabla, $datos)
    {
        try {

            $pdo = Conexion::conectar();
            $pdo->beginTransaction();

            $stmt1 = $pdo->prepare("UPDATE $tabla SET stock_actual = :stock_actual WHERE id = :id");
            $stmt1->bindParam(":id", $datos["id"], PDO::PARAM_INT);
            $stmt1->bindParam(":stock_actual", $datos["nuevo_stock"], PDO::PARAM_STR);
            $stmt1->execute();

            $pdo->commit();
            return 'ok';
        } catch (Exception $e) {
            $pdo->rollBack();
            return 'error: ' . $e->getMessage();
        }
    }
}
