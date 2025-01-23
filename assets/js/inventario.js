$(document).ready(function () {
  let id;
  let tablaInventario = $(".inventario").DataTable({
    ajax: {
      url: "controladores/inventario.controlador.php",
      type: "GET",
      data: function (d) {
        d.accion = "obtenerInventario";
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
    columns: [
      { data: "id", visible: false },
      {
        data: "cat",
        render: function (data, type, row) {
          let formato = capitalizeFirstLetter(data);
          return formato;
        },
      },
      { data: "des" },
      { data: "stock_actual" },

      { data: "unidad_medida" },
      {
        data: null,
        className: "dt-center",
        defaultContent:
          '<div class="d-flex justify-content-center align-items-center"><button type="button" class="btn btn-editInventario" data-bs-toggle="modal" data-bs-target="#inventario"><i class="fa fa-pencil text-secondary" aria-hidden="true" style="font-size:1.5rem;"></i></button></div>',
        orderable: false,
      },
    ],

    columnDefs: [
      { targets: 0, className: "align-middle text-center" },
      { targets: 1, className: "align-middle text-start" },
      { targets: 2, className: "align-middle text-start" },
      { targets: 3, className: "align-middle text-center" },
      { targets: 4, className: "align-middle text-center" },
    ],
  });

  function capitalizeFirstLetter(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
  }


  /*=============================================
        EDITAR PRODUCTO
        =============================================*/
  $(document).on("click", ".btn-editInventario", function (e) {
    let row = $(this).closest("tr");
    let rowData = $(".inventario").DataTable().row(row).data();

    id = rowData.id;
    let des = rowData.des;
    let cantidad = rowData.stock_actual;
    let cat = rowData.cat;

    $("#cantidadInventario").val(cantidad);

  });

  //Guardar cambios producto
  $(document).on("click", "#guardarCambiosInventario", function (e) {
    e.preventDefault();
    let formData = new FormData();
    formData.append("accion", "editarInventario");
    formData.append("id", id);
    formData.append("nuevo_stock", $("#cantidadInventario").val());

    $.ajax({
      url: "controladores/inventario.controlador.php",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function (respuesta) {
        tablaInventario.ajax.reload(null, false);
        $("#inventario").modal("hide");
        alertify.success("Cambios guardados!");
      },
      error: function (error) {
        console.error("Error en la solicitud AJAX:", error);
      },
    });
  });
});
