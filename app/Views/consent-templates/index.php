<?php
    $title = "Plantillas Consentimientos";
    $section = "Plantillas";
    $subsection = "Consentimientos";

    require_once __DIR__.'/../layout/title.php';
    // require_once __DIR__.'/toolbar.php';
?>

<style>
    .ql-snow a {
        color: #000 !important;
    }
</style>

<div class="grid grid-cols-12 gap-[25px] items-start">
    <div class="2xl:col-span-7 col-span-12">
        <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative h-full">
            <div class="px-[25px] text-dark dark:text-title-dark font-medium text-[17px] flex flex-wrap items-center justify-between max-sm:flex-col max-sm:h-auto max-sm:mb-[15px]">
                <h2 class="mb-0 inline-flex items-center py-[16px] max-sm:pb-[5px] overflow-hidden whitespace-nowrap text-ellipsis text-[18px] font-semibold text-dark dark:text-title-dark  capitalize">
                    Plantillas
                </h2>
            </div>
            
            <div class="p-[25px] pt-0">
                <div class="max-h-[320px] scrollbar overflow-y-auto">
                    <div id="as-today" role="tabpanel" data-te-tab-active class="hidden opacity-100 transition-opacity duration-150 ease-linear data-[te-tab-active]:block">
                        <table class="min-w-full text-sm font-light text-left whitespace-nowrap" id="table-templates">
                            <thead>
                                <tr>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-start text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden rounded-s-[4px]">
                                        NOMBRE</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        VERSIÓN</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        ESTATUS</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        CREADO</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        CREADO POR</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-box-dark">
                                
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="text-dark dark:text-title-dark font-medium text-[17px] flex flex-row-reverse flex-wrap items-center justify-between max-sm:flex-col max-sm:h-auto max-sm:mb-[15px] py-2">
                    <div class="flex items-center gap-[10px]">
                        <button id="btn-plantilla-baja" type="button" class="flex items-center px-[20px] text-sm text-white rounded-md font-semibold bg-danger border-danger hover:bg-danger-hbr disabled:text-neutral-600 disabled:bg-lightgray disabled:cursor-not-allowed h-10 gap-[6px] transition-[0.3s]">
                            <i class="uil uil-trash-alt"></i>
                            <span class="m-0">Dar de Baja</span>
                        </button>
                        <button id="btn-plantilla-activar" type="button" class="flex items-center px-[20px] text-sm text-white rounded-md font-semibold bg-dark border-dark hover:bg-dark-hbr disabled:text-neutral-600 disabled:bg-lightgray disabled:cursor-not-allowed h-10 gap-[6px] transition-[0.3s]">
                            <i class="uil uil-toggle-on"></i>
                            <span class="m-0">Activar</span>
                        </button>
                        <button id="btn-plantilla-nueva" type="button" class="flex items-center px-[20px] text-sm text-white rounded-md font-semibold bg-primary border-primary hover:bg-primary-hbr disabled:text-neutral-600 disabled:bg-lightgray disabled:cursor-not-allowed h-10 gap-[6px] transition-[0.3s]">
                            <i class="uil uil-plus"></i>
                            <span class="m-0">Nueva Plantilla</span>
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark  text-[15px] rounded-10">
                <div class="p-[25px]">
                    <div id="accordion-datos-plantillas">
                        <div class="bg-white border rounded-md overflow-hidden border-regular dark:border-box-dark-up dark:bg-box-dark mb-[6px]">
                            <h2 class="mb-0" id="accordion-sector-datos-generales">

                                <button class="group relative flex w-full items-center justify-between border-b border-transparent bg-white px-5 py-[14px] text-body text-left font-normal text-[14px] transition [overflow-anchor:none] hover:z-[2] focus:z-[3] focus:outline-none dark:bg-box-dark-up dark:text-white [&:not([data-te-collapse-collapsed])]:bg-white [&:not([data-te-collapse-collapsed])]:text-primary [&:not([data-te-collapse-collapsed])]:border-regular dark:[&:not([data-te-collapse-collapsed])]:bg-box-dark-up dark:[&:not([data-te-collapse-collapsed])]:text-primary-400 dark:[&:not([data-te-collapse-collapsed])]:border-white/10" type="button" data-te-collapse-init data-te-collapse-collapsed data-te-target="#accordion-datos-generales" aria-expanded="false" aria-controls="accordion-datos-generales">
                                    <span>
                                        <i class="uil uil-file-alt"></i>
                                        Datos Generales de la Plantilla
                                    </span>
                                    <span class="me-[10px] text-[20px] h-5 w-5 shrink-0 rotate-[-180deg] text-current transition-transform duration-200 ease-in-out group-[[data-te-collapse-collapsed]]:rotate-0 group-[[data-te-collapse-collapsed]]:text-[#212529] motion-reduce:transition-none dark:group-[[data-te-collapse-collapsed]]:text-white inline-flex items-center">
                                        <i class="uil uil-angle-down"></i>
                                    </span>
                                </button>

                                
                            </h2>
                            <div id="accordion-datos-generales" class="!visible hidden" data-te-collapse-item aria-labelledby="accordion-sector-datos-generales" data-te-parent="#accordion-datos-plantillas">
                                <form id="form-register-template" action="javascript:RegisterTemplate();">
                                    <div class="ssm:grid ssm:grid-cols-12 max-ssm:flex-col max-ssm:flex gap-[5px] py-2 px-4">
                                        <div class="col-span-12 md:col-span-3 xl:col-span-3">
                                            <label for="field-codigo" class="inline-flex items-center w-[178px] mb-[2px] text-[14px] font-medium capitalize dark:text-title-dark">
                                                Código
                                            </label>
                                            <div class="flex flex-col flex-1 md:flex-row">
                                                <input type="text" id="field-codigo" name="code" class=" rounded-4 border-normal border-1 text-[14px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[6px] min-h-[40px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" 
                                                    placeholder="Código" maxlength="50" required disabled>
                                            </div>
                                        </div>
                                        <div class="col-span-12 md:col-span-6 xl:col-span-6">
                                            <label for="field-nombre" class="inline-flex items-center w-[178px] mb-[2px] text-[14px] font-medium capitalize dark:text-title-dark">
                                                Nombre
                                            </label>
                                            <div class="flex flex-col flex-1 md:flex-row">
                                                <input type="text" id="field-nombre" name="template_name" class=" rounded-4 border-normal border-1 text-[14px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[6px] min-h-[40px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" 
                                                    placeholder="Nombre" maxlength="150" required disabled>
                                            </div>
                                        </div>
                                        <div class="col-span-12 md:col-span-3 xl:col-span-3">
                                            <label for="field-version" class="inline-flex items-center w-[178px] mb-[2px] text-[14px] font-medium capitalize dark:text-title-dark">
                                                Versión
                                            </label>
                                            <div class="flex flex-col flex-1 md:flex-row">
                                                <input type="text" id="field-version" class=" rounded-4 border-normal border-1 text-[14px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[6px] min-h-[40px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" 
                                                    placeholder="Versión" disabled>
                                            </div>
                                        </div>
                                        <div class="col-span-12 md:col-span-4 xl:col-span-4">
                                            <label for="field-estatus" class="inline-flex items-center w-[178px] mb-[2px] text-[14px] font-medium capitalize dark:text-title-dark">
                                                Estatus
                                            </label>
                                            <div class="flex flex-col flex-1 md:flex-row">
                                                <input type="text" id="field-estatus" class=" rounded-4 border-normal border-1 text-[14px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[6px] min-h-[40px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" 
                                                    placeholder="Estatus" disabled>
                                            </div>
                                        </div>
                                        <div class="col-span-12 md:col-span-4 xl:col-span-4">
                                            <label for="field-fecha-registrado" class="inline-flex items-center w-[178px] mb-[2px] text-[14px] font-medium capitalize dark:text-title-dark">
                                                Fecha Registro
                                            </label>
                                            <div class="flex flex-col flex-1 md:flex-row">
                                                <input type="text" id="field-fecha-registrado" class=" rounded-4 border-normal border-1 text-[14px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[6px] min-h-[40px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" 
                                                    placeholder="Registrado" disabled>
                                            </div>
                                        </div>
                                        <div class="col-span-12 md:col-span-4 xl:col-span-4">
                                            <label for="field-fecha-actualizado" class="inline-flex items-center w-[178px] mb-[2px] text-[14px] font-medium capitalize dark:text-title-dark">
                                                Actualizado
                                            </label>
                                            <div class="flex flex-col flex-1 md:flex-row">
                                                <input type="text" id="field-fecha-actualizado" class=" rounded-4 border-normal border-1 text-[14px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[6px] min-h-[40px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" 
                                                    placeholder="Actualizado" disabled>
                                            </div>
                                        </div>
                                        <div class="col-span-12">
                                            <label for="field-registro" class="inline-flex items-center w-[178px] mb-[2px] text-[14px] font-medium capitalize dark:text-title-dark">
                                                Registro
                                            </label>
                                            <div class="flex flex-col flex-1 md:flex-row">
                                                <input type="text" id="field-registro" class="rounded-4 border-normal border-1 text-[14px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[6px] min-h-[40px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" 
                                                    placeholder="Registro" disabled>
                                            </div>
                                        </div>

                                        <div class="col-span-12">
                                            <div class="text-dark dark:text-title-dark font-medium text-[17px] flex flex-row-reverse flex-wrap items-center justify-between max-sm:flex-col max-sm:h-auto max-sm:mb-[15px] py-2">
                                                <div id="sector-new-template-buttons" class="flex items-center gap-[10px]">
                                                    
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="bg-white border rounded-md overflow-hidden border-regular dark:border-box-dark-up dark:bg-box-dark mb-[6px]">
                            <h2 class="mb-0" id="accordion-sector-plantilla">
                                <button class="group relative flex w-full items-center justify-between border-b border-transparent bg-white px-5 py-[14px] text-body text-left font-normal text-[14px] transition [overflow-anchor:none] hover:z-[2] focus:z-[3] focus:outline-none dark:bg-box-dark-up dark:text-white [&:not([data-te-collapse-collapsed])]:bg-white [&:not([data-te-collapse-collapsed])]:text-primary [&:not([data-te-collapse-collapsed])]:border-regular dark:[&:not([data-te-collapse-collapsed])]:bg-box-dark-up dark:[&:not([data-te-collapse-collapsed])]:text-primary-400 dark:[&:not([data-te-collapse-collapsed])]:border-white/10" type="button" data-te-collapse-init data-te-collapse-collapsed data-te-target="#accordion-plantilla" aria-expanded="false" aria-controls="accordion-plantilla">
                                    <span>
                                        <i class="uil uil-cube"></i>
                                        Plantilla
                                    </span>
                                    <span class="me-[10px] text-[20px] h-5 w-5 shrink-0 rotate-[-180deg] text-current transition-transform duration-200 ease-in-out group-[[data-te-collapse-collapsed]]:rotate-0 group-[[data-te-collapse-collapsed]]:text-[#212529] motion-reduce:transition-none dark:group-[[data-te-collapse-collapsed]]:text-white inline-flex items-center">
                                        <i class="uil uil-angle-down"></i>
                                    </span>
                                </button>
                            </h2>
                            <div id="accordion-plantilla" class="!visible hidden" data-te-collapse-item aria-labelledby="accordion-sector-plantilla" data-te-parent="#accordion-datos-plantillas">
                                
                            </div>
                        </div>
                        <div class="bg-white border rounded-lg overflow-hidden border-regular dark:border-box-dark-up dark:bg-box-dark mb-[6px]">
                            <h2 class="mb-0 accordion-header" id="accordion-sector-logotipo">
                                <button class="group relative flex w-full items-center justify-between border-b border-transparent bg-white px-5 py-[14px] text-body text-left font-normal text-[14px] transition [overflow-anchor:none] hover:z-[2] focus:z-[3] focus:outline-none dark:bg-box-dark-up dark:text-white [&:not([data-te-collapse-collapsed])]:bg-white [&:not([data-te-collapse-collapsed])]:text-primary [&:not([data-te-collapse-collapsed])]:border-regular dark:[&:not([data-te-collapse-collapsed])]:bg-box-dark-up dark:[&:not([data-te-collapse-collapsed])]:text-primary-400 dark:[&:not([data-te-collapse-collapsed])]:border-white/10" type="button" data-te-collapse-init data-te-collapse-collapsed data-te-target="#accordion-logotipo" aria-expanded="false" aria-controls="accordion-logotipo">
                                    <span>
                                        <i class="uil uil-scenery"></i>
                                        Logotipo
                                    </span>
                                    <span class="me-[10px] text-[20px] h-5 w-5 shrink-0 rotate-[-180deg] text-current transition-transform duration-200 ease-in-out group-[[data-te-collapse-collapsed]]:rotate-0 group-[[data-te-collapse-collapsed]]:text-[#212529] motion-reduce:transition-none dark:group-[[data-te-collapse-collapsed]]:text-white inline-flex items-center">
                                        <i class="uil uil-angle-down"></i>
                                    </span>
                                </button>
                            </h2>
                            <form id="form-preview-template"
                                    method="POST"
                                    enctype="multipart/form-data"
                                    target="template-preview">
                                <input type="hidden" name="template" id="preview-template">
                                <input type="hidden" name="delta" id="preview-delta">
                                <div id="accordion-logotipo" class="!visible hidden" data-te-collapse-item aria-labelledby="accordion-sector-logotipo" data-te-parent="#accordion-datos-plantillas">
                                    <div class="ssm:grid ssm:grid-cols-12 max-ssm:flex-col max-ssm:flex gap-[5px] py-2 px-4">
                                        <div class="col-span-12">
                                            <label class="flex w-full cursor-pointer overflow-hidden rounded border border-gray-200 bg-white text-sm text-gray-400">
                                                <span id="file-logo-name" class="flex-1 px-4 py-3">
                                                    Logotipo
                                                </span>

                                                <span class="border-l border-gray-200 px-6 py-3 text-gray-500 hover:bg-gray-50">
                                                    Buscar
                                                </span>

                                                <input 
                                                    type="file" 
                                                    name="logo" 
                                                    id="file-logo" 
                                                    class="hidden"
                                                    onchange="document.getElementById('file-logo-name').textContent = this.files[0]?.name || 'Buscar'"
                                                >
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <div class="p-[25px] pt-0">
                <div id="toolbar">
                    <select class="ql-header">
                        <option value="1"></option>
                        <option value="2"></option>
                        <option value="3"></option>
                        <option value="4"></option>
                        <option value="5"></option>
                        <option value="6"></option>
                        <option selected></option>
                    </select>

                    <button class="ql-bold"></button>
                    <button class="ql-italic"></button>
                    <button class="ql-underline"></button>

                    <button class="ql-align" value=""></button>
                    <button class="ql-align" value="center"></button>
                    <button class="ql-align" value="right"></button>
                    <button class="ql-align" value="justify"></button>

                    <div class="flex items-center gap-[15px] max-xs:flex-wrap max-xs:justify-center " data-te-dropdown-ref>
                        <button class="text-[12px] text-body dark:text-subtitle-dark hover:text-primary flex items-center gap-x-[6px]" type="button" id="basic" data-te-dropdown-toggle-ref aria-expanded="false">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-newspaper" viewBox="0 0 16 16">
                                <path d="M0 2.5A1.5 1.5 0 0 1 1.5 1h11A1.5 1.5 0 0 1 14 2.5v10.528c0 .3-.05.654-.238.972h.738a.5.5 0 0 0 .5-.5v-9a.5.5 0 0 1 1 0v9a1.5 1.5 0 0 1-1.5 1.5H1.497A1.497 1.497 0 0 1 0 13.5zM12 14c.37 0 .654-.211.853-.441.092-.106.147-.279.147-.531V2.5a.5.5 0 0 0-.5-.5h-11a.5.5 0 0 0-.5.5v11c0 .278.223.5.497.5z"/>
                                <path d="M2 3h10v2H2zm0 3h4v3H2zm0 4h4v1H2zm0 2h4v1H2zm5-6h2v1H7zm3 0h2v1h-2zM7 8h2v1H7zm3 0h2v1h-2zm-3 2h2v1H7zm3 0h2v1h-2zm-3 2h2v1H7zm3 0h2v1h-2z"/>
                            </svg>
                        </button>
                        <ul class="absolute z-[1000] ltr:float-left rtl:float-right pb-2 m-0 hidden min-w-max list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-left text-base shadow-lg dark:shadow-boxLargeDark dark:bg-box-dark-down [&[data-te-dropdown-show]]:block opacity-100" aria-labelledby="basic" data-te-dropdown-menu-ref>
                            <li>
                                <button class="ql-clinica text-[12px] block px-4 py-1 font-normal capitalize bg-transparent whitespace-nowrap hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark active:no-underline" style="float: none; width: auto! important; padding: 5px 10px;" href="#" data-te-dropdown-item-ref>
                                Clínica</button>
                            </li>
                            <li>
                                <button class="ql-domicilio-clinica text-[12px] block px-4 py-1 font-normal capitalize bg-transparent whitespace-nowrap hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark active:no-underline" style="float: none; width: auto! important; padding: 5px 10px;" href="#" data-te-dropdown-item-ref>
                                Domicilio de la Clínica</button>
                            </li>
                            <li>
                                <button class="ql-telefono-clinica text-[12px] block px-4 py-1 font-normal capitalize bg-transparent whitespace-nowrap hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark active:no-underline" style="float: none; width: auto! important; padding: 5px 10px;" href="#" data-te-dropdown-item-ref>
                                Teléfono de la Clínica</button>
                            </li>
                            <li>
                                <button class="ql-email-clinica text-[12px] block px-4 py-1 font-normal capitalize bg-transparent whitespace-nowrap hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark active:no-underline" style="float: none; width: auto! important; padding: 5px 10px;" href="#" data-te-dropdown-item-ref>
                                E-Mail de la Clínica</button>
                            </li>
                            <li>
                                <button class="ql-paciente-nombre text-[12px] block px-4 py-1 font-normal capitalize bg-transparent whitespace-nowrap hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark active:no-underline" style="float: none; width: auto! important; padding: 5px 10px;" href="#" data-te-dropdown-item-ref>
                                Nombre del Paciente</button>
                            </li>
                            <li>
                                <button class="ql-paciente-edad text-[12px] block px-4 py-1 font-normal capitalize bg-transparent whitespace-nowrap hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark active:no-underline" style="float: none; width: auto! important; padding: 5px 10px;" href="#" data-te-dropdown-item-ref>
                                Edad del Paciente</button>
                            </li>
                            <li>
                                <button class="ql-paciente-fecha-nacimiento text-[12px] block px-4 py-1 font-normal capitalize bg-transparent whitespace-nowrap hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark active:no-underline" style="float: none; width: auto! important; padding: 5px 10px;" href="#" data-te-dropdown-item-ref>
                                Fecha de Nacimiento del Paciente</button>
                            </li>
                            <li>
                                <button class="ql-paciente-sexo text-[12px] block px-4 py-1 font-normal capitalize bg-transparent whitespace-nowrap hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark active:no-underline" style="float: none; width: auto! important; padding: 5px 10px;" href="#" data-te-dropdown-item-ref>
                                Sexo del Paciente</button>
                            </li>
                            <li>
                                <button class="ql-paciente-domicilio text-[12px] block px-4 py-1 font-normal capitalize bg-transparent whitespace-nowrap hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark active:no-underline" style="float: none; width: auto! important; padding: 5px 10px;" href="#" data-te-dropdown-item-ref>
                                Domicilio del Paciente</button>
                            </li>
                            <li>
                                <button class="ql-paciente-telefono text-[12px] block px-4 py-1 font-normal capitalize bg-transparent whitespace-nowrap hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark active:no-underline" style="float: none; width: auto! important; padding: 5px 10px;" href="#" data-te-dropdown-item-ref>
                                Teléfono del Paciente</button>
                            </li>
                            <li>
                                <button class="ql-responsable-nombre text-[12px] block px-4 py-1 font-normal capitalize bg-transparent whitespace-nowrap hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark active:no-underline" style="float: none; width: auto! important; padding: 5px 10px;" href="#" data-te-dropdown-item-ref>
                                Nombre del Responsable</button>
                            </li>
                            <li>
                                <button class="ql-responsable-parentesco text-[12px] block px-4 py-1 font-normal capitalize bg-transparent whitespace-nowrap hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark active:no-underline" style="float: none; width: auto! important; padding: 5px 10px;" href="#" data-te-dropdown-item-ref>
                                Parentesco con Responsable</button>
                            </li>
                            <li>
                                <button class="ql-responsable-telefono text-[12px] block px-4 py-1 font-normal capitalize bg-transparent whitespace-nowrap hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark active:no-underline" style="float: none; width: auto! important; padding: 5px 10px;" href="#" data-te-dropdown-item-ref>
                                Teléfono de Responsable</button>
                            </li>
                            <li>
                                <button class="ql-fecha text-[12px] block px-4 py-1 font-normal capitalize bg-transparent whitespace-nowrap hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark active:no-underline" style="float: none; width: auto! important; padding: 5px 10px;" href="#" data-te-dropdown-item-ref>
                                Fecha</button>
                            </li>
                            <li>
                                <button class="ql-hora text-[12px] block px-4 py-1 font-normal capitalize bg-transparent whitespace-nowrap hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark active:no-underline" style="float: none; width: auto! important; padding: 5px 10px;" href="#" data-te-dropdown-item-ref>
                                Hora</button>
                            </li>
                            <li>
                                <button class="ql-procedimiento text-[12px] block px-4 py-1 font-normal capitalize bg-transparent whitespace-nowrap hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark active:no-underline" style="float: none; width: auto! important; padding: 5px 10px;" href="#" data-te-dropdown-item-ref>
                                Procedimiento</button>
                            </li>
                            <li>
                                <button class="ql-profesional text-[12px] block px-4 py-1 font-normal capitalize bg-transparent whitespace-nowrap hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark active:no-underline" style="float: none; width: auto! important; padding: 5px 10px;" href="#" data-te-dropdown-item-ref>
                                Profesional</button>
                            </li>
                            <li>
                                <button class="ql-cedula-profesional text-[12px] block px-4 py-1 font-normal capitalize bg-transparent whitespace-nowrap hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark active:no-underline" style="float: none; width: auto! important; padding: 5px 10px;" href="#" data-te-dropdown-item-ref>
                                Cedula Profesional</button>
                            </li>
                            <li>
                                <button class="ql-diagnostico text-[12px] block px-4 py-1 font-normal capitalize bg-transparent whitespace-nowrap hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark active:no-underline" style="float: none; width: auto! important; padding: 5px 10px;" href="#" data-te-dropdown-item-ref>
                                Diagnostico</button>
                            </li>
                            <li>
                                <button class="ql-observaciones text-[12px] block px-4 py-1 font-normal capitalize bg-transparent whitespace-nowrap hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark active:no-underline" style="float: none; width: auto! important; padding: 5px 10px;" href="#" data-te-dropdown-item-ref>
                                Observaciones</button>
                            </li>
                            <li>
                                <button class="ql-riesgos text-[12px] block px-4 py-1 font-normal capitalize bg-transparent whitespace-nowrap hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark active:no-underline" style="float: none; width: auto! important; padding: 5px 10px;" href="#" data-te-dropdown-item-ref>
                                Riesgos</button>
                            </li>
                            <li>
                                <button class="ql-beneficios text-[12px] block px-4 py-1 font-normal capitalize bg-transparent whitespace-nowrap hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark active:no-underline" style="float: none; width: auto! important; padding: 5px 10px;" href="#" data-te-dropdown-item-ref>
                                Beneficios</button>
                            </li>
                            <li>
                                <button class="ql-alternativas text-[12px] block px-4 py-1 font-normal capitalize bg-transparent whitespace-nowrap hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark active:no-underline" style="float: none; width: auto! important; padding: 5px 10px;" href="#" data-te-dropdown-item-ref>
                                Alternativas</button>
                            </li>
                            <li>
                                <button class="ql-cuidados-posteriores text-[12px] block px-4 py-1 font-normal capitalize bg-transparent whitespace-nowrap hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark active:no-underline" style="float: none; width: auto! important; padding: 5px 10px;" href="#" data-te-dropdown-item-ref>
                                Cuidados Posteriores</button>
                            </li>
                            <li>
                                <button class="ql-firma-paciente text-[12px] block px-4 py-1 font-normal capitalize bg-transparent whitespace-nowrap hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark active:no-underline" style="float: none; width: auto! important; padding: 5px 10px;" href="#" data-te-dropdown-item-ref>
                                Firma de Paciente</button>
                            </li>
                            <li>
                                <button class="ql-firma-responsable text-[12px] block px-4 py-1 font-normal capitalize bg-transparent whitespace-nowrap hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark active:no-underline" style="float: none; width: auto! important; padding: 5px 10px;" href="#" data-te-dropdown-item-ref>
                                Firma de Responsable</button>
                            </li>
                            <li>
                                <button class="ql-firma-profesional text-[12px] block px-4 py-1 font-normal capitalize bg-transparent whitespace-nowrap hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark active:no-underline" style="float: none; width: auto! important; padding: 5px 10px;" href="#" data-te-dropdown-item-ref>
                                Firma de Profesional</button>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="relative [&>.tox]:border-0 [&>.tox~.tox-menubar]:bg-transparen [&>.ql-toolbar]:py-[15px] border border-regular dark:border-box-dark-up rounded-6">
                    <div id="editor-plantilla" class="h-[200px]"></div>
                </div>

                <div class="text-dark dark:text-title-dark font-medium text-[17px] flex flex-row-reverse flex-wrap items-center justify-between max-sm:flex-col max-sm:h-auto max-sm:mb-[15px] py-2">
                    <div class="flex items-center gap-[10px]">
                        <button id="btn-visualizar-plantilla" type="button" class="flex items-center px-[20px] text-sm text-white rounded-md font-semibold bg-dark border-dark hover:bg-dark-hbr disabled:text-neutral-600 disabled:bg-lightgray disabled:cursor-not-allowed h-10 gap-[6px] transition-[0.3s]" disabled>
                            <i class="uil uil-eye text-[17px]"></i>
                            <span class="m-0">Visualizar</span>
                        </button>
                        <button id="btn-guardar-cambios" type="button" class="!visible hidden flex items-center px-[20px] text-sm text-white rounded-md font-semibold bg-primary border-primary hover:bg-primary-hbr disabled:text-neutral-600 disabled:bg-lightgray disabled:cursor-not-allowed h-10 gap-[6px] transition-[0.3s]">
                            <i class="uil uil-save text-[17px]"></i>
                            <span class="m-0">Guardar Cambios</span>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>

    

    <div class="2xl:col-span-5 col-span-12">
        <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative h-full">
            <div class="px-[25px] text-dark dark:text-title-dark font-medium text-[17px] max-sm:h-auto max-sm:mb-[15px]">
                <div class="mb-0 pt-[15px] pb-[0px] overflow-hidden whitespace-nowrap text-ellipsis text-[18px] font-semibold text-dark dark:text-title-dark  capitalize">
                    Previsualizacion
                </div>
                <div class="text-gray-600 text-[12px] font-normal">Visualiza La Plantilla Con Los Cambios Realizados.</div>
            </div>

            <div class="row g-5">
                <iframe id="template-preview" name="template-preview" width="90%" style="margin: 0 auto; height: 85vh;"></iframe>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('template/assets/vendor_assets/js/quill.js'); ?>"></script>
<script src="<?= asset('js/consent-templates/index.js'); ?>"></script>

<script>
    var currentLink = '<?= base_url('consent-templates'); ?>';
</script>