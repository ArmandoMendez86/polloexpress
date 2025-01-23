$(document).ready(function () {
    let tablaRetiros = $(".retiros").DataTable({
        ajax: {
            url: "controladores/retiros.controlador.php",
            type: "GET",
            data: function (d) {
                d.accion = "obtenerRetiros";
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
        order: [[2, "desc"]],
        columns: [
            { data: "id", visible: false },
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
                defaultContent:
                    '<div class="d-flex justify-content-center align-items-center"><button type="button" class="btn btn-editRetiro" data-bs-toggle="modal" data-bs-target="#retiros"><i class="fa fa-pencil text-secondary" aria-hidden="true" style="font-size:1.5rem;"></i></button><button type="button" class="btn btn-deletRetiro"><i class="fa fa-times text-secondary" aria-hidden="true" style="font-size:1.5rem;"></i></button></div>',
                orderable: false,
            },
        ],

        columnDefs: [
            { targets: 0, className: "align-middle" },
            { targets: 1, className: "align-middle text-center" },
            { targets: 2, className: "align-middle text-center" },
            { targets: 3, className: "align-middle text-center" },
        
        ],

        footerCallback: function (row, data, start, end, display) {
            let api = this.api();
            let total = api
                .column(1, {
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

            $(api.column(1).footer()).html(
                "<p style='width:7rem;margin:0 auto;font-size:1.3rem;color:red;'>" +
                formato +
                "</p>"
            );
        },
    });



    function capitalizeFirstLetter(string) {
        return string.charAt(0).toUpperCase() + string.slice(1);
    }

    $(document).on("click", ".btnAgregarRetiro", function () {
        $("#guardarCambiosRetiro").addClass("d-none");
        $("#agregarRetiro").removeClass("d-none");
    });

    $("#retiros").on("hidden.bs.modal", function (e) {
        $("#formRetiros").trigger("reset");
    });

    $(document).on("click", ".btn-editRetiro", function (e) {
        $("#guardarCambiosRetiro").removeClass("d-none");
        $("#agregarRetiro").addClass("d-none");

        let row = $(this).closest("tr");
        let rowData = $(".retiros").DataTable().row(row).data();

        let id = rowData.id;
        let monto = rowData.monto;

        $("#idRetiro").val(id);
        $("#monto").val(monto);

    });

    $(document).on("click", "#guardarCambiosRetiro", function (e) {
        e.preventDefault();
        let fecha = moment().format("YYYY-MM-DD H:mm:ss");
        let datos = new FormData();
        datos.append("accion", "editarRetiro");
        datos.append("id", $("#idRetiro").val());
        datos.append("monto", $("#monto").val());
        datos.append("fecha", fecha);

        $.ajax({
            url: "controladores/retiros.controlador.php",
            type: "POST",
            data: datos,
            processData: false,
            contentType: false,
            success: function (respuesta) {
                tablaRetiros.ajax.reload(null, false);
                $("#retiros").modal("hide");
                alertify.success("Cambios guardados!");
            },
            error: function (error) {
                console.error("Error en la solicitud AJAX:", error);
            },
        });
    });

    $(document).on("click", "#agregarRetiro", function () {
        let monto = $("#monto").val();
        let fecha = moment().format("YYYY-MM-DD H:mm:ss");
        let datos = new FormData();
        datos.append("accion", "agregarRetiro");
        datos.append("monto", monto);
        datos.append("fecha", fecha);

        $.ajax({
            url: "controladores/retiros.controlador.php",
            method: "POST",
            data: datos,
            contentType: false,
            processData: false,
            success: function (response) {
                tablaRetiros.ajax.reload(null, false);
                $("#retiros").modal("hide");
                $("#formRetiros").trigger("reset");
                alertify.success("Retiro registrado!");
            },
        });
    });

    $(document).on("click", ".btn-deletRetiro", function (e) {
        e.preventDefault();
        let row = $(this).closest("tr");
        let rowData = $(".retiros").DataTable().row(row).data();
        let id = rowData.id;

        let datos = new FormData();
        datos.append("accion", "eliminarRetiro");
        datos.append("id", id);

        $.ajax({
            url: "controladores/retiros.controlador.php",
            method: "POST",
            data: datos,
            contentType: false,
            processData: false,
            success: function (response) {
                tablaRetiros.ajax.reload(null, false);
                alertify.error("Retiro eliminado!");
            },
        });
    });
});
