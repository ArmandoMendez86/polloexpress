$(document).ready(function () {
  $("#ingresar").click(() => {
    let datos = {
      usuario: $("#usuario").val(),
      password: $("#password").val(),
    };

    $.ajax({
      url: "controladores/usuarios.controlador.php",
      type: "POST",
      data: {
        accion: "ingresoUsuario",
        usuario: datos,
      },
      success: function (response) {
        let data = JSON.parse(response);

        if (data.status === "success") {
          window.location.href = data.redirect;
        } else {
          alert(data.message);
        }
      },
    });
  });

  $("#cerrarSesion").click(() => {
    $.ajax({
      url: "controladores/usuarios.controlador.php",
      type: "POST",
      data: {
        accion: "cerrarSesion",
      },
      success: function (response) {
        let data = JSON.parse(response);
        if (data.status === "success") {
          window.location.href = "login";
        } else {
          alert("Error al cerrar sesión");
        }
      },
    });
  });
});
