$(document).ready(function () {
  let tablaGasto = $(".gastos").DataTable({
    ajax: {
      url: "controladores/gastos.controlador.php",
      type: "GET",
      data: function (d) {
        d.accion = "obtenerGasto";
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
    order: [[4, "desc"]],
    columns: [
      { data: "id", visible: false },
      {
        data: "concepto",
        render: function (data, type, row) {
          return data.toUpperCase();
        },
      },
      {
        data: "monto",
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
        data: "fecha",
        render: function (data, type, row) {
          if (type === 'display') {
            return moment(data).format("DD/MM/YYYY H:mm:ss");
          }
          return data;
        },
      },
      {
        data: null,
        className: "dt-center",
        render: function (data, type, row) {
          let eliminarBtn =
            row.concepto === "caja"
              ? ""
              : '<button type="button" class="btn btn-deletGasto"><i class="fa fa-times text-secondary" aria-hidden="true" style="font-size:1.5rem;"></i></button>';

          return `<div class="d-flex justify-content-center align-items-center">
                    <button type="button" class="btn btn-editGasto" data-bs-toggle="modal" data-bs-target="#gastos">
                      <i class="fa fa-pencil text-secondary" aria-hidden="true" style="font-size:1.5rem;"></i>
                    </button>
                    ${eliminarBtn}
                  </div>`;
        },
        orderable: false,
      },
    ],

    columnDefs: [
      { targets: 0, className: "align-middle" },
      { targets: 1, className: "align-middle text-start" },
      { targets: 2, className: "align-middle text-center" },
      { targets: 3, className: "align-middle text-center" },
      { targets: 4, className: "align-middle text-center" },
    ],

    footerCallback: function (row, data, start, end, display) {
      let api = this.api();
      let total = api
        .column(2, {
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

      $(api.column(2).footer()).html(
        "<p style='width:7rem;margin:0 auto;font-size:1.3rem;color:red;'>" +
        formato +
        "</p>"
      );
    },
  });



  function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
  }

  $(document).on("click", ".btnAgregarGasto", function () {
    $("#guardarCambiosGasto").addClass("d-none");
    $("#agregarGasto").removeClass("d-none");
  });

  $("#gastos").on("hidden.bs.modal", function (e) {
    $("#formGasto").trigger("reset");
  });

  $(document).on("click", ".btn-editGasto", function (e) {
    $("#guardarCambiosGasto").removeClass("d-none");
    $("#agregarGasto").addClass("d-none");

    let row = $(this).closest("tr");
    let rowData = $(".gastos").DataTable().row(row).data();

    let id = rowData.id;
    let concepto = rowData.concepto;
    let monto = rowData.monto;

    $("#idGasto").val(id);
    $("#concepto").val(concepto);

    $("#monto").val(monto);
  });

  $(document).on("click", "#guardarCambiosGasto", function (e) {
    e.preventDefault();
    let fecha = moment().format("YYYY-MM-DD H:mm:ss");
    let datos = new FormData();
    datos.append("accion", "editarGasto");
    datos.append("id", $("#idGasto").val());
    datos.append("concepto", $("#concepto").val());
    datos.append("monto", $("#monto").val());
    datos.append("fecha", fecha);

    $.ajax({
      url: "controladores/gastos.controlador.php",
      type: "POST",
      data: datos,
      processData: false,
      contentType: false,
      success: function (respuesta) {
        tablaGasto.ajax.reload(null, false);
        $("#gastos").modal("hide");
        alertify.success("Cambios guardados!");
      },
      error: function (error) {
        console.error("Error en la solicitud AJAX:", error);
      },
    });
  });

  $(document).on("click", "#agregarGasto", function () {
    let concepto = $.trim($("#concepto").val()).toLowerCase();
    let monto = $("#monto").val();
    let tipo = $("#tipoGasto").val();
    let fecha = moment().format("YYYY-MM-DD H:mm:ss");

    let datos = new FormData();
    datos.append("accion", "agregarGasto");
    datos.append("concepto", concepto);
    datos.append("monto", monto);
    datos.append("fecha", fecha);

    $.ajax({
      url: "controladores/gastos.controlador.php",
      method: "POST",
      data: datos,
      contentType: false,
      processData: false,
      success: function (response) {
        tablaGasto.ajax.reload(null, false);
        $("#gastos").modal("hide");
        $("#formGasto").trigger("reset");
        alertify.success("Gasto agregado!");
      },
    });
  });

  $(document).on("click", ".btn-deletGasto", function (e) {
    e.preventDefault();
    let row = $(this).closest("tr");
    let rowData = $(".gastos").DataTable().row(row).data();
    let id = rowData.id;

    let datos = new FormData();
    datos.append("accion", "eliminarGasto");
    datos.append("id", id);

    $.ajax({
      url: "controladores/gastos.controlador.php",
      method: "POST",
      data: datos,
      contentType: false,
      processData: false,
      success: function (response) {
        tablaGasto.ajax.reload(null, false);
        alertify.error("Gasto eliminado!");
      },
    });
  });
});
