<?php
date_default_timezone_set('America/Mexico_City');
require_once 'conexion.php';

class ModeloOrdenes
{
    static public function mdlObtenerOrdenes($tabla)
    {
        $stmt = Conexion::conectar()->prepare("SELECT * FROM $tabla");
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
                $stmt = $procedimiento->prepare("SELECT stock_actual FROM stock WHERE cat = ?");
                $stmt->execute([$producto['cat']]);
                $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
                $existia = $resultado['stock_actual'];

                if ($producto['id'] == 102) {
                    $porcion = floatval($producto['precio']) / floatval(320);
                    $existe = floatval($existia) - (floatval($cantidad) * floatval($porcion));
                } else {

                    $existe = floatval($existia) - (floatval($cantidad) * floatval($porcion));
                }

                $stmt = $procedimiento->prepare("INSERT INTO movimientos (idventa, idproducto, existia, tipo, cantidad, existe) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$id_venta, $id_producto, $existia, "salida", $cantidad, $existe]);

                $stmt = $procedimiento->prepare("UPDATE stock SET stock_actual = ? WHERE cat = ?");
                $stmt->execute([$existe, $producto['cat']]);
            }

            $procedimiento->commit();
        } catch (Exception $e) {
            $procedimiento->rollBack();
            echo "Error: " . $e->getMessage();
        }
    }


    static public function mdlEliminarOrden($tabla, $datos)
    {
        $procedimiento = Conexion::conectar();

        if (!is_array($datos)) {
            return 'error: datos inválidos';
        }

        try {
            $procedimiento->beginTransaction();

            foreach ($datos as $venta) {
                $devolverCantidad = floatval($venta['cantidad']) * floatval($venta['porcion']);

                $stmt = $procedimiento->prepare("SELECT cat FROM productos WHERE id = ?");
                $stmt->execute([$venta['id']]);
                $cat = $stmt->fetchColumn();

                if ($cat === false) {
                    throw new Exception('Categoría no encontrada para el producto con ID: ' . $venta['id']);
                }

                $stmt = $procedimiento->prepare("DELETE FROM detalle_venta WHERE id_venta = ?");
                if (!$stmt->execute([$venta['id_venta']])) {
                    throw new Exception('Error al eliminar de detalle_venta para id_venta: ' . $venta['id_venta']);
                }

                $stmt = $procedimiento->prepare("DELETE FROM ventas WHERE id = ?");
                if (!$stmt->execute([$venta['id_venta']])) {
                    throw new Exception('Error al eliminar de ventas para ID: ' . $venta['id']);
                }

                $stmt = $procedimiento->prepare("UPDATE stock SET stock_actual = stock_actual + ? WHERE cat = ?");
                if (!$stmt->execute([$devolverCantidad, $cat])) {
                    throw new Exception('Error al actualizar el stock para la categoría: ' . $cat);
                }
            }

            $procedimiento->commit();
            return 'ok';
        } catch (Exception $e) {
            $procedimiento->rollBack();
            return 'error: ' . $e->getMessage();
        }
    }

    static public function mdlEditarOrden($datos)
    {
        $procedimiento = Conexion::conectar();

        try {
            $procedimiento->beginTransaction();
            $monto = str_replace(['$', ','], '', $datos['total']);
            $montoFloat = floatval($monto);
            $stmt = $procedimiento->prepare("UPDATE ventas SET fecha = ?, total = ?, cliente = ?, empleado = ? WHERE id = ?");
            $stmt->execute([$datos['fecha'], $montoFloat, $datos['cliente'], $datos['empleado'], $datos['productos'][0]['id_venta']]);

            $stmt = $procedimiento->prepare("
            SELECT dv.id_producto, dv.cantidad, p.cat, p.porcion 
            FROM detalle_venta dv
            INNER JOIN productos p ON dv.id_producto = p.id
            WHERE dv.id_venta = ?
        ");
            $stmt->execute([$datos['productos'][0]['id_venta']]);
            $detallesExistentes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $productos = $datos['productos'];
            $nuevosIds = array_column($productos, 'id');

            foreach ($productos as $producto) {
                $id_producto = $producto['id'];
                $nuevaCantidad = floatval($producto['cantidad']);
                $precio = floatval($producto['precio']);
                $porcion = floatval($producto['porcion']);

                $cantidadActual = 0;
                $cat = null;

                foreach ($detallesExistentes as $detalle) {
                    if ($detalle['id_producto'] == $id_producto) {
                        $cantidadActual = floatval($detalle['cantidad']);
                        $cat = $detalle['cat'];
                        break;
                    }
                }

                if ($cantidadActual > 0) {
                    $diferencia = ($nuevaCantidad - $cantidadActual) * $porcion;
                } else {
                    $diferencia = $nuevaCantidad * $porcion;
                }


                if ($cantidadActual > 0) {
                    $subtotal = $nuevaCantidad * $precio;
                    $stmt = $procedimiento->prepare("UPDATE detalle_venta SET cantidad = ?, subtotal = ? WHERE id_venta = ? AND id_producto = ?");
                    $stmt->execute([$nuevaCantidad, $subtotal, $datos['productos'][0]['id_venta'], $id_producto]);
                } else {
                    $subtotal = $nuevaCantidad * $precio;
                    $stmt = $procedimiento->prepare("INSERT INTO detalle_venta (id_venta, id_producto, cantidad, subtotal) VALUES (?, ?, ?, ?)");
                    $stmt->execute([$datos['productos'][0]['id_venta'], $id_producto, $nuevaCantidad, $subtotal]);
                    $stmt = $procedimiento->prepare("SELECT cat FROM productos WHERE id = ?");
                    $stmt->execute([$id_producto]);
                    $cat = $stmt->fetchColumn();
                }

                if ($diferencia != 0 && $cat !== null) {
                    $stmt = $procedimiento->prepare("UPDATE stock SET stock_actual = stock_actual - ? WHERE cat = ?");
                    $stmt->execute([$diferencia, $cat]);
                }
            }

            foreach ($detallesExistentes as $detalle) {
                if (!in_array($detalle['id_producto'], $nuevosIds)) {
                    $stmt = $procedimiento->prepare("DELETE FROM detalle_venta WHERE id_venta = ? AND id_producto = ?");
                    $stmt->execute([$datos['productos'][0]['id_venta'], $detalle['id_producto']]);

                    $devolverCantidad = floatval($detalle['cantidad']) * floatval($detalle['porcion']);
                    $stmt = $procedimiento->prepare("UPDATE stock SET stock_actual = stock_actual + ? WHERE cat = ?");
                    $stmt->execute([$devolverCantidad, $detalle['cat']]);
                }
            }
            $procedimiento->commit();
        } catch (Exception $e) {
            $procedimiento->rollBack();
            echo "Error: " . $e->getMessage();
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
