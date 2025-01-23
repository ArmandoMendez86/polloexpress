import { enviarTicket, generarOrden, comprobarCaja } from "./funciones.js";
import imagen from "./logo.js";
$(document).ready(function () {
  let table = $("#menuTable").DataTable({
    dom: '<"top"f>rt<"bottom"pl><"clear">',
    scrollY: "600px",
    paging: true,
    searching: true,
    info: false,
    hover: false,
    /*   search: {
      return: true,
    }, */
    columnDefs: [
      {
        targets: "_all",
        orderable: false,
      },
    ],
    ajax: {
      url: "controladores/productos.controlador.php",
      dataSrc: function (json) {
        //console.log(json);
        return json.map(function (item) {
          return [
            `   <div class="fast-food-menus">
                    <div class="fast-food-img">
                      <img alt="fast-food-img" src="assets/img/generic_product.png">
                    </div>
                  <div>
                    <button type="button" class="apply-coupon rounded-circle" style="width:50px;height:50px;padding:8px;position:absolute;top:5px;right:10px;font-size:13px;">${item.id}</button>
                    ${item.cod == 'COS GRANEL' ?

              (
                //Costilla a granel

                `<h3>${item.cod}</h3>
                      <input type="number" min="80" class="form-control" />
                      <h5 hidden>${item.cat}</h5>
                  </div>
                    <a href="#!" data-id =${item.id}>
                    <i class="fa btn-add fa-cart-plus fa-2x text-white" aria-hidden="true"></i>
                    </a>
                </div>`


              ) :

              (
                //Producto normal

                `<h3>${item.cod}</h3>
                      <span>${item.precio}</span>
                      <h5 hidden>${item.cat}</h5>
                  </div>
                    <a href="#!" data-id =${item.id}>
                    <i class="fa btn-add fa-cart-plus fa-2x text-white" aria-hidden="true"></i>
                    </a>
                </div>`
              )}
                  `];
        });
      },
      data: function (d) {
        d.accion = "obtenerProductos";
      },
    },
    language: {
      url: "assets/js/mx.json",
    },
    lengthMenu: [
      [6, 12, 18, -1],
      [6, 12, 18, "Todos"],
    ],
  });


  let inputSearch;

  table.on('init', function () {
    const searchInput = $('#dt-search-0');
    inputSearch = searchInput
    if (searchInput.length > 0) {
      searchInput.focus();
    } else {
      console.error('No se encontró el input de búsqueda.');
    }
  });

  //Modal para apertura de caja

  comprobarCaja()

  $("#guardarCaja").click(() => {

    let montoCaja = $("#montoCaja").val();
    let fecha = moment().format("YYYY-MM-DD H:mm:ss");

    let datos = new FormData();
    datos.append('accion', 'aperturaCaja')
    datos.append('monto', montoCaja)
    datos.append('fecha', fecha)


    $.ajax({
      url: "controladores/gastos.controlador.php",
      type: "POST",
      data: datos,
      processData: false,
      contentType: false,
      success: function (respuesta) {
        let datos = JSON.parse(respuesta)

        if (datos == 'ok') {
          $("#staticBackdrop").modal("hide");
        }

      },
    });



  })


  //* /* ############################# Variables ############################# */
  const listaItems = document.querySelector("#lista-items");
  let articulosCarrito = [];
  onOfCarrito();

  //* /* ############################# Formatear cantidad ############################# */
  function formatearCantidad(cantidad) {
    let monedaMexicana = new Intl.NumberFormat("es-MX", {
      style: "currency",
      currency: "MXN",
      minimumFractionDigits: 2,
    }).format(cantidad);

    return monedaMexicana;
  }

  //* /* ############################# Eventos click ############################# */
  $("#menuTable").on("click", function (e) {
    if (e.target.classList.contains("btn-add")) {
      const item = e.target.closest(".fast-food-menus");

      leerDatosItem(item);

      $(".cart-popup").addClass("show-cart");
    }
  });
  $("#vaciar-carrito").on("click", function (e) {
    vaciarCarrito();
  });

  //* /* ############################# Generar id venta ############################# */
  function generarIdVenta() {
    const randomPart = Math.floor(Math.random() * 1000)
      .toString()
      .padStart(3, "0");

    const timePart = Date.now().toString().slice(-2);
    const numeroOrden = randomPart + timePart;

    return numeroOrden;
  }

  //? /* ############################# Generar ticket PENDIENTE-IMPLEMENTACION ############################# */
  /*  $("#ticket").on("click", async function (e) {
    e.preventDefault();

    let total = $(".precioTotal").text();
    let cliente = $("#nombreCliente").val();
    let fecha = moment().format("DD/MM/YYYY H:mm:ss");
    let fechaFormat = moment().format("YYYY-MM-DD H:mm:ss");
    let numOrden = generarIdVenta();
    let empleado = "Prueba";
    let articulos = articulosCarrito;

    if (cliente == "") return;
    if (articulos.length === 0) return;

    generarOrden(fechaFormat, numOrden, cliente, total, empleado, articulos);
    if (generarOrden) {
      enviarTicket(cliente, empleado, total, fecha, numOrden, articulos);
      vaciarCarrito();
    }
  }); */

  //* /* ############################# Generar Ticket despues de guardar ############################# */
  //Produccion
  $("#ticket").on("click", function (e) {
    e.preventDefault();


    const { jsPDF } = window.jspdf;
    const doc = new jsPDF({
      orientation: "portrait",
      unit: "mm",
      format: [58, 210],
    });

    const logo = imagen;
    doc.addImage(logo, "PNG", 20, 5, 18, 8);

    let total = $(".precioTotal").text();
    let cliente = $("#nombreCliente").val();
    let fecha = moment().format("DD/MM/YYYY H:mm:ss");
    let fechaFormat = moment().format("YYYY-MM-DD H:mm:ss");
    let numOrden = generarIdVenta();
    let empleado = $("#nameSesion").text();
    let recibe = $(".recibe").val();
    let cambio = $(".cambio").val();

    if (cliente === "") return;

    // Encabezado
    doc.setFontSize(7);
    doc.setFont("times", "normal");
    doc.setTextColor(0, 0, 0);
    doc.text("R.F.C. RAPF860807286", 16, 16);
    doc.setFontSize(10);
    doc.text("TIO POLLO EXPRESS", 12, 20);
    doc.setFontSize(8);
    doc.text("Pedido a domicilio: 742 117 90 56", 10, 24);
    doc.text("Pedido a domicilio: 742 688 24 28", 10, 27);

    doc.setFontSize(12);
    doc.text(`Orden N°. ${numOrden}`, 3, 37);
    doc.text(`Total: ${total}`, 3, 42);
    doc.text(`Pago con: ${recibe}`, 3, 47);
    doc.text(`Su cambio: ${cambio}`, 3, 52);
    doc.text(`Atendido por: ${empleado}`, 3, 57);
    doc.text(`Cliente: ${cliente}`, 3, 62);
    doc.text(`Fecha: ${fecha}`, 3, 67);
    doc.text("Gracias por su compra.", 12, 75);

    // Detalles del pedido
    doc.text("--------- DETALLES ----------", 5, 85);
    let yPosition = 94;

    doc.setFontSize(10);
    articulosCarrito.forEach((element, index) => {
      let lineaProducto = `${index + 1}. ${element.cod} | Cant: ${element.cantidad
        } | $${(element.cantidad * element.precio).toFixed(2)}`;
      doc.text(lineaProducto, 3, yPosition);
      yPosition += 8;
    });

    doc.text("------------------------------------------", 5, yPosition + 2);

    // Imprimir directamente
    doc.autoPrint();
    window.open(doc.output('bloburl'), '_blank');

    generarOrden(
      fechaFormat,
      numOrden,
      cliente,
      total,
      empleado,
      articulosCarrito
    );
    vaciarCarrito();
    inputSearch.focus()
  });

  //* /* ############################# Eliminar platillo de carrito ############################# */
  $(document).on("click", ".borrar-item", function (e) {
    e.preventDefault();

    const itemLiP = e.target.closest(".item-li-p");

    const itemId = e.target.getAttribute("data-id");

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
    cantidadArticulos();
    if (articulosCarrito.length == 0) {
      ocultarCarro();
    }
  });

  //* /* ############################# Leer orden ############################# */
  function leerDatosItem(item) {

    let tipoProducto = item.querySelector("a").getAttribute("data-id");
    let infoItem = {};
    if (tipoProducto == '102') {

      infoItem = {
        id: item.querySelector("a").getAttribute("data-id"),
        cod: item.querySelector("h3").textContent,
        precio: item.querySelector("input").value,
        cat: item.querySelector("h5").textContent,
        cantidad: 1,
        imagen: item.querySelector("img").src,
      };

    } else {
      infoItem = {
        id: item.querySelector("a").getAttribute("data-id"),
        cod: item.querySelector("h3").textContent,
        precio: item.querySelector("span").textContent,
        cat: item.querySelector("h5").textContent,
        cantidad: 1,
        imagen: item.querySelector("img").src,
      };

    }


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
    cantidadArticulos();
  }

  //* /* ############################# Regenerar HTML ############################# */
  function carritoHTML() {
    limpiarCarrito();
    articulosCarrito.forEach((item) => {
      const li = document.createElement("li");
      li.innerHTML = `
                <div class="d-flex align-items-center position-relative justify-content-around item-li-p">
                <div class="p-img light-bg">
                    <img src="${item.imagen
        }" class="rounded-circle" alt="Product Image">
                </div>
                <div class="p-data">
                    <h3 class="font-semi-bold">${item.cod}</h3>
                    <p class="text-secondary">
                    ${item.cantidad} x  $${item.precio} 
                    <i class="fa fa-arrow-right" aria-hidden="true"></i>  
                    $${item.cantidad * item.precio}
                    </p>
                </div>
                <button type="button" class="close borrar-item" data-id=${item.id
        }>×</button>
                </div> 
            `;
      listaItems.appendChild(li);
    });
  }

  //* /* ############################# Limpiar carrito ############################# */
  function limpiarCarrito() {
    // forma rapida (recomendada)
    while (listaItems.firstChild) {
      listaItems.removeChild(listaItems.firstChild);
    }
  }

  //* /* ############################# Actualizar totales ############################# */
  function actualizarTotal() {
    let total = articulosCarrito.reduce((acumulador, item) => {
      let precio = parseFloat(item.precio);
      let cantidad = parseFloat(item.cantidad);
      return acumulador + precio * cantidad;
    }, 0);

    let totalFormat = formatearCantidad(total);

    $(".precioTotal").text(totalFormat);
  }

  //* /* ############################# Ocultar carrito vacio ############################# */
  function ocultarCarro() {
    $("#nombreCliente").val("");
    setTimeout(() => {
      $(".cart-popup").removeClass("show-cart");
    }, 1000);
  }

  //*  /* ############################# Número de articulos ############################# */
  function cantidadArticulos() {
    let numeroArticulos = articulosCarrito.reduce(
      (acumulador, item, cliente) => {
        return acumulador + item.cantidad;
      },
      0
    );
    $(".donation").attr("data-count", numeroArticulos);

    onOfCarrito();
  }

  //* /* ############################# Efecto pointer a botton ############################# */
  function onOfCarrito() {
    if (articulosCarrito.length == 0) {
      $("#ticket").css("pointer-events", "none");
    } else {
      $("#ticket").css("pointer-events", "auto");
    }
  }

  //* /* ############################# Calcular cambio ############################# */
  $(document).on("input", ".recibe", function (e) {
    e.preventDefault();

    // Obtener el valor del campo y convertirlo a número
    let valor = $(this).val();
    let total = $(".precioTotal").text(); // Leer el total de texto
    let totalNumerico = parseFloat(total.replace(/[^0-9.-]+/g, "")); // Convertir a número

    let cambio = 0;

    if (valor !== "" && valor !== null) {
      let valorNumerico = parseFloat(valor) || 0;
      cambio = valorNumerico - totalNumerico;
    }

    $(".cambio").val(cambio.toFixed(2));
  });

  //* /* ############################# Vaciar carrito ############################# */
  function vaciarCarrito() {
    while (listaItems.firstChild) {
      listaItems.removeChild(listaItems.firstChild);
    }
    articulosCarrito = [];
    actualizarTotal();
    ocultarCarro();
    cantidadArticulos();
    $(".recibe").val(null);
    $(".cambio").val(0);
  }

});
