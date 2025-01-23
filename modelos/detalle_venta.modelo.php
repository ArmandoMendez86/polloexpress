<?php
date_default_timezone_set('America/Mexico_City');
require_once 'conexion.php';

class ModeloDetalleVenta
{
    static public function mdlObtenerDetalles($tabla, $id)
    {
        $stmt = Conexion::conectar()->prepare("SELECT detalle_venta.id_venta, productos.id, productos.img, productos.cod, productos.des, productos.precio, productos.porcion, detalle_venta.cantidad, ventas.total
        FROM productos
        INNER JOIN detalle_venta ON productos.id = detalle_venta.id_producto
        INNER JOIN ventas ON detalle_venta.id_venta = ventas.id
        WHERE detalle_venta.id_venta = $id");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    static public function mdlCrearOrden($tabla, $datos)
    {
        $procedimiento = Conexion::conectar();

        try {
            $procedimiento->beginTransaction();
            $monto = str_replace(['$', ','], '', $datos['total']);
            $montoFloat = floatval($monto);

            $stmt = $procedimiento->prepare("INSERT INTO $tabla (fecha, total, cliente, empleado, n_orden) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute([$datos['fecha'], $montoFloat, $datos['cliente'], $datos['empleado'], $datos['n_orden']]);

            $id_venta = $procedimiento->lastInsertId();

            $productos = $datos['producto'];
            $porcion = 1;

            foreach ($productos as $producto) {
                $id_producto = $producto['id'];
                $cantidad = $producto['cantidad'];

                $stmt = $procedimiento->prepare("SELECT porcion, precio FROM productos WHERE id = ?");
                $stmt->execute([$id_producto]);
                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

                $porcion = $resultado['porcion'];
                $precio = $resultado['precio'];

                $subtotal = $precio * $cantidad;

                $stmt = $procedimiento->prepare("INSERT INTO detalle_venta (id_venta, id_producto, cantidad, subtotal) VALUES (?, ?, ?, ?)");
                $stmt->execute([$id_venta, $id_producto, $cantidad, $subtotal]);
            }


            foreach ($productos as $producto) {
                $cat = $producto['cat'];
                $cantidad = $producto['cantidad'] * $porcion;

                $stmt = $procedimiento->prepare("UPDATE stock SET stock_actual = stock_actual - ? WHERE cat = ?");
                $stmt->execute([$cantidad, $cat]);
            }

            $procedimiento->commit();
        } catch (Exception $e) {
            $procedimiento->rollBack();
            echo "Error: " . $e->getMessage();
        }
    }

    static public function mdlContabilidadProductos($datos)
    {
        $pdo = Conexion::conectar();
        $productos = $datos['contabilidad'];

        $productos = json_decode($productos, true);

        if (is_array($productos)) {

            foreach ($productos as $producto) {

                $idProducto = $producto['id'];
                $especialidad = $producto['especialidad'];
                $cantidad = $producto['cantidad'];
                $fecha = $datos['fecha'];

                $sql = "INSERT INTO contabilidad (id_producto, especialidad, cantidad, fecha) VALUES (:id_producto, :especialidad, :cantidad, :fecha)";
                $stmt = $pdo->prepare($sql);
                $stmt->bindParam(':id_producto', $idProducto);
                $stmt->bindParam(':especialidad', $especialidad);
                $stmt->bindParam(':cantidad', $cantidad);
                $stmt->bindParam(':fecha', $fecha);

                if (!$stmt->execute()) {
                    throw new Exception("Error al insertar el producto: " . $idProducto);
                }
            }
        } else {
            throw new Exception("Formato de datos incorrecto en 'productos'.");
        }
    }

    static public function mdlEliminarOrden($tabla, $idVenta)
    {
        $stmt = Conexion::conectar()->prepare("DELETE FROM $tabla WHERE id =:id");
        $stmt->bindParam(":id", $idVenta, PDO::PARAM_INT);
        if ($stmt->execute()) {
            return 'ok';
        } else {
            return 'error';
        }
    }
    static public function mdlEditarOrden($tabla, $datos)
    {

        $monto = str_replace(['$', ','], '', $datos['total']);
        $montoFloat = floatval($monto);

        $stmt = Conexion::conectar()->prepare("UPDATE $tabla SET producto =:producto, empleado =:empleado, fecha=:fecha, cliente=:cliente, total=:total WHERE id = :id");
        $stmt->bindParam(":producto", $datos['producto'], PDO::PARAM_STR);
        $stmt->bindParam(":empleado", $datos['empleado'], PDO::PARAM_STR);
        $stmt->bindParam(":fecha", $datos['fecha'], PDO::PARAM_STR);
        $stmt->bindParam(":cliente", $datos['cliente'], PDO::PARAM_STR);
        $stmt->bindParam(":total", $montoFloat, PDO::PARAM_STR);
        $stmt->bindParam(":id", $datos['id'], PDO::PARAM_INT);
        if ($stmt->execute()) {
            return 'ok';
        } else {
            return 'error';
        }
    }

    static public function mdlTotalVenta($tabla)
    {
        $fecha_actual = date('Y-m-d');
        $stmt = Conexion::conectar()->prepare("SELECT SUM(total) AS total_monto
        FROM $tabla
        WHERE DATE(fecha) = '$fecha_actual'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
