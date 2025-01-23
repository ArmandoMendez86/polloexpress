<head>
    <title>Retiros | &#x1F4C9;</title>
</head>

<section class="gap">
    <div class="text-center mt-4">
        <i class="fa fa-plus-circle fa-3x btnAgregarRetiro" data-bs-toggle="modal" data-bs-target="#retiros" aria-hidden="true"></i>
    </div>
    <div class="container">

        <div style="overflow-x:auto;overflow-y: hidden;">
            <table class="shop_table table-responsive retiros">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Monto</th>
                        <th>Fecha</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                    <tr>
                        <th></th>

                        <th style="border:3px solid #ffd40d !important;border-radius:30px !important;"></th>
                    </tr>
                </tfoot>
            </table>
        </div>

    </div>
</section>


<!-- Modal gastos-->
<div class="modal fade" id="retiros" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="productosLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Retiro</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formRetiros" onsubmit="return false">
                    <input type="hidden" id="idRetiro">
                    <div class="form-group d-flex">
                        <label for="monto" class="col-sm-4 col-form-label">Monto</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="monto">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-dark" id="guardarCambiosRetiro">Guardar cambios</button>
                <button type="button" class="btn btn-dark" id="agregarRetiro">Agregar retiro</button>
            </div>
        </div>
    </div>
</div>