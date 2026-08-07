<?php
    $title = "Empresas";
    $section = "Empresas";

    require_once __DIR__.'/../layout/title.php';
    require_once __DIR__.'/modal_organization.php';
?>

<div class="sm:grid sm:grid-cols-12 max-sm:flex max-sm:flex-col gap-[25px]">
    <div class="col-span-12 2xl:col-span-8">
        <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative h-full">
            <div class="px-[25px] text-dark dark:text-title-dark font-medium text-[17px] flex flex-wrap items-center justify-between max-sm:flex-col max-sm:h-auto max-sm:mb-[15px]">
                <h2 class="mb-0 inline-flex items-center py-[16px] max-sm:pb-[5px] overflow-hidden whitespace-nowrap text-ellipsis text-[18px] font-semibold text-dark dark:text-title-dark  capitalize">
                    Empresas
                </h2>
            </div>
            
            <div class="p-[25px] pt-0">
                <div class="scrollbar overflow-y-auto" style="max-height: 420px">
                    <div data-te-tab-active class="hidden opacity-100 transition-opacity duration-150 ease-linear data-[te-tab-active]:block">
                        <table class="min-w-full text-sm font-light text-left whitespace-nowrap" id="table-organizations">
                            <thead>
                                <tr>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-start text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden rounded-s-[4px]">
                                        EMPRESA</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        DOMICILIO</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        TELÉFONO</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        MÓVIL</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        E-MAIL</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        ENCARGADO</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        ACTIVA</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-box-dark">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-span-12 flex flex-row-reverse items-center gap-[5px]">
                    <button
                        type="button"
                        id="btn-new-organization"
                        class="px-[30px] h-[34px] mt-[14px] text-end text-white bg-primary border-regular hover:bg-primary-hbr disabled:text-neutral-600 disabled:bg-lightgray disabled:cursor-not-allowed font-medium rounded-4 text-sm w-full sm:w-auto capitalize transition-all duration-300 ease-linear"
                        data-te-ripple-init=""
                        data-te-ripple-color="light">
                        Registrar Empresa
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="col-span-12 2xl:col-span-4">
        <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative h-full">
            <div class="px-[25px] text-dark dark:text-title-dark font-medium text-[17px] flex flex-wrap items-center justify-between max-sm:flex-col max-sm:h-auto max-sm:mb-[15px]">
                <h2 class="mb-0 inline-flex items-center py-[16px] max-sm:pb-[5px] overflow-hidden whitespace-nowrap text-ellipsis text-[18px] font-semibold text-dark dark:text-title-dark  capitalize">
                    Detalles
                </h2>
            </div>

            <div class="sm:grid sm:grid-cols-12 max-sm:flex max-sm:flex-col px-[25px] pb-[10px] gap-[10px]">
                <div class="col-span-12 md:col-span-12 xl:col-span-12">
                    <label for="field-selected-code" class="inline-flex items-center w-[178px] mb-[2px] text-[14px] font-medium capitalize dark:text-title-dark">
                        Clave
                    </label>
                    <div class="flex flex-col flex-1 md:flex-row">
                        <input type="text"
                                id="field-selected-code"
                                class="rounded-4 border-normal border-0 text-[14px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[6px] min-h-[40px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" 
                                disabled>
                    </div>
                </div>
                <div class="col-span-12 md:col-span-12 xl:col-span-12">
                    <label for="field-selected-organization" class="inline-flex items-center w-[178px] mb-[2px] text-[14px] font-medium capitalize dark:text-title-dark">
                        Empresa
                    </label>
                    <div class="flex flex-col flex-1 md:flex-row">
                        <input type="text"
                                id="field-selected-organization"
                                class="rounded-4 border-normal border-0 text-[14px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[6px] min-h-[40px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                disabled>
                    </div>
                </div>
                <div class="col-span-12 md:col-span-12 xl:col-span-12">
                    <label for="field-selected-address" class="inline-flex items-center w-[178px] mb-[2px] text-[14px] font-medium capitalize dark:text-title-dark">
                        Domicilio
                    </label>
                    <div class="flex flex-col flex-1 md:flex-row">
                        <input type="text"
                                id="field-selected-address"
                                class="rounded-4 border-normal border-0 text-[14px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[6px] min-h-[40px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                disabled>
                    </div>
                </div>
                <div class="col-span-12 md:col-span-6 xl:col-span-6">
                    <label for="field-selected-phone" class="inline-flex items-center w-[178px] mb-[2px] text-[14px] font-medium capitalize dark:text-title-dark">
                        Teléfono
                    </label>
                    <div class="flex flex-col flex-1 md:flex-row">
                        <input type="text"
                                id="field-selected-phone"
                                class="rounded-4 border-normal border-0 text-[14px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[6px] min-h-[40px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                disabled>
                    </div>
                </div>
                <div class="col-span-12 md:col-span-6 xl:col-span-6">
                    <label for="field-selected-mobile" class="inline-flex items-center w-[178px] mb-[2px] text-[14px] font-medium capitalize dark:text-title-dark">
                        Teléfono Móvil
                    </label>
                    <div class="flex flex-col flex-1 md:flex-row">
                        <input type="text"
                                id="field-selected-mobile"
                                class="rounded-4 border-normal border-0 text-[14px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[6px] min-h-[40px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                disabled>
                    </div>
                </div>
                <div class="col-span-12 md:col-span-12 xl:col-span-12">
                    <label for="field-selected-email" class="inline-flex items-center w-[178px] mb-[2px] text-[14px] font-medium capitalize dark:text-title-dark">
                        E-Mail
                    </label>
                    <div class="flex flex-col flex-1 md:flex-row">
                        <input type="text"
                                id="field-selected-email"
                                class="rounded-4 border-normal border-0 text-[14px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[6px] min-h-[40px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                disabled>
                    </div>
                </div>
                <div class="col-span-12 md:col-span-12 xl:col-span-12">
                    <label for="field-selected-manager" class="inline-flex items-center w-[178px] mb-[2px] text-[14px] font-medium capitalize dark:text-title-dark">
                        Encargado
                    </label>
                    <div class="flex flex-col flex-1 md:flex-row">
                        <input type="text"
                                id="field-selected-manager"
                                class="rounded-4 border-normal border-0 text-[14px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[6px] min-h-[40px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                disabled>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-span-12 2xl:col-span-4">
        <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative h-full">
            <div class="px-[25px] text-dark dark:text-title-dark font-medium text-[17px] flex flex-wrap items-center justify-between max-sm:flex-col max-sm:h-auto max-sm:mb-[15px]">
                <h2 class="mb-0 inline-flex items-center py-[16px] max-sm:pb-[5px] overflow-hidden whitespace-nowrap text-ellipsis text-[18px] font-semibold text-dark dark:text-title-dark  capitalize">
                    Usuarios
                </h2>
            </div>

            <div class="p-[25px] pt-0">
                <div class="scrollbar overflow-y-auto" style="max-height: 420px">
                    <div data-te-tab-active class="hidden opacity-100 transition-opacity duration-150 ease-linear data-[te-tab-active]:block">
                        <table class="min-w-full text-sm font-light text-left whitespace-nowrap" id="table-users">
                            <thead>
                                <tr>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-start text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden rounded-s-[4px]">
                                        USUARIO</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        TIPO</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        ACTIVO</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-box-dark">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-span-12 flex flex-row-reverse items-center gap-[5px]">
                    <button
                        type="button"
                        id="btn-nuevo-servicio"
                        class="px-[30px] h-[34px] mt-[14px] text-end text-white bg-primary border-regular hover:bg-primary-hbr disabled:text-neutral-600 disabled:bg-lightgray disabled:cursor-not-allowed font-medium rounded-4 text-sm w-full sm:w-auto capitalize transition-all duration-300 ease-linear"
                        data-te-ripple-init=""
                        data-te-ripple-color="light">
                        Registrar Usuario
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= asset('js/organizations/index.js'); ?>"></script>

<script>
    var currentLink = '<?= base_url('organizations'); ?>';
</script>