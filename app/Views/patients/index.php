<?php
    $title = "Pacientes";
    $section = "Pacientes";

    require_once __DIR__.'/../layout/title.php';
?>

<div class="sm:grid sm:grid-cols-12 max-sm:flex max-sm:flex-col gap-[25px]">
    <div class="lg:col-span-4 col-span-12">
        <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative h-full">
            <div class="px-[25px] text-dark dark:text-title-dark font-medium text-[17px] flex flex-wrap items-center justify-between max-sm:flex-col max-sm:h-auto max-sm:mb-[15px]">
                <h2 class="mb-0 inline-flex items-center py-[16px] max-sm:pb-[5px] overflow-hidden whitespace-nowrap text-ellipsis text-[18px] font-semibold text-dark dark:text-title-dark  capitalize">
                    Pacientes
                </h2>
            </div>
            
            <div class="p-[25px] pt-0">
                <div class="h-60 scrollbar overflow-y-auto">
                    <div data-te-tab-active class="hidden opacity-100 transition-opacity duration-150 ease-linear data-[te-tab-active]:block">
                        <table class="min-w-full text-sm font-light text-left whitespace-nowrap" id="table-patients">
                            <thead>
                                <tr>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-start text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden rounded-s-[4px]">
                                        CLAVE</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        NOMBRE</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        FECHA NACIMIENTO</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        GENERO</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        TELÉFONO</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        TELÉFONO MÓVIL</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-end text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden rounded-e-[4px]">
                                        ÚLTIMA VISITA</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-box-dark">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
                <button id="btn-registrar-paciente" type="button" class="w-full px-[20px] mt-2 text-sm text-white rounded-md font-semibold bg-primary border-primary h-10 gap-[6px] transition-[0.3s]">
                    <i class="uil uil-plus"></i>
                    <span class="m-0">Nuevo Paciente</span>
                </button>
            </div>
        </div>
    </div>

    <div class="lg:col-span-8 col-span-12">
        <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-b-0 rounded-t-10 relative">
            <div class="bg-white dark:bg-box-dark sm:px-[25px] px-[15px] pt-[30px] rounded-b-0 rounded-t-10 text-center">
                <div class="sm:grid sm:grid-cols-12 max-sm:flex max-sm:flex-col gap-[25px] text-start">
                    <div class="col-span-12 lg:col-span-3 text-center">
                        <figure class="relative mb-0 max-w-[120px] h-[120px] inline-block bg-normalBG dark:bg-box-dark-up rounded-full">
                            <img id="widget-profile-upload-image" class="max-w-[120px] min-w-[120px] h-[120px] rounded-full inline-block object-cover" src="<?= asset('images/no-picture.jpeg'); ?>" alt="author">
                        </figure>
                    </div>
                    <div class="col-span-12 lg:col-span-9 sm:grid sm:grid-cols-12 max-sm:flex max-sm:flex-col text-start">
                        <div class="col-span-12 lg:col-span-5">
                            <h4 class="text-[14px] text-light dark:text-subtitle-dark mb-[12px] font-normal">Paciente:</h4>
                            <div class="flex items-center mb-[5px] gap-[15px]">
                                <span class="text-[14px] font-medium text-theme-gray dark:text-subtitle-dark capitalize">...</span>
                            </div>
                        </div>
                        <div class="col-span-12 lg:col-span-4">
                            <h4 class="text-[14px] text-light dark:text-subtitle-dark mb-[12px] font-normal">Fecha de Nacimiento</h4>
                            <div class="flex items-center mb-[5px] gap-[15px]">
                                <span class="text-[14px] font-medium text-theme-gray dark:text-subtitle-dark capitalize">...</span>
                            </div>
                        </div>
                        <div class="col-span-12 lg:col-span-3">
                            <h4 class="text-[14px] text-light dark:text-subtitle-dark mb-[12px] font-normal">Clave:</h4>
                            <div class="flex items-center mb-[5px] gap-[15px]">
                                <span class="text-[14px] font-medium text-theme-gray dark:text-subtitle-dark capitalize">...</span>
                            </div>
                        </div>
                        <div class="col-span-12 lg:col-span-6">
                            <h4 class="text-[14px] text-light dark:text-subtitle-dark mb-[12px] font-normal">Domicilio:</h4>
                            <div class="flex items-center mb-[5px] gap-[15px]">
                                <span class="text-[14px] font-medium text-theme-gray dark:text-subtitle-dark capitalize">...</span>
                            </div>
                        </div>
                        <div class="col-span-12 lg:col-span-3">
                            <h4 class="text-[14px] text-light dark:text-subtitle-dark mb-[12px] font-normal">CURP</h4>
                            <div class="flex items-center mb-[5px] gap-[15px]">
                                <span class="text-[14px] font-medium text-theme-gray dark:text-subtitle-dark capitalize">...</span>
                            </div>
                        </div>
                        <div class="col-span-12 lg:col-span-3">
                            <h4 class="text-[14px] text-light dark:text-subtitle-dark mb-[12px] font-normal">Genero:</h4>
                            <div class="flex items-center mb-[5px] gap-[15px]">
                                <span class="text-[14px] font-medium text-theme-gray dark:text-subtitle-dark capitalize">...</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 sm:grid sm:grid-cols-12 max-sm:flex max-sm:flex-col gap-[25px] text-start">
                        <div class="col-span-12 lg:col-span-2">
                            <h4 class="text-[14px] text-light dark:text-subtitle-dark mb-[12px] font-normal">Teléfono:</h4>
                            <div class="flex items-center mb-[5px] gap-[15px]">
                                <span class="text-[14px] font-medium text-theme-gray dark:text-subtitle-dark capitalize">...</span>
                            </div>
                        </div>
                        <div class="col-span-12 lg:col-span-2">
                            <h4 class="text-[14px] text-light dark:text-subtitle-dark mb-[12px] font-normal">Tel. Móvil:</h4>
                            <div class="flex items-center mb-[5px] gap-[15px]">
                                <span class="text-[14px] font-medium text-theme-gray dark:text-subtitle-dark capitalize">...</span>
                            </div>
                        </div>
                        <div class="col-span-12 lg:col-span-3">
                            <h4 class="text-[14px] text-light dark:text-subtitle-dark mb-[12px] font-normal">E-Mail:</h4>
                            <div class="flex items-center mb-[5px] gap-[15px]">
                                <span class="text-[14px] font-medium text-theme-gray dark:text-subtitle-dark capitalize">...</span>
                            </div>
                        </div>
                        <div class="col-span-12 lg:col-span-3">
                            <h4 class="text-[14px] text-light dark:text-subtitle-dark mb-[12px] font-normal">Registrado por:</h4>
                            <div class="flex items-center mb-[5px] gap-[15px]">
                                <span class="text-[14px] font-medium text-theme-gray dark:text-subtitle-dark capitalize">...</span>
                            </div>
                        </div>
                        <div class="col-span-12 lg:col-span-2">
                            <h4 class="text-[14px] text-light dark:text-subtitle-dark mb-[12px] font-normal">Fecha de Registro:</h4>
                            <div class="flex items-center mb-[5px] gap-[15px]">
                                <span class="text-[14px] font-medium text-theme-gray dark:text-subtitle-dark capitalize">...</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
                        <div class="tabs">
                            <nav class="sm:px-[25px] px-[15px] bg-white dark:bg-box-dark rounded-t-0 rounded-b-10 mb-[25px] overflow-x-auto">
                                <ul class="m-0 flex items-center sm:gap-x-[22px] gap-x-[10px] max-sm:gap-[5px]" role="tablist" aria-label="Social form">
                                    <li>
                                        <button type="button" role="tab" aria-selected="true" id="tabs-observacion_inicial" onclick="javascript:selectTab('observacion_inicial')" class="relative block py-[20px] px-[5px] text-light dark:text-subtitle-dark [&.active]:text-primary after:[&.active]:bg-primary after:absolute after:bottom-0 after:end-0 after:w-full after:h-[2px] after:bg-transparent after:transition-all after:duration-300 after:ease-in-out after:invisible [&.active]:after:visible font-medium active">Observación inicial</button>
                                    </li>
                                    <li>
                                        <button type="button" role="tab" aria-selected="true" id="tabs-procedimientos" onclick="javascript:selectTab('procedimientos')" class="relative block py-[20px] px-[5px] text-light dark:text-subtitle-dark [&.active]:text-primary after:[&.active]:bg-primary after:absolute after:bottom-0 after:end-0 after:w-full after:h-[2px] after:bg-transparent after:transition-all after:duration-300 after:ease-in-out after:invisible [&.active]:after:visible font-medium">Procedimientos realizados</button>
                                    </li>
                                </ul>
                            </nav>
                            
                            <div class="transition-opacity duration-150 ease-linear opacity-100" role="tabpanel" aria-labelledby="tabs-observacion_inicial">
                                <div class="grid grid-cols-12 sm:gap-[25px] gap-y-[25px]">
                                    <div class="col-span-12">
                                        <div class="bg-white dark:bg-box-dark rounded-10 pt-[25px] px-[25px] pb-[30px] shadow-[0_5px_20px_rgba(173,181,217,0.05)] dark:shadow-none">
                                            <div class="mb-[25px]">
                                                <h4 class="text-[20px] text-dark dark:text-subtitle-dark font-medium">Observaciones</h4>
                                                <span class="text-[14px] font-medium text-theme-gray dark:text-subtitle-dark">Motivo de consulta y observaciones iniciales del paciente</span>
                                            </div>
                                            <!-- CONTENIDO -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="transition-opacity duration-150 ease-linear opacity-100" role="tabpanel" aria-labelledby="tabs-procedimientos">
                                <div class="grid grid-cols-12 sm:gap-[25px] gap-y-[25px]">
                                    <div class="col-span-12">
                                        <div class="bg-white dark:bg-box-dark rounded-10 pt-[25px] px-[25px] pb-[30px] shadow-[0_5px_20px_rgba(173,181,217,0.05)] dark:shadow-none">
                                            <div class="mb-[25px]">
                                                <h4 class="text-[20px] text-dark dark:text-subtitle-dark font-medium">Procedimientos realizados</h4>
                                                <span class="text-[14px] font-medium text-theme-gray dark:text-subtitle-dark">Procedimientos y tratamientos aplicados durante la consulta</span>
                                            </div>
                                            <!-- CONTENIDO -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
    </div>
</div>

<script src="<?= asset('js/patients/index.js'); ?>"></script>

<script>
    var currentLink = '<?= base_url('patients'); ?>';
</script>