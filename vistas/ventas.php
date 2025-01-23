<head>
    <title>Tickets | &#x1F4C3;</title>
</head>

<section class="gap">
    <div class="container">
        <form class="woocommerce-cart-form">
            <div style="overflow-x:auto;overflow-y: hidden;">
                <table class="shop_table table-responsive" id="tabVentas">
                    <thead>
                        <tr>
                            <th class="product-name">id</th>
                            <th class="product-quantity">Cliente</th>
                            <th class="product-quantity">Atendió</th>
                            <th class="product-subtotal">Fecha</th>
                            <th class="product-quantity">Folio</th>
                            <th class="product-subtotal">Subtotal</th>
                            <th class="product-subtotal">Acción</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                    <tfoot>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th style="border:3px solid #ffd40d !important;border-radius:30px !important;"></th>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Modal -->
            <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="staticBackdropLabel">Editar orden N.° <span id="ordenEdit"></span></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body d-flex justify-content-around flex-wrap">

                            <!-- Lista de menu -->

                            <div class="delicious-menu">
                                <h5>Menu</h5>
                                <div class="line"></div>
                                <ul class="menu-items"></ul>
                            </div>


                            <!-- Carrito de compras -->
                            <div class="cart-popup show-cart p-4">
                                <ul id="lista-items-edit"></ul>

                                <div class="cart-total d-flex align-items-center justify-content-between">
                                    <span class="font-semi-bold">Total:</span>
                                    <span class="font-semi-bold precioTotalEdit">$0.00</span>
                                </div>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Nombre</span>
                                    </div>
                                    <input type="text" aria-label="nombre" class="form-control" id="nombreClienteEdit">
                                </div>
                                <div class="cart-btns d-flex align-items-center justify-content-center mt-2">
                                    <a class="font-bold theme-bg-clr text-white checkout" href="#!" id="ticketEdit" style="pointer-events: auto;">Guardar</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>



































            <!--    <div class="row mt-5">
                <div class="col-lg-4">
                    <div class="coupon-area">
                        <h3>Apply Coupon</h3>
                        <div class="coupon">
                            <input type="text" name="coupon_code" class="input-text" placeholder="Coupon Code">
                            <button type="submit" name="apply_coupon"><span>Apply coupon</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="cart_totals">
                        <h4>Cart Totals</h4>
                        <div class="shop_table-boder">
                            <table class="shop_table_responsive">
                                <tbody>
                                    <tr class="cart-subtotal">
                                        <th>Sub total:</th>
                                        <td>
                                            <span class="woocommerce-Price-amount">
                                                <bdi>
                                                    <span class="woocommerce-Price-currencySymbol">$</span>250.00
                                                </bdi>
                                            </span>
                                        </td>
                                    </tr>
                                    <tr class="Shipping">
                                        <th>Shipping:</th>
                                        <td>
                                            <span class="woocommerce-Price-amount amount">
                                                free
                                            </span>
                                        </td>
                                    </tr>
                                    <tr class="Total">
                                        <th>Total:</th>
                                        <td>
                                            <span class="woocommerce-Price-amount">
                                                <bdi>
                                                    <span>$</span>250.00
                                                </bdi>
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                        <div class="wc-proceed-to-checkout">
                            <a href="#" class="button">
                                <span>Proceed to checkout</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div> -->
        </form>
    </div>
</section>