<head>
   <title>Pedidos | &#x1F357;</title>
</head>

<section class="gap">
   <div class="container">
      <div class="heading-two">
         <h2>Menu</h2>
         <div class="line"></div>
      </div>
      <table id="menuTable" class="display" style="width: 100%;">
         <thead>
            <tr>
               <th class="text-center"></th>
            </tr>
         </thead>
         <tbody></tbody>
      </table>
   </div>
</section>



<!-- Modal -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title" id="staticBackdropLabel">Apertura de caja</h5>
         </div>
         <div class="modal-body d-flex justify-content-around flex-wrap">
            <div class="input-group">
               <div class="input-group-prepend">
                  <span class="input-group-text">Monto</span>
               </div>
               <input type="number" aria-label="nombre" class="form-control" id="montoCaja">
            </div>
            <div class="cart-btns d-flex align-items-center justify-content-center mt-2">
               <button type="button" class="btn btn-danger" id="guardarCaja">Guardar</button>
            </div>
         </div>
      </div>
   </div>
</div>