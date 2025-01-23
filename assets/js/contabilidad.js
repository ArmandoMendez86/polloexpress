$(document).ready(function () {
  let tablaContabilidad = $("#contabilidad").DataTable({
    ajax: {
      url: "controladores/contabilidad.controlador.php",
      type: "GET",
      data: function (d) {
        d.accion = "obtenerContabilidad";
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
    order: [[9, "desc"]],
    columns: [
      {
        data: "cat",
      },
      {
        data: "cod",
      },
      {
        data: "des",
      },
      {
        data: "precio",
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
        data: "porcion",
        render: function (data, type, row) {
          if (type === "display") {

            if (data == "1") {
              return `<span class="badge bg-danger text-white">${data}</span>`;
            }
            if (data == "0.75") {
              return `<span class="badge bg-danger text-white">3/4</span>`;
            }
            if (data == "0.5") {
              return `<span class="badge bg-danger text-white">1/2</span>`;
            }
            if (data == "0.25") {
              return `<span class="badge bg-danger text-white">1/4</span>`;
            }
          }
          return data;
        },
      },
      {
        data: "empleado",
      },
      {
        data: "n_orden",
      },
      {
        data: "cantidad",
      },
      {
        data: "cantidad_neta",
      },
     
      {
        data: "fecha",
        render: function (data, type, row) {
          if (type === 'display') {
            return moment(data).format("DD/MM/YYYY H:mm:ss");
          }
          return data;
        },

      },
    ],

    columnDefs: [
      { targets: 0, className: "align-middle text-center" },
      { targets: 1, className: "align-middle text-center" },
      { targets: 2, className: "align-middle text-center" },
      { targets: 3, className: "align-middle text-center" },
      { targets: 4, className: "align-middle text-center" },
      { targets: 5, className: "align-middle text-center" },
      { targets: 6, className: "align-middle text-center" },
      { targets: 7, className: "align-middle text-center" },
      { targets: 8, className: "align-middle text-center" },
      { targets: 9, className: "align-middle text-center" },

    ],

    footerCallback: function (row, data, start, end, display) {
      let api = this.api();


      let totalProducto = api
        .column(8, { page: "current" })
        .data()
        .reduce(function (a, b) {
          return parseFloat(a) + parseFloat(b);
        }, 0);


      $(api.column(7).footer()).html("TOTALES");

      $(api.column(8).footer()).html(
        `<p style='width:7rem;margin:0 auto;font-size:1.3rem;color:green;font-weight:bold;'>${totalProducto}</p>`
      );

    },
  });
});
