<?php
    $title = "Agregar Personal";
    $section = "Personal";
    $subsection = "Agregar Personal";

    require_once __DIR__.'/../layout/title.php';
?>

            <form id="form-staff-add" no-validate action="javascript:RegisterStaff()">
               <div class="col-span-12 xl:col-span-6">
                  <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative">
                     <div class="px-[25px] text-dark dark:text-title-dark font-medium text-[17px] flex flex-wrap items-center justify-between max-sm:flex-col max-sm:h-auto border-b border-regular dark:border-box-dark-up">
                        <h1 class="mb-0 inline-flex items-center py-[16px] overflow-hidden whitespace-nowrap text-ellipsis text-[18px] font-semibold text-dark dark:text-title-dark capitalize">
                           Datos de Personal
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
                                                <select id="select-pais"
                                                    name="pais"
                                                    data-te-select-init
                                                    data-te-select-filter="true"
                                                    data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto"
                                                    data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none"
                                                    data-te-class-notch-middle="!border-0 !shadow-none !outline-none"
                                                    data-te-class-notch-trailing="!border-0 !shadow-none !outline-none">
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
                                                <select id="select-estado"
                                                    name="estado"
                                                    data-te-select-init
                                                    data-te-select-filter="true"
                                                    data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto"
                                                    data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none"
                                                    data-te-class-notch-middle="!border-0 !shadow-none !outline-none"
                                                    data-te-class-notch-trailing="!border-0 !shadow-none !outline-none">
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
                                                <select id="select-municipio"
                                                    name="municipio"
                                                    data-te-select-init
                                                    data-te-select-filter="true"
                                                    data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto"
                                                    data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none"
                                                    data-te-class-notch-middle="!border-0 !shadow-none !outline-none"
                                                    data-te-class-notch-trailing="!border-0 !shadow-none !outline-none">
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
                                                <select id="select-colonia"
                                                    name="colonia"
                                                    data-te-select-init
                                                    data-te-select-filter="true"
                                                    data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto"
                                                    data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none"
                                                    data-te-class-notch-middle="!border-0 !shadow-none !outline-none"
                                                    data-te-class-notch-trailing="!border-0 !shadow-none !outline-none">
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
                           Datos de Acceso
                        </h1>
                     </div>

                     <div class="p-[25px]">
                            <div class="sm:grid sm:grid-cols-12 max-sm:flex max-sm:flex-col gap-[25px]">
                                <div class="col-span-12 md:col-span-6">
                                    <div class="flex flex-col pb-4 md:flex-row">
                                        <label for="select-usuario" class="inline-flex items-center w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">Usuario</label>
                                        <div class="flex items-center flex-1">
                                            <div class="w-full">
                                                <select id="select-usuario"
                                                    name="usuario"
                                                    data-te-select-init
                                                    data-te-select-filter="true"
                                                    data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto"
                                                    data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none"
                                                    data-te-class-notch-middle="!border-0 !shadow-none !outline-none"
                                                    data-te-class-notch-trailing="!border-0 !shadow-none !outline-none">
                                                    <option value="N">Sin Usuario</option>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>

                     <div class="px-[25px] text-dark dark:text-title-dark font-medium text-[17px] flex flex-wrap items-center justify-between max-sm:flex-col max-sm:h-auto border-b border-regular dark:border-box-dark-up">
                        <h1 class="mb-0 inline-flex items-center py-[16px] overflow-hidden whitespace-nowrap text-ellipsis text-[18px] font-semibold text-dark dark:text-title-dark capitalize">
                           Puesto
                        </h1>
                     </div>

                     <div class="p-[25px]">
                            <div class="sm:grid sm:grid-cols-12 max-sm:flex max-sm:flex-col gap-[25px]">
                                <div class="col-span-12 md:col-span-6">
                                    <div class="flex flex-col pb-4 md:flex-row">
                                        <label for="select-puesto" class="inline-flex items-center w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">Puesto</label>
                                        <div class="flex items-center flex-1">
                                            <div class="w-full">
                                                <select id="select-puesto" name="puesto" data-te-select-init data-te-select-filter="true" data-te-select-init data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto" data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none" data-te-class-notch-middle="!border-0 !shadow-none !outline-none" data-te-class-notch-trailing="!border-0 !shadow-none !outline-none">
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>

                     <div class="px-[25px] text-dark dark:text-title-dark font-medium text-[17px] flex flex-wrap items-center justify-between max-sm:flex-col max-sm:h-auto border-b border-regular dark:border-box-dark-up">
                        <h1 class="mb-0 inline-flex items-center py-[16px] overflow-hidden whitespace-nowrap text-ellipsis text-[18px] font-semibold text-dark dark:text-title-dark capitalize">
                           Perfil Profesional
                        </h1>
                     </div>

                     <div class="p-[25px]">
                            <div class="sm:grid sm:grid-cols-12 max-sm:flex max-sm:flex-col gap-[25px]">
                                <div class="col-span-12 md:col-span-6">
                                    <div class="flex flex-col pb-4 md:flex-row">
                                        <label for="field-cedula" class="inline-flex items-center w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">C&eacute;dula</label>
                                        <div class="flex items-center flex-1">
                                            <div class="w-full rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[15px] py-[12px] min-h-[50px] focus:ring-primary focus:border-primary gap-[12px]  flex items-center">
                                                <span class="inline-flex items-center text-sm text-light dark:text-subtitle-dark me-[8px]">
                                                <i class="uil uil-file-bookmark-alt text-[16px]"></i>
                                                </span>
                                                <input type="text" id="field-cedula" name="cedula" class="outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full bg-transparent" placeholder="01234567" maxlength="12" value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <div class="flex flex-col pb-4 md:flex-row">
                                        <label for="select-especialidad" class="inline-flex items-center w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">Especialidad</label>
                                        <div class="flex items-center flex-1">
                                            <div class="w-full">
                                                <select id="select-especialidad" name="especialidad" data-te-select-init data-te-select-filter="true" data-te-select-init data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto" data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none" data-te-class-notch-middle="!border-0 !shadow-none !outline-none" data-te-class-notch-trailing="!border-0 !shadow-none !outline-none">
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <div class="flex flex-col pb-4 md:flex-row">
                                        <label for="field-universidad" class="inline-flex items-center w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">Universidad</label>
                                        <div class="flex items-center flex-1">
                                            <div class="w-full rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[15px] py-[12px] min-h-[50px] focus:ring-primary focus:border-primary gap-[12px]  flex items-center">
                                                <span class="inline-flex items-center text-sm text-light dark:text-subtitle-dark me-[8px]">
                                                <i class="uil uil-university text-[16px]"></i>
                                                </span>
                                                <input type="text" id="field-universidad" name="universidad" class="outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full bg-transparent" placeholder="Nombre de la Universidad" maxlength="250" value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <div class="flex flex-col pb-4 md:flex-row">
                                        <label for="field-universidad-anyo-egreso" class="inline-flex items-center w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">A&ntilde;o de Egreso</label>
                                        <div class="flex items-center flex-1">
                                            <div class="w-full rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[15px] py-[12px] min-h-[50px] focus:ring-primary focus:border-primary gap-[12px]  flex items-center">
                                                <span class="inline-flex items-center text-sm text-light dark:text-subtitle-dark me-[8px]">
                                                <i class="uil uil-graduation-cap text-[16px]"></i>
                                                </span>
                                                <input type="text" id="field-universidad-anyo-egreso" name="universidad_anyo_egreso" class="outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full bg-transparent" placeholder="2026" maxlength="4" value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <div class="flex flex-col pb-4 md:flex-row">
                                        <label for="select-universidad-pais" class="inline-flex items-center w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">País</label>
                                        <div class="flex items-center flex-1">
                                            <div class="w-full">
                                                <select id="select-universidad-pais" name="universidad_pais" data-te-select-init data-te-select-filter="true" data-te-select-init data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto" data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none" data-te-class-notch-middle="!border-0 !shadow-none !outline-none" data-te-class-notch-trailing="!border-0 !shadow-none !outline-none">
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <div class="flex flex-col pb-4 md:flex-row">
                                        <label for="select-universidad-estado" class="inline-flex items-center w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">Estado de Universidad</label>
                                        <div class="flex items-center flex-1">
                                            <div class="w-full">
                                                <select id="select-universidad-estado" name="universidad_estado" data-te-select-init data-te-select-filter="true" data-te-select-init data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto" data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none" data-te-class-notch-middle="!border-0 !shadow-none !outline-none" data-te-class-notch-trailing="!border-0 !shadow-none !outline-none">
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <div class="flex flex-col pb-4 md:flex-row">
                                        <label for="select-universidad-municipio" class="inline-flex items-center w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">Ciudad de Universidad</label>
                                        <div class="flex items-center flex-1">
                                            <div class="w-full">
                                                <select id="select-universidad-municipio" name="universidad_municipio" data-te-select-init data-te-select-filter="true" data-te-select-init data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto" data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none" data-te-class-notch-middle="!border-0 !shadow-none !outline-none" data-te-class-notch-trailing="!border-0 !shadow-none !outline-none">
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>

                     <div class="px-[25px] text-dark dark:text-title-dark font-medium text-[17px] flex flex-wrap items-center justify-between max-sm:flex-col max-sm:h-auto border-b border-regular dark:border-box-dark-up">
                        <h1 class="mb-0 inline-flex items-center py-[16px] overflow-hidden whitespace-nowrap text-ellipsis text-[18px] font-semibold text-dark dark:text-title-dark capitalize">
                           Datos de Nomina
                        </h1>
                     </div>

                     <div class="p-[25px]">
                            <div class="sm:grid sm:grid-cols-12 max-sm:flex max-sm:flex-col gap-[25px]">
                                <div class="col-span-12 md:col-span-6">
                                    <div class="flex flex-col pb-4 md:flex-row">
                                        <label for="field-rfc" class="inline-flex items-center w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">RFC</label>
                                        <div class="flex items-center flex-1">
                                            <div class="w-full rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[15px] py-[12px] min-h-[50px] focus:ring-primary focus:border-primary gap-[12px]  flex items-center">
                                                <span class="inline-flex items-center text-sm text-light dark:text-subtitle-dark me-[8px]">
                                                <i class="uil uil-postcard text-[16px]"></i>
                                                </span>
                                                <input type="text" id="field-rfc" name="rfc" class="outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full bg-transparent" placeholder="HEL8401178H1" maxlength="14" value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-span-12 md:col-span-6">
                                    <div class="flex flex-col pb-4 md:flex-row">
                                        <label for="field-salario-mensual" class="inline-flex items-center w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">Salario Mensual</label>
                                        <div class="flex flex-col flex-1 md:flex-row">
                                            <div class="w-full rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[15px] py-[12px] min-h-[50px] focus:ring-primary focus:border-primary gap-[12px] flex items-center">
                                                <span class="inline-flex items-center text-sm text-light dark:text-subtitle-dark me-[8px]">
                                                <i class="uil uil-dollar-sign text-[16px]"></i>
                                                </span>
                                                <input type="text" id="field-salario-mensual" name="salario_mensual" class="outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full bg-transparent" placeholder="8000.00" maxlength="14" value="">
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

<script src="<?= asset('js/staff/add.js'); ?>"></script>

<script>
    var currentLink = '<?= base_url('staff'); ?>';
</script>