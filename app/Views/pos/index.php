<?php
    use App\Controllers\CashReconciliationController;
    
    $cashReconciliationController = new CashReconciliationController();
    $cashReconciliation = json_decode($cashReconciliationController->activeCashReconciliation());

    $title = "Punto de Venta";
    $section = "Punto de Venta";

    require_once __DIR__.'/../layout/title.php';
    require_once __DIR__.'/modal_shifts.php';
    require_once __DIR__.'/modal_end_shift.php';
    require_once __DIR__.'/modal_clients.php';
    require_once __DIR__.'/modal_sales.php';
    require_once __DIR__.'/modal_products.php';
?>

<div class="flex flex-row gap-[5px] -mt-3 mb-2" id="sector-top-buttons">
    <?php if(!isset($cashReconciliation->data->id) || $cashReconciliation->data->id == '') { ?>
        <button type="button"
                id="btn-shift-modal"
                class="group text-[13px] border-primary border-1 font-semibold text-primary btn-outlined h-[34px] sm:px-[20px] px-[15px] rounded-6 flex items-center gap-[5px] leading-[22px] hover:text-white hover:bg-primary transition duration-300"
                data-te-ripple-init=""
                data-te-ripple-color="light">
            Iniciar Corte
        </button>
    <?php } ?>
    <button type="button"
            id="btn-sales-modal"
            class="group text-[13px] border-primary border-1 font-semibold text-primary btn-outlined h-[34px] sm:px-[20px] px-[15px] rounded-6 flex items-center gap-[5px] leading-[22px] hover:text-white hover:bg-primary transition duration-300"
            data-te-ripple-init=""
            data-te-ripple-color="light">
        Buscar Pendientes
    </button>
    <button type="button"
            id="btn-products-modal"
            class="group text-[13px] border-warning border-1 font-semibold text-warning btn-outlined h-[34px] sm:px-[20px] px-[15px] rounded-6 flex items-center gap-[5px] leading-[22px] hover:text-white hover:bg-warning transition duration-300"
            data-te-ripple-init=""
            data-te-ripple-color="light">
        Agregar Producto
    </button>
    <button type="button"
            id="btn-empty-cart"
            class="group text-[13px] border-danger border-1 font-semibold text-danger btn-outlined h-[34px] sm:px-[20px] px-[15px] rounded-6 flex items-center gap-[5px] leading-[22px] hover:text-white hover:bg-danger transition duration-300"
            data-te-ripple-init=""
            data-te-ripple-color="light">
        Vaciar
    </button>
    <?php if(isset($cashReconciliation->data->id) && $cashReconciliation->data->id != '') { ?>
        <button type="button"
                id="btn-end-shift-modal"
                class="group text-[13px] border-primary border-1 font-semibold text-primary btn-outlined h-[34px] sm:px-[20px] px-[15px] rounded-6 flex items-center gap-[5px] leading-[22px] hover:text-white hover:bg-primary transition duration-300"
                data-te-ripple-init=""
                data-te-ripple-color="light">
            Cerrar Corte
        </button>
    <?php } ?>
</div>

<div class="bg-white dark:bg-box-dark rounded-10 px-[30px] pt-0 minh-70 pb-[30px]">
    <div class="sm:grid sm:grid-cols-12 max-sm:flex max-sm:flex-col gap-[15px]">
        <div class="col-span-12">
            <div class="flex flex-row">
                <label class="text-body font-medium dark:text-subtitle-dark text-[15px] w-full relative mt-[20px] block">Corte de Caja Abierto por: <span class="font-normal"><?= $cashReconciliation->data->opened_by_name ?? ''?></span></label>
                <label class="text-body font-medium dark:text-subtitle-dark text-[15px] w-full relative mt-[20px] block">Abierto: <span class="font-normal"><?= $cashReconciliation->data->opened_date ?? ''?></span></label>
            </div>
        </div>
        <div class="col-span-12 md:col-span-6">
            <div class="flex pb-4 md:flex-row">
                <div class="flex items-center flex-1">
                    <div class="w-full rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[15px] py-[12px] min-h-[50px] focus:ring-primary focus:border-primary gap-[12px]  flex items-center">
                        <span class="inline-flex items-center text-sm text-light dark:text-subtitle-dark me-[8px]">
                            <i class="uil uil-user text-[16px]"></i>
                        </span>
                        <input type="text" id="field-cliente" name="cliente" class="outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full bg-transparent" placeholder="Cliente" value="" readonly>
                    </div>
                </div>
                <button type="button" id="btn-clients-modal" class="group text-[13px] bg-primary border-primary border-1 font-semibold text-white btn-outlined h-[50px] sm:px-[20px] px-[15px] rounded-6 flex items-center gap-[5px] leading-[22px] transition duration-300" data-te-ripple-init="" data-te-ripple-color="light">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-search" viewBox="0 0 16 16">
                        <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001q.044.06.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1 1 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-12 sm:gap-[25px] gap-y-[25px]">
        <div class="col-span-12 lg:col-span-8">
            <div class="maxh-65 pb-[15px] scrollbar overflow-y-auto">
                <table id="table-cart" class="min-w-full text-sm font-light text-start whitespace-nowrap product-container">
                    <thead>
                        <tr>
                            <th class="sticky top-0 capitalize bg-regularBG dark:bg-box-dark-up px-[25px] py-4 text-start text-light dark:text-title-dark text-[14px] font-medium border-none before:hidden rounded-s-[4px]">
                            Producto</th>
                            <th class="sticky top-0 capitalize bg-regularBG dark:bg-box-dark-up px-4 py-4 text-light dark:text-title-dark text-[14px] font-medium border-none before:hidden">
                            Precio</th>
                            <th class="sticky top-0 capitalize bg-regularBG dark:bg-box-dark-up px-4 py-4 text-light dark:text-title-dark text-[14px] font-medium border-none before:hidden">
                            Cantidad</th>
                            <th class="sticky top-0 capitalize bg-regularBG dark:bg-box-dark-up px-4 py-4 text-light dark:text-title-dark text-[14px] font-medium border-none before:hidden">
                            Total</th>
                            <th class="sticky top-0 capitalize bg-regularBG dark:bg-box-dark-up px-4 py-4 text-light dark:text-title-dark text-[14px] font-medium border-none before:hidden">
                            Pagado</th>
                            <th class="sticky top-0 capitalize bg-regularBG dark:bg-box-dark-up px-4 py-4 text-light dark:text-title-dark text-[14px] font-medium border-none before:hidden">
                            Adeudo</th>
                            <th class="sticky top-0 capitalize bg-regularBG dark:bg-box-dark-up px-[25px] py-2.5 text-end text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden rounded-e-[4px]">
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-box-dark">
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col-span-12 lg:col-span-4">
            <div class="bg-regularBG dark:bg-box-dark-up sm:p-[25px] p-[15px] rounded-10">
                <h1 class="mb-6 text-xl font-semibold text-dark dark:text-title-dark">Detalles de Venta</h1>
                <div class="bg-white dark:bg-box-dark p-[25px] shadow-[0_10px_30px_rgba(10,10,10,0.06)] rounded-10">
                    <ul class="mb-0">
                        <li class="flex items-center justify-between mb-[18px]"><span class="font-medium text-body dark:text-subtitle-dark">Subtotal :</span><span id="field-cart-subtotal" class="font-medium text-dark dark:text-title-dark">$0.00</span></li>
                        <li class="flex items-center justify-between mb-[18px]"><span class="font-medium text-body dark:text-subtitle-dark">Impuestos :</span><span id="field-cart-taxes" class="font-medium text-dark dark:text-title-dark">$0.00</span></li>
                        <li class="flex items-center justify-between mb-[18px]"><span class="font-medium text-body dark:text-subtitle-dark">Descuento :</span><span id="field-cart-discount" class="font-medium text-dark dark:text-title-dark">$0.00</span></li>
                    </ul>
                    <label for="field-coupon" class="text-body dark:text-subtitle-dark text-[15px] w-full relative mt-[20px] block">Código de Promoción</label>
                    <div class="flex sm:flex-col flex-row items-center gap-[15px] mt-[15px] mb-[35px] w-full">
                        <input type="text"
                            id="field-coupon"
                            class="w-full sm:flex-1 sm:min-w-0 bg-regularBG rounded-6 border-regular border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] h-[44px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary"
                            placeholder="Código"
                            autocomplete="off">
                        <button type="button"
                            class="w-full sm:w-auto sm:flex-none group text-[14px] border-danger border-1 font-semibold text-danger btn-outlined h-[44px] sm:px-[20px] px-[15px] rounded-6 flex items-center gap-[5px] leading-[22px] hover:text-white hover:bg-danger transition duration-300"
                            data-te-ripple-init=""
                            data-te-ripple-color="light">
                            Aplicar
                        </button>
                    </div>
                    <h5 class="inline-flex items-center justify-between w-full mb-[15px]"><span class="text-base font-semibold text-dark dark:text-title-dark">Total : </span><span id="field-cart-total"  class="text-lg font-semibold text-dark">$0.00</span></h5>
                    <fieldset id="fs-metodo-pago" class="col-span-12 border border-gray-300 rounded-lg px-6 pt-2 pb-4 bg-white shadow-sm mb-[15px]">
                        <label class="text-body text-dark dark:text-subtitle-dark text-[15px] relative block w-full">Selecciona el método de pago: </label>
                        <legend class="text-[16px] font-semibold text-gray-700 px-2">
                            Método de Pago
                        </legend>
                        <div class="flex flex-col sm:flex-row sm:items-center gap-[2px] w-full">
                            <div class="w-full sm:flex-1 sm:min-w-0 flex flex-row items-center gap-[15px]">
                                <button type="button"
                                    data-metodo="efectivo"
                                    id="btn-payment-method-efectivo"
                                    class="payment-method flex items-center text-[22px] text-primary dark:text-subtitle-dark min-h-[40px]">
                                    <i class="uil uil-money-bill text-[22px]"></i>
                                </button>
                                <button type="button"
                                    data-metodo="t-debito"
                                    id="btn-payment-method-t-debito"
                                    class="payment-method flex items-center text-[22px] text-[#a0a0a0] dark:text-subtitle-dark min-h-[40px]">
                                    <i class="uil uil-credit-card text-[22px]"></i>
                                </button>
                                <button type="button"
                                    data-metodo="t-credito"
                                    id="btn-payment-method-t-credito"
                                    class="payment-method flex items-center text-[22px] text-[#a0a0a0] dark:text-subtitle-dark min-h-[40px]">
                                    <i class="uil uil-credit-card text-[22px]"></i>
                                </button>
                                <button type="button"
                                    data-metodo="transferencia"
                                    id="btn-payment-method-transferencia"
                                    class="payment-method flex items-center text-[22px] text-[#a0a0a0] dark:text-subtitle-dark min-h-[40px]">
                                    <i class="uil uil-globe text-[22px]"></i>
                                </button>
                            </div>
                            <label id="label-payment-method" class="w-full sm:w-auto sm:flex-none font-semibold sm:text-right sm:mt-[8px] capitalize">Efectivo</label>
                        </div>
                        <div class="sector-payment-reference flex flex-col sm:flex-row gap-[25px] sm:gap-[5px] hidden">
                            <label class="w-full sm:w-auto sm:flex-none text-body text-dark dark:text-subtitle-dark text-[15px] mt-[14px] relative">Referencia: </label>
                            <input type="text"
                                id="field-payment-reference"
                                class="w-full sm:flex-1 sm:min-w-0 bg-regularBG rounded-6 border-regular border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] h-[44px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary"
                                placeholder="Referencia"
                                autocomplete="off">
                        </div>
                    </fieldset>
                    <h5 class="inline-flex items-center justify-between w-full mb-[15px]"><span class="text-base font-semibold text-dark dark:text-title-dark">Monto a Pagar : </span><span class="text-xl font-semibold text-primary">$<span id="field-cart-pay-amount" contenteditable="true">0.00</span></span></h5>
                    <button 
                        type="button"
                        id="btn-register-payment"
                        class="group w-full text-[16px] border-normal font-semibold text-white dark:text-title-dark btn-outlined h-[44px] dark:border-box-dark-up sm:px-[20px] px-[15px] rounded-6 flex items-center justify-center gap-[5px] leading-[22px] hover:text-white hover:bg-primary-hbr bg-primary transition duration-300 capitalize"
                        data-te-ripple-init=""
                        data-te-ripple-color="light">
                        Pagar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= asset('js/pos/index.js'); ?>"></script>

<script>
    var currentLink = '<?= base_url('pos'); ?>';
</script>