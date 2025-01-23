$(document).ready(function () {
  const gastos = $("#gastosCantidad");
  const venta = $("#ventaCantidad");
  const caja = $("#cajaApertura");
  const neto = $("#netoCantidad");
  const retiro = $("#retiro");

  // Llamada a ambas funciones AJAX usando Promise.all
  Promise.all([obtenerTotalGasto(), obtenerCaja(), obtenerTotalVenta(), obtenerTotalRetiro()])
    .then(([totalGastos, montoCaja, totalVentas, totalRetiro]) => {
      // Formatear las cantidades en moneda mexicana
      const formatoGastos = totalGastos.toLocaleString("es-MX", {
        style: "currency",
        currency: "MXN",
      });
      const formatoCaja = montoCaja.toLocaleString("es-MX", {
        style: "currency",
        currency: "MXN",
      });
      const formatoVentas = totalVentas.toLocaleString("es-MX", {
        style: "currency",
        currency: "MXN",
      });
      const formatoRetiro = totalRetiro.toLocaleString("es-MX", {
        style: "currency",
        currency: "MXN",
      });
      const totalNeto = montoCaja + totalVentas - totalGastos - totalRetiro;
      const formatoNeto = totalNeto.toLocaleString("es-MX", {
        style: "currency",
        currency: "MXN",
      });

      // Mostrar los resultados en la interfaz con formato de moneda
      gastos.html(formatoGastos);
      caja.html(formatoCaja);
      venta.html(formatoVentas);
      neto.html(formatoNeto);
      retiro.html(formatoRetiro);
    })
    .catch((error) => {
      console.error("Error al obtener los datos:", error);
    });

  function obtenerTotalGasto() {
    return $.ajax({
      url: "controladores/gastos.controlador.php",
      type: "POST",
      data: {
        accion: "obtenerTotalGasto",
      },
    }).then((respuesta) => {
      let datos = JSON.parse(respuesta);
      return datos[0] && !isNaN(parseFloat(datos[0].total_monto))
        ? parseFloat(datos[0].total_monto)
        : 0;
    });
  }
  function obtenerCaja() {
    return $.ajax({
      url: "controladores/gastos.controlador.php",
      type: "POST",
      data: {
        accion: "obtenerCaja",
      },
    }).then((respuesta) => {
      let datos = JSON.parse(respuesta);
      return datos[0] && !isNaN(parseFloat(datos[0].monto))
        ? parseFloat(datos[0].monto)
        : 0;
    });
  }

  function obtenerTotalVenta() {
    return $.ajax({
      url: "controladores/ordenes.controlador.php",
      type: "POST",
      data: {
        accion: "totalVentaOrdenes",
      },
    }).then((respuesta) => {
      let datos = JSON.parse(respuesta);
      return datos[0] && !isNaN(parseFloat(datos[0].total_monto))
        ? parseFloat(datos[0].total_monto)
        : 0;
    });
  }
  function obtenerTotalRetiro() {
    return $.ajax({
      url: "controladores/retiros.controlador.php",
      type: "POST",
      data: {
        accion: "obtenerTotalRetiro",
      },
    }).then((respuesta) => {
      let datos = JSON.parse(respuesta);
      return datos[0] && !isNaN(parseFloat(datos[0].total_monto))
        ? parseFloat(datos[0].total_monto)
        : 0;
    });
  }
});
