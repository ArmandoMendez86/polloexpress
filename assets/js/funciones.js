//* /* ############################# Generar ticket ############################# */

const enviarTicket = (cliente, empleado, total, fecha, numOrden, articulos) => {
  fetch("controladores/imprimir.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/json",
    },
    body: JSON.stringify({
      cliente,
      total,
      fecha,
      empleado,
      numOrden,
      articulos,
    }),
  })
    .then((response) => response.text())
    .then((data) => console.log(data))
    .catch((err) => console.error("Error al enviar el ticket:", err));
};

//* /* ############################# Registrar orden ############################# */
const generarOrden = (
  fecha,
  numeroOrden,
  cliente,
  total,
  empleado,
  articulos
) => {
  const productosMod = articulos.map((element) => {
    return {
      id: element.id,
      cantidad: element.cantidad,
      cat: element.cat,
      precio: element.precio,
    };
  });

  $.ajax({
    url: "controladores/ordenes.controlador.php",
    type: "POST",
    data: {
      accion: "crearOrden",
      producto: productosMod,
      n_orden: numeroOrden,
      empleado: empleado,
      fecha: fecha,
      cliente: cliente,
      total: total,
    },
    success: function (respuesta) {
      alertify.success("Orden registrada!");
    },
  });
};

function comprobarCaja() {
  $.ajax({
    url: "controladores/gastos.controlador.php",
    type: "POST",
    data: {
      accion: "obtenerCaja",
    },
    success: function (respuesta) {
      let datos = JSON.parse(respuesta)

      if (datos.length == 0) {
        $("#staticBackdrop").modal("show");
      } else {
        $("#staticBackdrop").modal("hide");
      }
    },
  });

}

export { enviarTicket, generarOrden, comprobarCaja };
