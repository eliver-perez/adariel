<?php
    $title = "Consultas";
    $section = "Consultas";

    require_once __DIR__.'/../layout/title.php';
?>

<div class="sm:grid sm:grid-cols-12 max-sm:flex max-sm:flex-col gap-[25px]">
    <div class="col-span-12 lg:col-span-8">
        <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative h-full">
            <div class="px-[25px] text-dark dark:text-title-dark font-medium text-[17px] flex flex-wrap items-center justify-between max-sm:flex-col max-sm:h-auto max-sm:mb-[15px]">
                <h2 class="mb-0 inline-flex items-center py-[16px] max-sm:pb-[5px] overflow-hidden whitespace-nowrap text-ellipsis text-[18px] font-semibold text-dark dark:text-title-dark  capitalize">
                    Consultas
                </h2>
                <div>
                    <select id="select-filtro-consulta-estatus"
                        name="filtro_consulta_estatus"
                        data-te-select-init data-te-select-init data-te-class-select-input="py-[5px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto">
                        
                    </select>
                </div>
            </div>
            
            <div class="p-[25px] pt-0">
                <div class="scrollbar overflow-y-auto" style="max-height: 65vh">
                    <div data-te-tab-active class="hidden opacity-100 transition-opacity duration-150 ease-linear data-[te-tab-active]:block">
                        <table class="min-w-full text-sm font-light text-left whitespace-nowrap" id="table-procedures">
                            <thead>
                                <tr>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-start text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden rounded-s-[4px]">
                                        PACIENTE</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        EDAD</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        ASUNTO</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        FECHA INICIO</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        DURACION</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        ESTATUS</th>
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
                    <div class=" px-2 py-1 text-[10px] font-medium text-primary uppercase rounded-md bg-primary/20" id="field-consultation-type">
                        ...
                    </div>
                    <div class=" px-2 py-1 text-[10px] font-medium text-warning uppercase rounded-md bg-warning/20" id="field-consultation-status">
                        ...
                    </div>
                </div>
                <div class="col-span-12 2xl:col-span-3 lg:col-span-5">
                    <h4 class="text-[14px] text-light dark:text-subtitle-dark mb-[8px] font-normal">Folio:</h4>
                    <div class="flex items-center mb-[15px] gap-[15px]">
                        <span class="text-[14px] font-medium text-theme-gray dark:text-subtitle-dark capitalize" id="field-consultation-folio">...</span>
                    </div>
                </div>
                <div class="col-span-12 2xl:col-span-4 lg:col-span-7">
                    <h4 class="text-[14px] text-light dark:text-subtitle-dark mb-[8px] font-normal">Edad:</h4>
                    <div class="flex items-center mb-[15px] gap-[15px]">
                        <span class="text-[14px] font-medium text-theme-gray dark:text-subtitle-dark capitalize" id="field-consultation-age">...</span>
                    </div>
                </div>
                <div class="col-span-12 2xl:col-span-9 lg:col-span-12">
                    <h4 class="text-[14px] text-light dark:text-subtitle-dark mb-[8px] font-normal">Paciente:</h4>
                    <div class="flex items-center mb-[15px] gap-[15px]">
                        <span class="text-[14px] font-medium text-theme-gray dark:text-subtitle-dark capitalize" id="field-consultation-patient">...</span>
                    </div>
                </div>
                <div class="col-span-12 2xl:col-span-4 lg:col-span-7">
                    <h4 class="text-[14px] text-light dark:text-subtitle-dark mb-[8px] font-normal">Fecha de Cita:</h4>
                    <div class="flex items-center mb-[15px] gap-[15px]">
                        <span class="text-[14px] font-medium text-theme-gray dark:text-subtitle-dark capitalize" id="field-consultation-date">...</span>
                    </div>
                </div>
                <div class="col-span-12 2xl:col-span-4 lg:col-span-5">
                    <h4 class="text-[14px] text-light dark:text-subtitle-dark mb-[8px] font-normal">Duración:</h4>
                    <div class="flex items-center mb-[15px] gap-[15px]">
                        <span class="text-[14px] font-medium text-theme-gray dark:text-subtitle-dark capitalize" id="field-consultation-duration">...</span>
                    </div>
                </div>
            </div>
            <h4 class="text-[20px] font-semibold leading-1 text-theme-gray dark:text-subtitle-dark mb-[12px]">Motivo de Consulta:
            </h4>
            <p class="text-theme-gray dark:text-subtitle-dark text-[16px] font-normal" id="field-consultation-chief-complaint">...</p>

            <div class="col-span-12 flex flex-row-reverse items-center gap-[5px] mt-[14px]">
                <button type="button" id="btn-consultation-start" class="px-[30px] h-[34px] text-white bg-primary border-regular hover:bg-primary-hbr disabled:text-neutral-600 disabled:bg-lightgray disabled:cursor-not-allowed font-medium rounded-4 text-sm w-full sm:w-auto text-center inline-flex items-center justify-center capitalize transition-all duration-300 ease-linear" data-te-ripple-init="" data-te-ripple-color="light" disabled>Iniciar Consulta</button>
                <button type="button" id="btn-registrar-servicio" class="!visible hidden px-[30px] h-[34px] text-white bg-primary border-regular hover:bg-primary-hbr disabled:text-neutral-600 disabled:bg-lightgray disabled:cursor-not-allowed font-medium rounded-4 text-sm w-full sm:w-auto text-center inline-flex items-center justify-center capitalize transition-all duration-300 ease-linear" data-te-ripple-init="" data-te-ripple-color="light" disabled>Cancelar</button>
            </div>
        </div>
    </div>  
</div>

<script src="<?= asset('js/consultations/index.js'); ?>"></script>

<script>
    var currentLink = '<?= base_url('consultations'); ?>';
</script>