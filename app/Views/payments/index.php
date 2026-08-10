<?php
    $title = "Pagos";
    $section = "Pagos";

    require_once __DIR__.'/../layout/title.php';
    require_once __DIR__.'/receipt_modal.php';
?>

<div class="sm:grid sm:grid-cols-12 max-sm:flex max-sm:flex-col gap-[25px]">
    <div class="col-span-12 lg:col-span-8">
        <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative h-full">
            <div class="px-[25px] text-dark dark:text-title-dark font-medium text-[17px] flex flex-wrap items-center justify-between max-sm:flex-col max-sm:h-auto max-sm:mb-[15px]">
                <h2 class="mb-0 inline-flex items-center py-[16px] max-sm:pb-[5px] overflow-hidden whitespace-nowrap text-ellipsis text-[18px] font-semibold text-dark dark:text-title-dark  capitalize">
                    Pagos
                </h2>
            </div>
            
            <div class="p-[25px] pt-0">
                <div class="scrollbar overflow-y-auto" style="max-height: 55vh">
                    <div data-te-tab-active class="hidden opacity-100 transition-opacity duration-150 ease-linear data-[te-tab-active]:block">
                        <table class="min-w-full text-sm font-light text-left whitespace-nowrap" id="table-payments">
                            <thead>
                                <tr>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-start text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden rounded-s-[4px]">
                                        FOLIO</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        CLIENTE</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        MÉTODO DE PAGO</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        MONTO DE PAGO</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        REGISTRO</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        ESTATUS</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        FECHA DE PAGO</th>
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
                    <div class=" px-2 py-1 text-[10px] font-medium text-primary uppercase rounded-md bg-primary/20" id="field-payment-cash-reconciliation">
                        ...
                    </div>
                    <div class=" px-2 py-1 text-[10px] font-medium text-warning uppercase rounded-md bg-warning/20" id="field-payment-status">
                        ...
                    </div>
                </div>
                <div class="col-span-12 2xl:col-span-3 lg:col-span-4">
                    <h4 class="text-[14px] text-light dark:text-subtitle-dark mb-[8px] font-normal">Folio:</h4>
                    <div class="flex items-center mb-[15px] gap-[15px]">
                        <span class="text-[14px] font-medium text-theme-gray dark:text-subtitle-dark capitalize" id="field-payment-folio">...</span>
                    </div>
                </div>
            </div>

            <div class="col-span-12 flex flex-row-reverse items-center gap-[5px] mt-[14px]">
                <button type="button"
                        id="btn-payment-cancel"
                        class="px-[30px] h-[34px] text-white bg-primary border-regular hover:bg-primary-hbr disabled:text-neutral-600 disabled:bg-lightgray disabled:cursor-not-allowed font-medium rounded-4 text-sm w-full sm:w-auto text-center inline-flex items-center justify-center capitalize transition-all duration-300 ease-linear"
                        data-te-ripple-init=""
                        data-te-ripple-color="light"
                        disabled>
                        Cancelar Pago
                </button>
            </div>
        </div>
    </div>

    <div class="col-span-12 2xl:col-span-5">
        <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative h-full">
            <div class="px-[25px] text-dark dark:text-title-dark font-medium text-[17px] flex flex-wrap items-center justify-between max-sm:flex-col max-sm:h-auto max-sm:mb-[15px]">
                <h2 class="mb-0 inline-flex items-center py-[16px] max-sm:pb-[5px] overflow-hidden whitespace-nowrap text-ellipsis text-[18px] font-semibold text-dark dark:text-title-dark  capitalize">
                    Ventas
                </h2>
            </div>
            
            <div class="p-[25px] pt-0">
                <div class="scrollbar overflow-y-auto" style="max-height: 65vh">
                    <div data-te-tab-active class="hidden opacity-100 transition-opacity duration-150 ease-linear data-[te-tab-active]:block">
                        <table class="min-w-full text-sm font-light text-left whitespace-nowrap" id="table-sales">
                            <thead>
                                <tr>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-start text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden rounded-s-[4px]">
                                        FOLIO</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        CLIENTE</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        PACIENTE</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        ADEUDO</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        PAGO</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        FECHA VENTA</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden"></th>
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

    <div class="col-span-12 2xl:col-span-7">
        <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative h-full">
            <div class="px-[25px] text-dark dark:text-title-dark font-medium text-[17px] flex flex-wrap items-center justify-between max-sm:flex-col max-sm:h-auto max-sm:mb-[15px]">
                <h2 class="mb-0 inline-flex items-center py-[16px] max-sm:pb-[5px] overflow-hidden whitespace-nowrap text-ellipsis text-[18px] font-semibold text-dark dark:text-title-dark  capitalize">
                    Detalle de Pago
                </h2>
            </div>
            
            <div class="p-[25px] pt-0">
                <div class="scrollbar overflow-y-auto" style="max-height: 65vh">
                    <div data-te-tab-active class="hidden opacity-100 transition-opacity duration-150 ease-linear data-[te-tab-active]:block">
                        <table class="min-w-full text-sm font-light text-left whitespace-nowrap" id="table-payment-details">
                            <thead>
                                <tr>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-start text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden rounded-s-[4px]">
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

<script src="<?= asset('js/payments/index.js'); ?>"></script>

<script>
    var currentLink = '<?= base_url('payments'); ?>';
</script>