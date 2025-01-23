$(document).ready(function () {
  let tablaMenu = $(".historial").DataTable({
    ajax: {
      url: "controladores/movimientos.controlador.php",
      type: "GET",
      data: function (d) {
        d.accion = "obtenerMovimientos";
      },
      dataType: "json",
    },
    language: {
      url: "assets/js/mx.json",
    },
    lengthMenu: [
      [5, 10, 15, -1],
      [5, 10, 15, "Todos"],
    ],
    order: [[9, "desc"]],
    columns: [
      { data: "idventa", visible: false },
      {
        data: "cod",
      },
      { data: "des" },

      {
        data: "cantidad",
      },
      {
        data: "porcion",
        render: function (data, type, row) {
          if (data == "0.25") {
            return `<span class="badge bg-dark">1/4</span>`;
          } else if (data == "0.5") {
            return `<span class="badge bg-dark">1/2</span>`;
          } else if (data == "0.75") {
            return `<span class="badge bg-dark">3/4</span>`;
          } else if (data == "1") {
            return `<span class="badge bg-dark">1</span>`;
          } else {
            return `<span class="badge bg-dark">${data}</span>`;
          }
        },
      },
      { data: "existia" },
      { data: "existe" },
      {
        data: "tipo",
        render: function (data, type, row) {
          if (data == "salida") {
            return `<span class="badge bg-danger text-dark">${data}</span>`;
          } else {
            return `<span class="badge bg-success">${data}</span>`;
          }
        },
      },
      { data: "empleado" },
      {
        data: "fecha",
        render: function (data, type, row) {
          if (type === "display") {
            return moment(data).format("DD/MM/YYYY H:mm:ss");
          }
          return data;
        },
      },
      { data: "n_orden" },
    ],

    columnDefs: [
      { targets: 0, className: "align-middle" },
      { targets: 1, className: "align-middle" },
      { targets: 2, className: "align-middle" },
      { targets: 3, className: "align-middle text-center" },
      { targets: 4, className: "align-middle text-center" },
      { targets: 5, className: "align-middle text-center" },
      { targets: 6, className: "align-middle text-center" },
      { targets: 7, className: "align-middle text-center" },
      { targets: 8, className: "align-middle text-center" },
      { targets: 9, className: "align-middle text-center" },
      { targets: 10, className: "align-middle text-center" },
    ],
    createdRow: function (row, data, dataIndex) {
      $("td:eq(5)", row).css("background-color", "#d7ffef");
      $("td:eq(5)", row).css("color", "green");
    },
  });
});
