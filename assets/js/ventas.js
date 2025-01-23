import { enviarTicket, generarOrden } from "./funciones.js";
import imagen from "./logo.js";
$(document).ready(function () {
  let reciboVentas = $("#tabVentas").DataTable({
    ajax: {
      url: "controladores/ordenes.controlador.php",
      type: "GET",
      data: function (d) {
        d.accion = "obtenerOrdenes";
      },
      dataType: "json",
    },
    language: {
      url: "assets/js/mx.json",
    },
    lengthMenu: [
      [10, 15, 20, -1],
      [10, 15, 20, "Todos"],
    ],
    order: [[3, "desc"]],
    columns: [
      { data: "id", visible: false },
      { data: "cliente" },

      { data: "empleado" },
      {
        data: "fecha",
        render: function (data, type, row) {
          if (type === 'display') {
            return moment(data).format("DD/MM/YYYY H:mm:ss");
          }
          return data;
        },
      },
      { data: "n_orden" },
      {
        data: "total",
        render: function (data, type, row) {
          let montoNumerico = parseFloat(data);
          if (isNaN(montoNumerico)) {
            return data;
          }
          return montoNumerico.toLocaleString("es-MX", {
            style: "currency",
            currency: "MXN",
          });
        },
      },
      {
        data: null,
        className: "dt-center",
        defaultContent: `<div class="d-flex justify-content-center align-items-center">
        ${$("#rol").text() == "admin"
            ? '<button type="button" class="btn btn-deletVentaEdit"><i class="fa fa-times text-secondary" aria-hidden="true" style="font-size:1.5rem;"></i></button>'
            : ""
          }
          <button type="button" class="btn btn-print-tux"><i class="fa fa-print text-secondary" aria-hidden="true" style="font-size:1.5rem;"></i></button>
          <button type="button" class="btn btn-edit" data-bs-toggle="modal" data-bs-target="#staticBackdrop"><i class="fa fa-pencil text-secondary" aria-hidden="true" style="font-size:1.5rem;"></i></button>
          </div>`,
        orderable: false,
      },
    ],
    columnDefs: [
      { targets: 0, className: " align-middle" },
      { targets: 1, className: "align-middle text-start" },
      { targets: 2, className: "align-middle text-center" },
      { targets: 3, className: "text-center align-middle" },
      { targets: 4, className: "text-center align-middle" },
      { targets: 5, className: "text-center align-middle" },
      { targets: 6, className: "text-center align-middle" },
    ],
    footerCallback: function (row, data, start, end, display) {
      let api = this.api();
      let total = api
        .column(5, {
          page: "current",
        })
        .data()
        .reduce(function (a, b) {
          return parseFloat(a) + parseFloat(b);
        }, 0);

      let formato = total.toLocaleString("es-MX", {
        style: "currency",
        currency: "MXN",
      });

      $(api.column(4).footer()).html("TOTAL");
      $(api.column(5).footer()).html(
        "<p style='width:7rem;margin:0 auto;font-size:1.3rem;color:green;'>" +
        formato +
        "</p>"
      );
    },
  });

  //* /* ############################# Variables ############################# */
  const listaItems = document.querySelector("#lista-items-edit");
  let articulosCarrito = [];
  let idRow = "";

  cargarListaMenu();

  //* /* ############################# Eliminar venta ############################# */
  $(document).on("click", ".btn-deletVentaEdit", function (e) {
    let row = $(this).closest("tr");
    let rowData = $("#tabVentas").DataTable().row(row).data();
    let idVenta = rowData.id;

    fetch(
      `controladores/detalle_venta.controlador.php?accion=obtenerDetalles&id=${idVenta}`
    )
      .then((response) => response.json())
      .then((data) => {
        $.ajax({
          url: "controladores/ordenes.controlador.php",
          type: "POST",
          data: {
            accion: "eliminarOrden",
            datos: data,
          },
          success: function (respuesta) {
            reciboVentas.ajax.reload(null, false);
            alertify.error("Venta eliminada!");
          },
        });
      })
      .catch((error) => {
        console.error("Error:", error);
      });
  });

  //* /* ############################# Editar carrito ############################# */
  $(document).on("click", ".btn-edit", function (e) {
    e.preventDefault();
    let row = $(this).closest("tr");
    let rowData = $("#tabVentas").DataTable().row(row).data();
    $("#nombreClienteEdit").val(rowData.cliente);
    $("#ordenEdit").text(rowData.n_orden);

    idRow = rowData.id;

    fetch(
      `controladores/detalle_venta.controlador.php?accion=obtenerDetalles&id=${idRow}`
    )
      .then((response) => response.json())
      .then((data) => {
        articulosCarrito = data;
        carritoHTML();
        actualizarTotal();
      })
      .catch((error) => {
        console.error("Error:", error);
      });
  });

  //* /* ############################# Cargar lista de platillos ############################# */

  function cargarListaMenu() {
    $.ajax({
      url: "controladores/productos.controlador.php",
      type: "GET",
      data: {
        accion: "obtenerProductos",
      },
      success: function (respuesta) {
        let menu = JSON.parse(respuesta);
        let html = "";
        menu.forEach((element) => {
          html += `
          <li>
            <div class="delicious">
                <img class="rounded-circle" src="${element.img}" alt="Product Image" style="width:50px;">
                <h6>${element.cod}</h6>
                <h5 hidden>${element.cat}</h5>
                <h4 hidden>${element.porcion}</h4>
                <span>$${element.precio}</span>
            </div>
            <div class="box-items">
              <p>${element.des}</p>
              <a href="#!" data-id =${element.id}>
              <i class="fa item-add-edit fa-plus-circle text-warning fa-2x" aria-hidden="true"></i>
              </a>
            </div>
          </li>
          `;
        });
        $(".menu-items").html(html);
      },
    });
  }

  //* /* ############################# Agregar platillo ############################# */

  $(".delicious-menu").on("click", function (e) {
    if (e.target.classList.contains("item-add-edit")) {
      const item = e.target.closest("li");
      leerDatosItem(item);
    }
  });

  function leerDatosItem(item) {
    const infoItem = {
      id: item.querySelector("a").getAttribute("data-id"),
      cod: item.querySelector("h6").textContent,
      porcion: item.querySelector("h4").textContent,
      precio: item.querySelector("span").textContent.replace("$", ""),
      cat: item.querySelector("h5").textContent,
      des: item.querySelector("p").textContent,
      cantidad: 1,
      img: item.querySelector("img").src,
    };

    if (articulosCarrito.some((item) => item.id === infoItem.id)) {
      const items = articulosCarrito.map((item) => {
        if (item.id === infoItem.id) {
          item.cantidad++;
          return item;
        } else {
          return item;
        }
      });
      articulosCarrito = [...items];
    } else {
      articulosCarrito = [...articulosCarrito, infoItem];
    }

    carritoHTML();
    actualizarTotal();
  }

  //* /* ############################# Redibujar carrito ############################# */

  function carritoHTML() {
    limpiarCarrito();
    articulosCarrito.forEach((item) => {
      const li = document.createElement("li");
      li.innerHTML = `
                <div class="d-flex align-items-center position-relative justify-content-around item-li">
                    <div class="p-img light-bg">
                        <img class="rounded-circle" src="${item.img
        }" alt="Product Image">
                    </div>
                    <div class="p-data">
                      <h3 class="font-semi-bold">${item.cod}</h3>
                      <h4 hidden>${item.porcion}</h4>
                      <span style="font-size:12px;">${item.des}</span>
                      <p class="text-secondary">
                    ${item.cantidad} x  $${item.precio} 
                    <i class="fa fa-arrow-right" aria-hidden="true"></i>  
                    $${item.cantidad * item.precio}
                    </p>
                    </div>
                    <button type="button" class="close borrar-item-edit" data-id-edit=${item.id
        }>×</button>
                </div> 
            `;

      listaItems.appendChild(li);
    });
  }

  //* /* ############################# Limpiar carrito ############################# */
  function limpiarCarrito() {
    while (listaItems.firstChild) {
      listaItems.removeChild(listaItems.firstChild);
    }
  }

  //* /* ############################# Formatear cantidades ############################# */
  function formatearCantidad(cantidad) {
    let monedaMexicana = new Intl.NumberFormat("es-MX", {
      style: "currency",
      currency: "MXN",
      minimumFractionDigits: 2,
    }).format(cantidad);

    return monedaMexicana;
  }

  //* /* ############################# Actualizar totales ############################# */
  function actualizarTotal() {
    let total = articulosCarrito.reduce((acumulador, item) => {
      let precio = item.precio;
      let precioNumerico = parseFloat(precio);
      let cantidad = parseFloat(item.cantidad);
      return acumulador + precioNumerico * cantidad;
    }, 0);

    let totalFormat = formatearCantidad(total);

    $(".precioTotalEdit").text(totalFormat);
  }

  //* /* ############################# Eliminar platillo ############################# */
  $(document).on("click", ".borrar-item-edit", function (e) {
    e.preventDefault();
    const itemId = e.target.getAttribute("data-id-edit");

    articulosCarrito.forEach((item) => {
      if (item.id == itemId) {
        if (item.cantidad > 1) {
          item.cantidad = item.cantidad - 1;
        } else {
          articulosCarrito = articulosCarrito.filter(
            (item) => !(item.id == itemId)
          );
        }
      }
    });

    carritoHTML();
    actualizarTotal();
  });

  //* /* ############################# Generar ticket btn GUARDAR ############################# */
  $("#ticketEdit").on("click", function (e) {
    e.preventDefault();

    let total = $(".precioTotalEdit").text();
    let cliente = $("#nombreClienteEdit").val();
    let numOrden = $("#ordenEdit").text();
    let fechaFormat = moment().format("YYYY-MM-DD H:mm:ss");
    let fecha = moment().format("DD/MM/YYYY H:mm:ss");
    let empleado = $("#nameSesion").text();

    if (cliente == "") return;

    $.ajax({
      url: "controladores/ordenes.controlador.php",
      type: "POST",
      data: {
        accion: "editarOrden",
        productos: articulosCarrito,
        cliente: cliente,
        fecha: fechaFormat,
        total: total,
        empleado: empleado,
      },
      success: function (respuesta) {
        //? Pendiente la implementacion de enviar ticket directo a la impresora
        reciboVentas.ajax.reload(null, false);
        $("#staticBackdrop").modal("hide");
        alertify.success("Cambios guardados");
      },
    });
  });

  //* /* ############################# Generar ticket desde tabla ############################# */
  $(document).on("click", ".btn-print-edit", function (e) {
    e.preventDefault();

    let row = $(this).closest("tr");
    let rowData = $("#tabVentas").DataTable().row(row).data();

    let id = rowData.id;
    let cliente = rowData.cliente;
    let numOrden = rowData.n_orden;
    let total = formatearCantidad(rowData.total);
    let fecha = rowData.fecha;
    let fechaFormat = moment(fecha).format("DD/MM/YYYY H:mm:ss");
    let empleado = $("#nameSesion").text();

    fetch(
      `controladores/detalle_venta.controlador.php?accion=obtenerDetalles&id=${id}`
    )
      .then((response) => response.json())
      .then((data) => {
        generarOrden(fechaFormat, numOrden, cliente, total, empleado, data);

        if (generarOrden) {
          enviarTicket(cliente, empleado, total, fecha, numOrden, data);
        }
      })
      .catch((error) => {
        console.error("Error:", error);
      });
  });

  //? /* ############################# Generar ticket DIRECTAMENTE -- PENDIENTE ############################# */
  $(document).on("click", ".btn-print-tux", function (e) {
    e.preventDefault();
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF({
      orientation: "portrait",
      unit: "mm",
      format: [58, 210],
    });

    const logo = imagen;

    let row = $(this).closest("tr");
    let rowData = $("#tabVentas").DataTable().row(row).data();

    let id = rowData.id;
    let cliente = rowData.cliente;
    let numOrden = rowData.n_orden;
    let total = formatearCantidad(rowData.total);
    let fecha = rowData.fecha;
    let fechaFormat = moment(fecha).format("DD/MM/YYYY H:mm:ss");
    let empleado = $("#nameSesion").text();

    fetch(
      `controladores/detalle_venta.controlador.php?accion=obtenerDetalles&id=${id}`
    )
      .then((response) => response.json())
      .then((data) => {
        doc.addImage(logo, "PNG", 20, 5, 18, 8);
        doc.setFont("courier", "bold");
        doc.setFontSize(10);
        doc.setTextColor(0, 0, 0);
        doc.text("TIO POLLO EXPRESS", 10, 20);

        doc.setFontSize(8);
        doc.text(`Orden N°. ${numOrden}`, 3, 30);
        doc.text(`Total: ${total}`, 3, 35);
        doc.text(`Atendido por: ${empleado}`, 3, 40);
        doc.text(`Cliente: ${cliente}`, 3, 45);
        doc.text(`Fecha: ${fechaFormat}`, 3, 50);

        // Detalles del pedido
        doc.text("--------- DETALLES ----------", 3, 55);
        let yPosition = 60;

        doc.setFontSize(7);
        data.forEach((element, index) => {
          let lineaProducto = `${index + 1}. ${element.cod} | Cant: ${element.cantidad
            } | $${(element.cantidad * element.precio).toFixed(2)}`;
          doc.text(lineaProducto, 3, yPosition);
          yPosition += 4;
        });

        doc.text("---------------------------------", 5, yPosition + 2);
        doc.save(`${numOrden}.pdf`);
        alertify.success("Reimprimiendo ticket!");
      })
      .catch((error) => {
        console.error("Error:", error);
      });
  });







});
