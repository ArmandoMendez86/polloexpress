<head>
    <title>Menu | &#x1F35C;</title>
</head>

<section class="gap">
    <div class="text-center mt-4">
        <i class="fa fa-plus-circle fa-3x btnAgregarProducto" data-bs-toggle="modal" data-bs-target="#productos" aria-hidden="true"></i>
    </div>
    <div class="container">
        <form class="woocommerce-cart-form">
            <div style="overflow-x:auto;overflow-y: hidden;">
                <table class="shop_table table-responsive menu">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Producto</th>
                            <th>Descripción</th>
                            <th>Precio</th>
                            <th>Identificador</th>
                            <th>Porción</th>
                            <th>Imagen</th>
                            <th>Status</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </form>
    </div>
</section>


<!-- Modal productos-->
<div class="modal fade" id="productos" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="productosLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Producto</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formProductos">
                    <input type="hidden" id="idProducto">
                    <div class="form-group d-flex">
                        <label for="producto" class="col-sm-4 col-form-label">Producto</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="cod" placeholder="Nombre del producto">
                        </div>
                    </div>
                    <div class="form-group d-flex mt-2">
                        <label for="porcion" class="col-sm-4 col-form-label">Porción</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="porcion" placeholder="Ejemp. 0.8, 0.78, 1">
                        </div>
                    </div>
                    <div class="form-group d-flex mt-2">
                        <label for="categoria" class="col-sm-4 col-form-label">Categoría</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="cat" placeholder="Ejemp. Carn, Lact">
                        </div>
                    </div>
                    <div class="form-group d-flex mt-2">
                        <label for="precio" class="col-sm-4 col-form-label">Precio</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="precio" min=0 >
                        </div>
                    </div>
                    <div class="form-group d-flex mt-2">
                        <label for="descripcion" class="col-sm-4 col-form-label">Descripción</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="des" placeholder="Descripción del producto">
                        </div>
                    </div>
                    <div class="form-group d-flex mt-2">
                        <label for="status" class="col-sm-4 col-form-label">Estatus</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="status" id="status">
                                <option value="1" style="color: green;font-weight:bold;">En stock</option>
                                <option value="0" style="color: red;font-weight:bold;">Agotado</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group d-flex mt-2">
                        <label for="img" class="col-sm-4 col-form-label">Imagen</label>
                        <div class="col-sm-8">
                            <input type="file" class="form-control" id="img" name="img">
                        </div>
                    </div>
                    <div class="text-center mt-2">
                        <img src="assets/img/market.jpg" alt="Imagen del producto" class="imagen-producto" style="width: 140px;height:100px;">
                    </div>

                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-dark" id="guardarCambiosProducto">Guardar cambios</button>
                <button type="button" class="btn btn-dark" id="agregarProducto">Agregar producto</button>
            </div>
        </div>
    </div>
</div>