<?php
    $title = "Agregar Paciente";
    $section = "Pacientes";
    $subsection = "Agregar Paciente";

    require_once __DIR__.'/../layout/title.php';
?>

<form id="form-patient-add" no-validate action="javascript:RegisterPatient()">
    <div class="col-span-12 xl:col-span-6">
        <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative">
            <div class="px-[25px] text-dark dark:text-title-dark font-medium text-[17px] flex flex-wrap items-center justify-between max-sm:flex-col max-sm:h-auto border-b border-regular dark:border-box-dark-up">
            <h1 class="mb-0 inline-flex items-center py-[16px] overflow-hidden whitespace-nowrap text-ellipsis text-[18px] font-semibold text-dark dark:text-title-dark capitalize">
                Datos de Paciente
            </h1>
            </div>
            <div class="p-[25px]">
                <div class="sm:grid sm:grid-cols-12 max-sm:flex max-sm:flex-col gap-[25px]">
                    <div class="col-span-12 md:col-span-6">
                        <div class="flex flex-col pb-4 md:flex-row">
                            <label for="field-nombre" class="inline-flex items-center w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">Nombre</label>
                            <div class="flex items-center flex-1">
                                <div class="w-full rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[15px] py-[12px] min-h-[50px] focus:ring-primary focus:border-primary gap-[12px]  flex items-center">
                                    <span class="inline-flex items-center text-sm text-light dark:text-subtitle-dark me-[8px]">
                                    <i class="uil uil-user text-[16px]"></i>
                                    </span>
                                    <input type="text" id="field-nombre" name="nombre" class="outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full bg-transparent" placeholder="Nombres" required maxlength="60" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 md:col-span-6">
                        <div class="flex flex-col pb-4 md:flex-row">
                            <label for="field-apellido-paterno" class="inline-flex items-center w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">Apellido Paterno</label>
                            <div class="flex items-center flex-1">
                                <div class="w-full rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[15px] py-[12px] min-h-[50px] focus:ring-primary focus:border-primary gap-[12px]  flex items-center">
                                    <span class="inline-flex items-center text-sm text-light dark:text-subtitle-dark me-[8px]">
                                    <i class="uil uil-user text-[16px]"></i>
                                    </span>
                                    <input type="text" id="field-apellido-paterno" name="paterno" class="outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full bg-transparent" placeholder="Apellido Paterno" required maxlength="40" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 md:col-span-6">
                        <div class="flex flex-col pb-4 md:flex-row">
                            <label for="field-apellido-materno" class="inline-flex items-center w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">Apellido Materno</label>
                            <div class="flex items-center flex-1">
                                <div class="w-full rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[15px] py-[12px] min-h-[50px] focus:ring-primary focus:border-primary gap-[12px]  flex items-center">
                                    <span class="inline-flex items-center text-sm text-light dark:text-subtitle-dark me-[8px]">
                                    <i class="uil uil-user text-[16px]"></i>
                                    </span>
                                    <input type="text" id="field-apellido-materno" name="materno" class="outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full bg-transparent" placeholder="Apellido Materno" maxlength="40" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 md:col-span-6">
                        <div class="flex flex-col pb-4 md:flex-row">
                            <label for="field-fecha-nacimiento" class="inline-flex items-center w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">Fecha de Nacimiento</label>
                            <div class="flex items-center flex-1">
                                <div class="w-full rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[15px] py-[12px] min-h-[50px] focus:ring-primary focus:border-primary gap-[12px]  flex items-center">
                                    <span class="inline-flex items-center text-sm text-light dark:text-subtitle-dark me-[8px]">
                                    <i class="uil uil-schedule text-[16px]"></i>
                                    </span>
                                    <!-- <input type="text" id="field-fecha-nacimiento" name="fecha_nacimiento" class="outline-none dark:border-box-dark-up rounded-6 h-[46px] flex items-center w-full text-[14px] px-[20px] outline-none text-body dark:text-subtitle-dark dark:bg-box-dark-up"> -->
                                    <input type="text" id="field-fecha-nacimiento" name="fecha_nacimiento" class="outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full bg-transparent" placeholder="dd/mm/yyyy" readonly required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 md:col-span-6">
                        <div class="flex flex-col pb-4 md:flex-row">
                            <label for="select-genero" class="inline-flex items-center w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">Genero</label>
                            <div class="flex items-center flex-1">
                                <div class="w-full">
                                    <select id="select-genero" name="genero" data-te-select-init data-te-select-filter="true" data-te-select-init data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto" data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none" data-te-class-notch-middle="!border-0 !shadow-none !outline-none" data-te-class-notch-trailing="!border-0 !shadow-none !outline-none">
                                        
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 md:col-span-6">
                        <div class="flex flex-col pb-4 md:flex-row">
                            <label for="field-curp" class="inline-flex items-center w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">CURP</label>
                            <div class="flex items-center flex-1">
                                <div class="w-full rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[15px] py-[12px] min-h-[50px] focus:ring-primary focus:border-primary gap-[12px]  flex items-center">
                                    <span class="inline-flex items-center text-sm text-light dark:text-subtitle-dark me-[8px]">
                                    <i class="uil uil-user text-[16px]"></i>
                                    </span>
                                    <input type="text" id="field-curp" name="curp" class="outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full bg-transparent" placeholder="CURP" maxlength="20" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-[25px] text-dark dark:text-title-dark font-medium text-[17px] flex flex-wrap items-center justify-between max-sm:flex-col max-sm:h-auto border-b border-regular dark:border-box-dark-up">
            <h1 class="mb-0 inline-flex items-center py-[16px] overflow-hidden whitespace-nowrap text-ellipsis text-[18px] font-semibold text-dark dark:text-title-dark capitalize">
                Domicilio
            </h1>
            </div>

            <div class="p-[25px]">
                <div class="sm:grid sm:grid-cols-12 max-sm:flex max-sm:flex-col gap-[25px]">
                    <div class="col-span-12 md:col-span-6">
                        <div class="flex flex-col pb-4 md:flex-row">
                            <label for="field-calle" class="inline-flex items-center w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">Calle</label>
                            <div class="flex items-center flex-1">
                                <div class="w-full rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[15px] py-[12px] min-h-[50px] focus:ring-primary focus:border-primary gap-[12px]  flex items-center">
                                    <span class="inline-flex items-center text-sm text-light dark:text-subtitle-dark me-[8px]">
                                    <i class="uil uil-house-user text-[16px]"></i>
                                    </span>
                                    <input type="text" id="field-calle" name="calle" class="outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full bg-transparent" placeholder="Calle" maxlength="120" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 md:col-span-6">
                        <div class="flex flex-col pb-4 md:flex-row">
                            <label for="field-no-exterior" class="inline-flex items-center w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">No. Exterior</label>
                            <div class="flex items-center flex-1">
                                <div class="w-full rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[15px] py-[12px] min-h-[50px] focus:ring-primary focus:border-primary gap-[12px]  flex items-center">
                                    <span class="inline-flex items-center text-sm text-light dark:text-subtitle-dark me-[8px]">
                                    <i class="uil uil-house-user text-[16px]"></i>
                                    </span>
                                    <input type="text" id="field-no-exterior" name="no_exterior" class="outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full bg-transparent" placeholder="No. Exterior" maxlength="12" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 md:col-span-6">
                        <div class="flex flex-col pb-4 md:flex-row">
                            <label for="field-no-interior" class="inline-flex items-center w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">No. Interior</label>
                            <div class="flex items-center flex-1">
                                <div class="w-full rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[15px] py-[12px] min-h-[50px] focus:ring-primary focus:border-primary gap-[12px]  flex items-center">
                                    <span class="inline-flex items-center text-sm text-light dark:text-subtitle-dark me-[8px]">
                                    <i class="uil uil-house-user text-[16px]"></i>
                                    </span>
                                    <input type="text" id="field-no-interior" name="no_interior" class="outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full bg-transparent" placeholder="No. Interior" maxlength="12" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 md:col-span-6">
                        <div class="flex flex-col pb-4 md:flex-row">
                            <label for="select-pais" class="inline-flex items-center w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">País</label>
                            <div class="flex items-center flex-1">
                                <div class="w-full">
                                    <select id="select-pais" name="pais" data-te-select-init data-te-select-filter="true" data-te-select-init data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto" data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none" data-te-class-notch-middle="!border-0 !shadow-none !outline-none" data-te-class-notch-trailing="!border-0 !shadow-none !outline-none">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 md:col-span-6">
                        <div class="flex flex-col pb-4 md:flex-row">
                            <label for="select-estado" class="inline-flex items-center w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">Estado</label>
                            <div class="flex items-center flex-1">
                                <div class="w-full">
                                    <select id="select-estado" name="estado" data-te-select-init data-te-select-filter="true" data-te-select-init data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto" data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none" data-te-class-notch-middle="!border-0 !shadow-none !outline-none" data-te-class-notch-trailing="!border-0 !shadow-none !outline-none">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 md:col-span-6">
                        <div class="flex flex-col pb-4 md:flex-row">
                            <label for="select-municipio" class="inline-flex items-center w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">Municipio</label>
                            <div class="flex items-center flex-1">
                                <div class="w-full">
                                    <select id="select-municipio" name="municipio" data-te-select-init data-te-select-filter="true" data-te-select-init data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto" data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none" data-te-class-notch-middle="!border-0 !shadow-none !outline-none" data-te-class-notch-trailing="!border-0 !shadow-none !outline-none">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 md:col-span-6">
                        <div class="flex flex-col pb-4 md:flex-row">
                            <label for="select-colonia" class="inline-flex items-center w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">Colonia</label>
                            <div class="flex items-center flex-1">
                                <div class="w-full">
                                    <select id="select-colonia" name="colonia" data-te-select-init data-te-select-filter="true" data-te-select-init data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto" data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none" data-te-class-notch-middle="!border-0 !shadow-none !outline-none" data-te-class-notch-trailing="!border-0 !shadow-none !outline-none">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-[25px] text-dark dark:text-title-dark font-medium text-[17px] flex flex-wrap items-center justify-between max-sm:flex-col max-sm:h-auto border-b border-regular dark:border-box-dark-up">
            <h1 class="mb-0 inline-flex items-center py-[16px] overflow-hidden whitespace-nowrap text-ellipsis text-[18px] font-semibold text-dark dark:text-title-dark capitalize">
                Datos de Contacto
            </h1>
            </div>

            <div class="p-[25px]">
                <div class="sm:grid sm:grid-cols-12 max-sm:flex max-sm:flex-col gap-[25px]">
                    <div class="col-span-12 md:col-span-6">
                        <div class="flex flex-col pb-4 md:flex-row">
                            <label for="field-email" class="inline-flex items-center w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">Email</label>
                            <div class="flex items-center flex-1">
                                <div class="w-full rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[15px] py-[12px] min-h-[50px] focus:ring-primary focus:border-primary gap-[12px]  flex items-center">
                                    <span class="inline-flex items-center text-sm text-light dark:text-subtitle-dark me-[8px]">
                                    <i class="uil uil-envelope text-[16px]"></i>
                                    </span>
                                    <input type="email" id="field-email" name="email" class="outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full bg-transparent" placeholder="example@gmail.com" maxlength="255" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 md:col-span-6">
                        <div class="flex flex-col pb-4 md:flex-row">
                            <label for="field-telefono" class="inline-flex items-center w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">Tel&eacute;fono</label>
                            <div class="flex items-center flex-1">
                                <div class="w-full rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[15px] py-[12px] min-h-[50px] focus:ring-primary focus:border-primary gap-[12px]  flex items-center">
                                    <span class="inline-flex items-center text-sm text-light dark:text-subtitle-dark me-[8px]">
                                    <i class="uil uil-phone text-[16px]"></i>
                                    </span>
                                    <input type="text" id="field-telefono" name="telefono" class="outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full bg-transparent" placeholder="5555555555" maxlength="40" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 md:col-span-6">
                        <div class="flex flex-col pb-4 md:flex-row">
                            <label for="field-telefono-movil" class="inline-flex items-center w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">Tel&eacute;fono M&oacute;vil</label>
                            <div class="flex items-center flex-1">
                                <div class="w-full rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[15px] py-[12px] min-h-[50px] focus:ring-primary focus:border-primary gap-[12px]  flex items-center">
                                    <span class="inline-flex items-center text-sm text-light dark:text-subtitle-dark me-[8px]">
                                    <i class="uil uil-mobile-android text-[16px]"></i>
                                    </span>
                                    <input type="text" id="field-telefono-movil" name="telefono_movil" class="outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full bg-transparent" placeholder="5555555555" maxlength="40" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>

            <div class="px-[25px] text-dark dark:text-title-dark font-medium text-[17px] flex flex-wrap items-center justify-between max-sm:flex-col max-sm:h-auto border-b border-regular dark:border-box-dark-up">
            <h1 class="mb-0 inline-flex items-center py-[16px] overflow-hidden whitespace-nowrap text-ellipsis text-[18px] font-semibold text-dark dark:text-title-dark capitalize">
                Observaciones & Antecedentes
            </h1>
            </div>

            <div class="p-[25px]">
                <div class="sm:grid sm:grid-cols-12 max-sm:flex max-sm:flex-col gap-[25px]">
                    <div class="col-span-12 md:col-span-12">
                        <div class="flex flex-col pb-4 md:flex-row">
                            <label for="field-observaciones" class="inline-flex align-top w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">Observaciones Generales</label>
                            <div class="flex flex-1">
                                <div class="w-full">
                                    <textarea id="field-observaciones" name="observaciones" rows="5" class=" rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary resize-none" placeholder="Captura observaciones generales del paciente..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 md:col-span-12">
                        <div class="flex flex-col pb-4 md:flex-row">
                            <label for="field-medicamentos" class="inline-flex align-top w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">Medicamentos</label>
                            <div class="flex flex-1">
                                <div class="w-full">
                                    <textarea id="field-medicamentos" name="medicamentos" rows="5" class=" rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary resize-none" placeholder="Captura medicamentos que toma del paciente..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 md:col-span-12">
                        <div class="flex flex-col pb-4 md:flex-row">
                            <label for="field-suplementos" class="inline-flex align-top w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">Suplementos</label>
                            <div class="flex flex-1">
                                <div class="w-full">
                                    <textarea id="field-suplementos" name="suplementos" rows="5" class=" rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary resize-none" placeholder="Captura suplementos/vitaminas que toma del paciente..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 md:col-span-12">
                        <div class="flex flex-col pb-4 md:flex-row">
                            <label for="field-antecedentes-familiares" class="inline-flex align-top w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">Antecedentes Familiares</label>
                            <div class="flex flex-1">
                                <div class="w-full">
                                    <textarea id="field-antecedentes-familiares" name="antecedentes_familiares" rows="5" class=" rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary resize-none" placeholder="Captura antecedentes familiares del paciente..."></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>

            <div class="px-[25px] text-dark dark:text-title-dark font-medium text-[17px] flex flex-wrap items-center justify-between max-sm:flex-col max-sm:h-auto border-b border-regular dark:border-box-dark-up">
            <h1 class="mb-0 inline-flex items-center py-[16px] overflow-hidden whitespace-nowrap text-ellipsis text-[18px] font-semibold text-dark dark:text-title-dark capitalize">
                Facturación
            </h1>
            </div>

            <div class="p-[25px]">
                <div class="sm:grid sm:grid-cols-12 max-sm:flex max-sm:flex-col gap-[25px]">
                    <div class="col-span-12 md:col-span-12">
                        <div class="flex flex-col pb-4 md:flex-row gap-[25px]">
                            <label for="chk-agregar-facturacion" class="inline-flex items-center w-[278px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">Agregar Datos de Facturación</label>
                            <div class="flex items-center flex-1">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" id="chk-agregar-facturacion" name="agregar_facturacion" class="sr-only peer">
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/10 dark:peer-focus:ring-transparent rounded-full peer dark:bg-box-dark-up peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary">
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 md:col-span-6 billing-data">
                        <div class="flex flex-col pb-4 md:flex-row">
                            <label for="field-facturacion-rfc" class="inline-flex items-center w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">RFC</label>
                            <div class="flex items-center flex-1">
                                <div class="w-full rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[15px] py-[12px] min-h-[50px] focus:ring-primary focus:border-primary gap-[12px]  flex items-center">
                                    <span class="inline-flex items-center text-sm text-light dark:text-subtitle-dark me-[8px]">
                                    <i class="uil uil-building text-[16px]"></i>
                                    </span>
                                    <input type="text" id="field-facturacion-rfc" name="facturacion_rfc" class="outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full bg-transparent" placeholder="ERPXXXXX1H8" maxlength="18" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 md:col-span-6 billing-data">
                        <div class="flex flex-col pb-4 md:flex-row">
                            <label for="field-facturacion-razon-social" class="inline-flex items-center w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">Razón Social</label>
                            <div class="flex items-center flex-1">
                                <div class="w-full rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[15px] py-[12px] min-h-[50px] focus:ring-primary focus:border-primary gap-[12px]  flex items-center">
                                    <span class="inline-flex items-center text-sm text-light dark:text-subtitle-dark me-[8px]">
                                    <i class="uil uil-user-square text-[16px]"></i>
                                    </span>
                                    <input type="text" id="field-facturacion-razon-social" name="facturacion_razon_social" class="outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full bg-transparent" placeholder="5555555555" maxlength="255" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 md:col-span-6 billing-data">
                        <div class="flex flex-col pb-4 md:flex-row">
                            <label for="select-facturacion-regimen" class="inline-flex items-center w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">Regimen Fiscal</label>
                            <div class="flex items-center flex-1">
                                <div class="w-full">
                                    <select id="select-facturacion-regimen" name="facturacion_regimen" data-te-select-init data-te-select-filter="true" data-te-select-init data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto" data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none" data-te-class-notch-middle="!border-0 !shadow-none !outline-none" data-te-class-notch-trailing="!border-0 !shadow-none !outline-none">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 md:col-span-6 billing-data">
                        <div class="flex flex-col pb-4 md:flex-row">
                            <label for="field-facturacion-codigo-postal" class="inline-flex items-center w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">Código Postal</label>
                            <div class="flex items-center flex-1">
                                <div class="w-full rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[15px] py-[12px] min-h-[50px] focus:ring-primary focus:border-primary gap-[12px]  flex items-center">
                                    <span class="inline-flex items-center text-sm text-light dark:text-subtitle-dark me-[8px]">
                                    <i class="uil uil-estate text-[16px]"></i>
                                    </span>
                                    <input type="text" id="field-facturacion-codigo-postal" name="facturacion_codigo_postal" class="outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full bg-transparent" placeholder="Código Postal" maxlength="5" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 md:col-span-6 billing-data">
                        <div class="flex flex-col pb-4 md:flex-row">
                            <label for="field-facturacion-calle" class="inline-flex items-center w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">Calle</label>
                            <div class="flex items-center flex-1">
                                <div class="w-full rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[15px] py-[12px] min-h-[50px] focus:ring-primary focus:border-primary gap-[12px]  flex items-center">
                                    <span class="inline-flex items-center text-sm text-light dark:text-subtitle-dark me-[8px]">
                                    <i class="uil uil-house-user text-[16px]"></i>
                                    </span>
                                    <input type="text" id="field-facturacion-calle" name="facturacion_calle" class="outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full bg-transparent" placeholder="Calle" maxlength="120" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 md:col-span-6 billing-data">
                        <div class="flex flex-col pb-4 md:flex-row">
                            <label for="field-facturacion-no-exterior" class="inline-flex items-center w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">No. Exterior</label>
                            <div class="flex items-center flex-1">
                                <div class="w-full rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[15px] py-[12px] min-h-[50px] focus:ring-primary focus:border-primary gap-[12px]  flex items-center">
                                    <span class="inline-flex items-center text-sm text-light dark:text-subtitle-dark me-[8px]">
                                    <i class="uil uil-house-user text-[16px]"></i>
                                    </span>
                                    <input type="text" id="field-facturacion-no-exterior" name="facturacion_no_exterior" class="outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full bg-transparent" placeholder="No. Exterior" maxlength="12" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 md:col-span-6 billing-data">
                        <div class="flex flex-col pb-4 md:flex-row">
                            <label for="field-facturacion-no-interior" class="inline-flex items-center w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">No. Interior</label>
                            <div class="flex items-center flex-1">
                                <div class="w-full rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[15px] py-[12px] min-h-[50px] focus:ring-primary focus:border-primary gap-[12px]  flex items-center">
                                    <span class="inline-flex items-center text-sm text-light dark:text-subtitle-dark me-[8px]">
                                    <i class="uil uil-house-user text-[16px]"></i>
                                    </span>
                                    <input type="text" id="field-facturacion-no-interior" name="facturacion_no_interior" class="outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full bg-transparent" placeholder="No. Interior" maxlength="12" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 md:col-span-6 billing-data">
                        <div class="flex flex-col pb-4 md:flex-row">
                            <label for="select-facturacion-pais" class="inline-flex items-center w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">País</label>
                            <div class="flex items-center flex-1">
                                <div class="w-full">
                                    <select id="select-facturacion-pais" name="facturacion_pais" data-te-select-init data-te-select-filter="true" data-te-select-init data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto" data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none" data-te-class-notch-middle="!border-0 !shadow-none !outline-none" data-te-class-notch-trailing="!border-0 !shadow-none !outline-none">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 md:col-span-6 billing-data">
                        <div class="flex flex-col pb-4 md:flex-row">
                            <label for="select-facturacion-estado" class="inline-flex items-center w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">Estado</label>
                            <div class="flex items-center flex-1">
                                <div class="w-full">
                                    <select id="select-facturacion-estado" name="facturacion_estado" data-te-select-init data-te-select-filter="true" data-te-select-init data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto" data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none" data-te-class-notch-middle="!border-0 !shadow-none !outline-none" data-te-class-notch-trailing="!border-0 !shadow-none !outline-none">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 md:col-span-6 billing-data">
                        <div class="flex flex-col pb-4 md:flex-row">
                            <label for="select-facturacion-municipio" class="inline-flex items-center w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">Municipio</label>
                            <div class="flex items-center flex-1">
                                <div class="w-full">
                                    <select id="select-facturacion-municipio" name="facturacion_municipio" data-te-select-init data-te-select-filter="true" data-te-select-init data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto" data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none" data-te-class-notch-middle="!border-0 !shadow-none !outline-none" data-te-class-notch-trailing="!border-0 !shadow-none !outline-none">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 md:col-span-6 billing-data">
                        <div class="flex flex-col pb-4 md:flex-row">
                            <label for="select-facturacion-colonia" class="inline-flex items-center w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">Colonia</label>
                            <div class="flex items-center flex-1">
                                <div class="w-full">
                                    <select id="select-facturacion-colonia" name="facturacion_colonia" data-te-select-init data-te-select-filter="true" data-te-select-init data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto" data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none" data-te-class-notch-middle="!border-0 !shadow-none !outline-none" data-te-class-notch-trailing="!border-0 !shadow-none !outline-none">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 md:col-span-6 billing-data">
                        <div class="flex flex-col pb-4 md:flex-row">
                            <label for="field-facturacion-email" class="inline-flex items-center w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">Email</label>
                            <div class="flex items-center flex-1">
                                <div class="w-full rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[15px] py-[12px] min-h-[50px] focus:ring-primary focus:border-primary gap-[12px]  flex items-center">
                                    <span class="inline-flex items-center text-sm text-light dark:text-subtitle-dark me-[8px]">
                                    <i class="uil uil-envelope text-[16px]"></i>
                                    </span>
                                    <input type="email" id="field-facturacion-email" name="facturacion_email" class="outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full bg-transparent" placeholder="example@gmail.com" maxlength="255" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 md:col-span-6 billing-data">
                        <div class="flex flex-col pb-4 md:flex-row">
                            <label for="field-facturacion-telefono" class="inline-flex items-center w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">Tel&eacute;fono</label>
                            <div class="flex items-center flex-1">
                                <div class="w-full rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[15px] py-[12px] min-h-[50px] focus:ring-primary focus:border-primary gap-[12px]  flex items-center">
                                    <span class="inline-flex items-center text-sm text-light dark:text-subtitle-dark me-[8px]">
                                    <i class="uil uil-phone text-[16px]"></i>
                                    </span>
                                    <input type="text" id="field-facturacion-telefono" name="facturacion_telefono" class="outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full bg-transparent" placeholder="5555555555" maxlength="40" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>

        <div class="me-4 flex flex-row-reverse items-center gap-[10px] mt-[14px]">
            <button type="submit" class="px-[30px] h-[44px] mb-[14px] text-white bg-primary border-primary hover:bg-primary-hbr font-medium rounded-4 text-sm w-full sm:w-auto text-center inline-flex items-center justify-center capitalize transition-all duration-300 ease-linear" data-te-ripple-init="" data-te-ripple-color="light">Registrar</button>
            <button type="button" class="px-[30px] h-[44px] mb-[14px] text-white bg-danger border-regular hover:bg-danger-hbr font-medium rounded-4 text-sm w-full sm:w-auto text-center inline-flex items-center justify-center capitalize transition-all duration-300 ease-linear" data-te-ripple-init="" data-te-ripple-color="light">Cancelar</button>
        </div>
        </div>
    </div>
</form>

<script src="<?= asset('js/patients/add.js'); ?>"></script>

<script>
    var callbackRequest = '<?= isset($_GET['callback']) ? $_GET['callback'] : ''; ?>';
    var currentLink = '<?= base_url('patients'); ?>';
</script>