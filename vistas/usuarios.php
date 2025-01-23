<head>
    <title>Usuarios | &#x1F465;</title>
</head>


<section class="gap">
    <div class="container">
        <div class="text-center mt-4">
            <i class="fa fa-user-plus fa-3x btnAgregarUsuario" data-bs-toggle="modal" data-bs-target="#usuariosMod" aria-hidden="true"></i>
        </div>
        <div style="overflow-x:auto;overflow-y: hidden;">
            <table class="shop_table table-responsive" id="usuarios">
                <thead class="thead-light">
                    <tr>
                        <th>Id</th>
                        <th>Nombre</th>
                        <th>Apellido</th>
                        <th>Usuario</th>
                        <th>Estado</th>
                        <th>Ultimo login</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</section>

<!-- Modal usuarios-->
<div class="modal fade" id="usuariosMod" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">Usuarios</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="formUsuarios">
                    <input type="hidden" id="idUsuario">
                    <div class="form-group row search-wrap">
                        <label for="nombre" class="col-sm-2 col-form-label">Nombre</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nombre">
                        </div>
                    </div>
                    <div class="form-group row search-wrap">
                        <label for="apellido" class="col-sm-2 col-form-label">Apellido</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="apellido">
                        </div>
                    </div>
                    <div class="form-group row search-wrap">
                        <label for="usuario" class="col-sm-2 col-form-label">Usuario</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="usuario">
                        </div>
                    </div>
                    <div class="form-group row search-wrap">
                        <label for="password" class="col-sm-2 col-form-label">Contraseña</label>
                        <div class="col-sm-10">
                            <input type="password" class="form-control" id="password">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-dark" id="guardarCambiosUsuario">Guardar cambios</button>
                <button type="button" class="btn btn-dark" id="agregarUsuario">Agregar usuario</button>
            </div>
        </div>
    </div>
</div>