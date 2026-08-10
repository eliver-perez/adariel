<?php
    $title = "Ventas";
    $section = "Ventas";

    require_once __DIR__.'/../layout/title.php';
    require_once __DIR__.'/receipt_modal.php';
?>

<div class="sm:grid sm:grid-cols-12 max-sm:flex max-sm:flex-col gap-[25px]">
    <div class="col-span-12">
        <div class="flex flex-wrap items-center justify-between overflow-hidden bg-white rounded-10 dark:bg-box-dark-down">
            <div class="mb-0 flex-[0_0_auto] p-[15px]">
                <div class="relative text-[15px] text-body dark:text-subtitle-dark leading-6 p-[5px]">
                    <div class="flex gap-[25px] justify-between items-start">
                        <div class="bg-warning/10 flex min-w-[70px] h-[70px] items-center justify-center rounded-[14px] text-warning">
                            <div class="flex items-center text-warning text-[30px]">
                            <i class="uil uil-shopping-cart-alt"></i>
                            </div>
                        </div>
                        <div class="flex gap-[10px]">
                            <div>
                            <h4 class="mb-0 text-3xl max-lg:text-[26px] max-sm:text-2xl font-semibold text-dark dark:text-title-dark">
                                <span class="flex items-center countCategories sm:min-w-[75px]" data-number="0">
                                    <span class="countNumber">0</span>
                                </span>
                            </h4>
                            <span class="font-normal text-light dark:text-subtitle-dark text-15">Cantidad Ventas</span>
                            </div>
                            <div class="mt-[5px]">

                            <span class="flex font-medium gap-[2px] items-center text-sm text-success">
                                <i class="uil uil-arrow-up text-[18px]"></i> 0% </span>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-0 flex-[0_0_auto]">
                <div class="relative text-[15px] text-body dark:text-subtitle-dark leading-6 p-[5px]">
                    <div class="flex gap-[25px] justify-between items-start">
                        <div class="bg-info/10 flex min-w-[70px] h-[70px] items-center justify-center rounded-[14px] text-info">
                            <div class="flex items-center text-info text-[30px]">
                            <i class="uil uil-arrow-growth"></i>
                            </div>
                        </div>
                        <div class="flex gap-[10px]">
                            <div>
                            <h4 class="mb-0 text-3xl max-lg:text-[26px] max-sm:text-2xl font-semibold text-dark dark:text-title-dark">
                                <span class="flex items-center countCategories sm:min-w-[75px]" data-number="0.00">

                                    <span>$</span>

                                    <span class="countNumber">0.00</span>

                                </span>
                            </h4>
                            <span class="font-normal text-light dark:text-subtitle-dark text-15">Total Venta</span>
                            </div>
                            <div class="mt-[5px]">

                            <span class="flex font-medium gap-[2px] items-center text-sm text-success">
                                <i class="uil uil-arrow-up text-[18px]"></i> 0% </span>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-0 flex-[0_0_auto]">
                <div class="relative text-[15px] text-body dark:text-subtitle-dark leading-6 p-[5px]">
                    <div class="flex gap-[25px] justify-between items-start">
                        <div class="bg-primary/10 flex min-w-[70px] h-[70px] items-center justify-center rounded-[14px] text-primary">
                            <div class="flex items-center text-primary text-[30px]">
                            <i class="uil uil-money-bill"></i>
                            </div>
                        </div>
                        <div class="flex gap-[10px]">
                            <div>
                            <h4 class="mb-0 text-3xl max-lg:text-[26px] max-sm:text-2xl font-semibold text-dark dark:text-title-dark">
                                <span class="flex items-center countCategories sm:min-w-[75px]" data-number="0.00">

                                    <span>$</span>

                                    <span class="countNumber">0.00</span>


                                </span>
                            </h4>
                            <span class="font-normal text-light dark:text-subtitle-dark text-15">Pagado</span>
                            </div>
                            <div class="mt-[5px]">

                            <span class="flex font-medium gap-[2px] items-center text-sm text-success">
                                <i class="uil uil-arrow-up text-[18px]"></i> 0% </span>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mb-0 flex-[0_0_auto]">
                <div class="relative text-[15px] text-body dark:text-subtitle-dark leading-6 p-[5px]">
                    <div class="flex gap-[25px] justify-between items-start">
                        <div class="bg-danger/10 flex min-w-[70px] h-[70px] items-center justify-center rounded-[14px] text-danger">
                            <div class="flex items-center text-danger text-[30px]">
                            <i class="uil uil-usd-circle"></i>
                            </div>
                        </div>
                        <div class="flex gap-[10px]">
                            <div>
                            <h4 class="mb-0 text-3xl max-lg:text-[26px] max-sm:text-2xl font-semibold text-dark dark:text-title-dark">
                                <span class="flex items-center countCategories sm:min-w-[75px]" data-number="0.00">

                                    <span>$</span>

                                    <span class="countNumber">0.00</span>

                                </span>
                            </h4>
                            <span class="font-normal text-light dark:text-subtitle-dark text-15">Adeudos</span>
                            </div>
                            <div class="mt-[5px]">

                            <span class="flex font-medium gap-[2px] items-center text-sm text-danger">
                                <i class="uil uil-arrow-down text-[18px]"></i> 0% </span>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



    <div class="col-span-12 lg:col-span-8">
        <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative h-full">
            <div class="px-[25px] text-dark dark:text-title-dark font-medium text-[17px] flex flex-wrap items-center justify-between max-sm:flex-col max-sm:h-auto max-sm:mb-[15px]">
                <h2 class="mb-0 inline-flex items-center py-[16px] max-sm:pb-[5px] overflow-hidden whitespace-nowrap text-ellipsis text-[18px] font-semibold text-dark dark:text-title-dark  capitalize">
                    Ventas
                </h2>
                <div class="inline-flex gap-[5px]">
                    <div class="w-full rounded-4 border-normal border-1 text-[14px] dark:bg-box-dark-up dark:border-box-dark-up px-[15px] py-[6px] min-h-[35px] focus:ring-primary focus:border-primary gap-[6px] flex items-center">
                        <span class="inline-flex items-center text-sm text-light dark:text-subtitle-dark me-[8px]">
                        <i class="uil uil-search text-[16px]"></i>
                        </span>
                        <input type="text" id="field-filter-sales-search" name="sales_search" class="outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full bg-transparent" placeholder="Busqueda..." value="">
                    </div>
                    <select id="select-filter-sales-status"
                            name="filter_sales_status"
                            data-te-select-init data-te-select-filter="true"
                            data-te-select-init data-te-class-select-input="py-[5px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto">
                        <option value="0">Mostrar Todo</option>
                    </select>
                </div>
            </div>
            
            <div class="p-[25px] pt-0">
                <div class="scrollbar overflow-y-auto" style="max-height: 55vh">
                    <div data-te-tab-active class="hidden opacity-100 transition-opacity duration-150 ease-linear data-[te-tab-active]:block">
                        <table class="min-w-full text-sm font-light text-left whitespace-nowrap" id="table-sales">
                            <thead>
                                <tr>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-start text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden rounded-s-[4px]">
                                        FOLIO</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        CLIENTE</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        MONTO VENTA</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        DESCUENTO</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        PAGADO</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        ADEUDO</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        REGISTRO</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        ESTATUS</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        FECHA DE VENTA</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">

                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-box-dark">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-span-12 lg:col-span-4">
        <div class="bg-white dark:bg-box-dark rounded-10 pt-[25px] px-[25px] pb-[30px] shadow-[0_5px_20px_rgba(173,181,217,0.05)] dark:shadow-none">
            <h4 class="text-[20px] text-dark dark:text-subtitle-dark font-medium mb-[25px]">Detalles</h4>
            <div class="grid grid-cols-12 mb-[20px]">
                <div class="col-span-12 flex items-center flex-wrap justify-between  gap-y-[5px] mb-[10px]">
                    <div class=" px-2 py-1 text-[10px] font-medium text-primary uppercase rounded-md bg-primary/20" id="field-sale-client-tag">
                        ...
                    </div>
                    <div class=" px-2 py-1 text-[10px] font-medium text-warning uppercase rounded-md bg-warning/20" id="field-sale-status">
                        ...
                    </div>
                </div>
                <div class="col-span-12 2xl:col-span-3 lg:col-span-5">
                    <h4 class="text-[14px] text-light dark:text-subtitle-dark mb-[8px] font-normal">Folio:</h4>
                    <div class="flex items-center mb-[15px] gap-[15px]">
                        <span class="text-[14px] font-medium text-theme-gray dark:text-subtitle-dark capitalize" id="field-sale-folio">...</span>
                    </div>
                </div>
                <div class="col-span-12 2xl:col-span-9 lg:col-span-7">
                    <h4 class="text-[14px] text-light dark:text-subtitle-dark mb-[8px] font-normal">Cliente:</h4>
                    <div class="flex items-center mb-[15px] gap-[15px]">
                        <span class="text-[14px] font-medium text-theme-gray dark:text-subtitle-dark capitalize" id="field-sale-client">...</span>
                    </div>
                </div>
                <div class="col-span-12 2xl:col-span-9 lg:col-span-12">
                    <h4 class="text-[14px] text-light dark:text-subtitle-dark mb-[8px] font-normal">Paciente:</h4>
                    <div class="flex items-center mb-[15px] gap-[15px]">
                        <span class="text-[14px] font-medium text-theme-gray dark:text-subtitle-dark capitalize" id="field-sale-patient">...</span>
                    </div>
                </div>
                <div class="col-span-12 2xl:col-span-3 lg:col-span-7">
                    <h4 class="text-[14px] text-light dark:text-subtitle-dark mb-[8px] font-normal">Fecha:</h4>
                    <div class="flex items-center mb-[15px] gap-[15px]">
                        <span class="text-[14px] font-medium text-theme-gray dark:text-subtitle-dark capitalize" id="field-sale-date">...</span>
                    </div>
                </div>
                <div class="col-span-12 2xl:col-span-4 lg:col-span-5">
                    <h4 class="text-[14px] text-light dark:text-subtitle-dark mb-[8px] font-normal">Subtotal:</h4>
                    <div class="flex items-center mb-[15px] gap-[15px]">
                        <span class="text-[14px] font-medium text-theme-gray dark:text-subtitle-dark capitalize" id="field-sale-subtotal">...</span>
                    </div>
                </div>
                <div class="col-span-12 2xl:col-span-4 lg:col-span-4">
                    <h4 class="text-[14px] text-light dark:text-subtitle-dark mb-[8px] font-normal">Impuestos:</h4>
                    <div class="flex items-center mb-[15px] gap-[15px]">
                        <span class="text-[14px] font-medium text-theme-gray dark:text-subtitle-dark capitalize" id="field-sale-taxes">...</span>
                    </div>
                </div>
                <div class="col-span-12 2xl:col-span-4 lg:col-span-4">
                    <h4 class="text-[14px] text-light dark:text-subtitle-dark mb-[8px] font-normal">Total:</h4>
                    <div class="flex items-center mb-[15px] gap-[15px]">
                        <span class="text-[14px] font-medium text-theme-gray dark:text-subtitle-dark capitalize" id="field-sale-total">...</span>
                    </div>
                </div>
                <div class="col-span-12 2xl:col-span-4 lg:col-span-4">
                    <h4 class="text-[14px] text-light dark:text-subtitle-dark mb-[8px] font-normal">Descuento:</h4>
                    <div class="flex items-center mb-[15px] gap-[15px]">
                        <span class="text-[14px] font-medium text-theme-gray dark:text-subtitle-dark capitalize" id="field-sale-discount">...</span>
                    </div>
                </div>
                <div class="col-span-12 2xl:col-span-4 lg:col-span-6">
                    <h4 class="text-[14px] text-light dark:text-subtitle-dark mb-[8px] font-normal">Pagado:</h4>
                    <div class="flex items-center mb-[15px] gap-[15px]">
                        <span class="text-[14px] font-medium text-theme-gray dark:text-subtitle-dark capitalize" id="field-sale-paid">...</span>
                    </div>
                </div>
                <div class="col-span-12 2xl:col-span-4 lg:col-span-6">
                    <h4 class="text-[14px] text-light dark:text-subtitle-dark mb-[8px] font-normal">Adeudo:</h4>
                    <div class="flex items-center mb-[15px] gap-[15px]">
                        <span class="text-[16px] font-bold text-theme-gray dark:text-subtitle-dark capitalize" id="field-sale-balance-due">...</span>
                    </div>
                </div>
                <div class="col-span-12 2xl:col-span-12 lg:col-span-12">
                    <h4 class="text-[14px] text-light dark:text-subtitle-dark mb-[8px] font-normal">Observaciones:</h4>
                    <div class="flex items-center mb-[15px] gap-[15px]">
                        <span class="text-[14px] font-medium text-theme-gray dark:text-subtitle-dark capitalize" id="field-sale-observations">...</span>
                    </div>
                </div>
            </div>

            <div class="col-span-12 flex flex-row-reverse items-center gap-[5px] mt-[14px]">
                <button type="button"
                        id="btn-sale-cancel"
                        class="px-[30px] h-[34px] text-white bg-primary border-regular hover:bg-primary-hbr disabled:text-neutral-600 disabled:bg-lightgray disabled:cursor-not-allowed font-medium rounded-4 text-sm w-full sm:w-auto text-center inline-flex items-center justify-center capitalize transition-all duration-300 ease-linear"
                        data-te-ripple-init=""
                        data-te-ripple-color="light"
                        disabled>
                        Cancelar Venta
                </button>
            </div>
        </div>
    </div>

    <div class="col-span-12 2xl:col-span-12">
        <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative h-full">
            <div class="px-[25px] text-dark dark:text-title-dark font-medium text-[17px] flex flex-wrap items-center justify-between max-sm:flex-col max-sm:h-auto max-sm:mb-[15px]">
                <h2 class="mb-0 inline-flex items-center py-[16px] max-sm:pb-[5px] overflow-hidden whitespace-nowrap text-ellipsis text-[18px] font-semibold text-dark dark:text-title-dark  capitalize">
                    Detalle de Venta
                </h2>
            </div>
            
            <div class="p-[25px] pt-0">
                <div class="scrollbar overflow-y-auto" style="max-height: 65vh">
                    <div data-te-tab-active class="hidden opacity-100 transition-opacity duration-150 ease-linear data-[te-tab-active]:block">
                        <table class="min-w-full text-sm font-light text-left whitespace-nowrap" id="table-sale-details">
                            <thead>
                                <tr>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-start text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden rounded-s-[4px] hidden">
                                        VENTA</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        #</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        PRODUCTO</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        PU</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        TOTAL</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        ADEUDO</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        PAGO</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-box-dark">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script src="<?= asset('js/sales/index.js'); ?>"></script>

<script>
    var currentLink = '<?= base_url('sales'); ?>';
</script>