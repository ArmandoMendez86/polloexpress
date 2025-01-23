$(document).ready(function () {
  let tablaUsuarios = $("#usuarios").DataTable({
    ajax: {
      url: "controladores/usuarios.controlador.php",
      type: "GET",
      data: function (d) {
        d.accion = "obtenerUsuarios";
      },
      dataType: "json",
    },
    language: {
      url: "assets/js/mx.json",
    },
    order: [[0, "asc"]],
    columns: [
      { data: "id", visible: false },
      {
        data: "nombre",
        render: function (data, type, row) {
          return data.toUpperCase();
        },
      },
      {
        data: "apellido",
        render: function (data, type, row) {
          return data.toUpperCase();
        },
      },
      { data: "usuario" },
      {
        data: "estado",
        render: function (data, type, row) {
          if (data == 1) {
            return `<span class="badge statusUsuario bg-success p-2 d-inline-flex justify-content-center" style="width:80px;">Activado</span>`;
          } else {
            return `<span class="badge statusUsuario  bg-danger p-2 d-inline-flex justify-content-center" style="width:80px;">Desactivado</span>`;
          }
        },
      },
      {
        data: "ultimo_login",
        render: function (data, type, row) {
          if (type === "display") {
            return moment(data).format("DD/MM/YYYY H:mm:ss");
          }
          return data;
        },
      },
      {
        data: null,
        className: "dt-center",
        defaultContent:
          '<div class="d-flex justify-content-center align-items-center"><button class="btn btn-editUsuario" data-bs-toggle="modal" data-bs-target="#usuariosMod"><i class="fa fa-pencil text-secondary" aria-hidden="true" style="font-size:1.5rem;"></i></button><button class="btn btn-deletUsuario"><i class="fa fa-times text-secondary" aria-hidden="true" style="font-size:1.5rem;"></i></button></div>',
        orderable: false,
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
    ],
  });

  //* /*=============== OCULTAR MODAL / RESET FORMULARIO ===============*/

  $(document).on("click", ".btnAgregarUsuario", function () {
    $("#guardarCambiosUsuario").addClass("d-none");
    $("#agregarUsuario").removeClass("d-none");
  });

  $("#usuariosMod").on("hidden.bs.modal", function (e) {
    $("#formUsuarios").trigger("reset");
  });

  //* /*=============== ACTIVAR / DESACTIVAR USUARIO ===============*/
  $(document).on("click", ".badge.statusUsuario", function () {
    let row = $(this).closest("tr");
    let rowData = $("#usuarios").DataTable().row(row).data();

    let statusUsuario = rowData.estado;
    let id = rowData.id;

    if (statusUsuario == 0) {
      statusUsuario = 1;
    } else {
      statusUsuario = 0;
    }

    let datos = new FormData();
    datos.append("accion", "actualizarStatusUsuario");
    datos.append("estado", statusUsuario);
    datos.append("id", id);

    $.ajax({
      url: "controladores/usuarios.controlador.php",
      type: "POST",
      data: datos,
      processData: false,
      contentType: false,
      success: function (response) {
        tablaUsuarios.ajax.reload(null, false);
      },
    });
  });

  //* /*=============== EDITAR USUARIO ===============*/
  $(document).on("click", ".btn-editUsuario", function (e) {
    $("#guardarCambiosUsuario").removeClass("d-none");
    $("#agregarUsuario").addClass("d-none");
    let row = $(this).closest("tr");
    let rowData = $("#usuarios").DataTable().row(row).data();

    let id = rowData.id;
    let nombre = rowData.nombre;
    let apellido = rowData.apellido;
    let usuario = rowData.usuario;
    let password = rowData.password;

    $("#idUsuario").val(id);
    $("#nombre").val(nombre);
    $("#apellido").val(apellido);
    $("#usuario").val(usuario);
    $("#password").val(password);
  });

  $(document).on("click", "#guardarCambiosUsuario", function (e) {
    e.preventDefault();
    $("#idUsuario").val();
    $("#nombre").val();
    $("#apellido").val();
    $("#usuario").val();
    $("#password").val();

    let datosUsuario = {
      id: $("#idUsuario").val(),
      nombre: $("#nombre").val(),
      apellido: $("#apellido").val(),
      usuario: $("#usuario").val(),
      password: $("#password").val(),
    };

    let datos = new FormData();
    datos.append("accion", "actualizarDatosUsuario");
    datos.append("info", JSON.stringify(datosUsuario));

    $.ajax({
      url: "controladores/usuarios.controlador.php",
      method: "POST",
      data: datos,
      contentType: false,
      processData: false,
      success: function (response) {
        tablaUsuarios.ajax.reload(null, false);
        $("#usuariosMod").modal("hide");
        $("#formUsuarios").trigger("reset");
      },
    });
  });

  //* /*=============== AGREGAR USUARIO ===============*/
  $(document).on("click", "#agregarUsuario", function () {
    if ($("#password").val() == "") {
      return;
    }

    let datosUsuario = {
      nombre: $("#nombre").val(),
      apellido: $("#apellido").val(),
      usuario: $("#usuario").val(),
      password: $("#password").val(),
    };

    let datos = new FormData();
    datos.append("accion", "nuevoUsuario");
    datos.append("usuario", JSON.stringify(datosUsuario));

    $.ajax({
      url: "controladores/usuarios.controlador.php",
      method: "POST",
      data: datos,
      contentType: false,
      processData: false,
      success: function (response) {
        tablaUsuarios.ajax.reload(null, false);
        $("#usuariosMod").modal("hide");
      },
    });
  });

  //* /*=============== ELIMINAR USUARIO ===============*/
  $(document).on("click", ".btn-deletUsuario", function (e) {
    e.preventDefault();
    let row = $(this).closest("tr");
    let rowData = $("#usuarios").DataTable().row(row).data();

    let id = rowData.id;

    let datos = new FormData();
    datos.append("accion", "eliminarUsuario");
    datos.append("id", id);

    $.ajax({
      url: "controladores/usuarios.controlador.php",
      method: "POST",
      data: datos,
      contentType: false,
      processData: false,
      success: function (response) {
        tablaUsuarios.ajax.reload(null, false);
      },
    });
  });
});
