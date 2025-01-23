<?php

require '../vendor/autoload.php';

use Mike42\Escpos\Printer;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;

$rawData = file_get_contents("php://input");
$data = json_decode($rawData, true);

if (!$data) {
    http_response_code(400);
    echo "Error: Datos no válidos.";
    exit;
}

$cliente = $data['cliente'] ?? 'Cliente desconocido';
$empleado = $data['empleado'] ?? 'empleado turno';
$total = $data['total'] ?? '0.00';
$fecha = $data['fecha'] ?? date('Y-m-d H:i:s');
$numOrden = $data['numOrden'] ?? [];
$articulos = $data['articulos'] ?? [];

$ticket = "Tio Pollo Express\n";
$ticket .= "-----------------------------\n";
$ticket .= "Folio: $numOrden\n";
$ticket .= "Cliente: $cliente\n";
$ticket .= "Fecha: $fecha\n";
$ticket .= "Atendio: $empleado\n";
$ticket .= "-----------------------------\n";
$ticket .= "Productos:\n";

foreach ($articulos as $articulo) {
    $nombre = $articulo['cod'] ?? 'Producto desconocido';
    $cantidad = $articulo['cantidad'] ?? 0;
    $precio = $articulo['precio'] ?? 0.00;
    $ticket .= sprintf("%s x%s - $%0.2f\n", $nombre, $cantidad, $precio);
}

$ticket .= "-----------------------------\n";
$ticket .= "Total:  $total\n";
$ticket .= "¡Gracias por su compra!\n";

$tempFile = tempnam(sys_get_temp_dir(), 'ticket_');
file_put_contents($tempFile, $ticket);


try {

    $connector = new WindowsPrintConnector("POS-58");
    $printer = new Printer($connector);
    $printer->text($ticket);
    $printer->cut();
    $printer->close();
    echo "Ticket enviado a impresión.";
} catch (Exception $e) {
    echo "Error al imprimir el ticket: " . $e->getMessage();
}


unlink($tempFile);
