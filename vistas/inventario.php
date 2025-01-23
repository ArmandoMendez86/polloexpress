<head>
    <title>Inventario | &#x1F4C8;</title>
</head>

<section class="gap">
    <div class="container">
        <div style="overflow-x:auto;overflow-y: hidden;">
            <table class="shop_table table-responsive inventario">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Identificador</th>
                        <th>Descripción</th>
                        <th>Stock</th>
                        <th>Unidad</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</section>


<!-- Modal productos-->
<div class="modal fade" id="inventario" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="productosLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Editando <span id="selectProduct"></span></h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <input type="hidden" id="idInventario">
                <div class="form-group d-flex mt-2">
                    <label for="precio" class="col-sm-4 col-form-label">Cantidad</label>
                    <div class="col-sm-8">
                        <input type="number" class="form-control" id="cantidadInventario">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-dark" id="guardarCambiosInventario">Guardar cambios</button>

            </div>
        </div>
    </div>
</div>