<button id="btn-agregar-lesion" type="button" class="group text-[14px] border-normal font-semibold text-white dark:text-title-dark btn-outlined h-[44px] mb-[14px] dark:border-box-dark-up ps-[10px] pe-[20px] rounded-6 flex items-center gap-[5px] leading-[12px] hover:text-white hover:bg-primary-hbr bg-primary transition duration-300" data-te-ripple-init="" data-te-ripple-color="light">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
    </svg>
    Agregar Lesión
</button>

<fieldset id="fs-agregar-lesion" class="col-span-12 border border-gray-300 rounded-lg p-6 bg-white shadow-sm hidden">
    <legend class="text-[16px] font-semibold text-gray-700 px-2">
        Agregar una nueva lesión
    </legend>
    <div class="sm:grid sm:grid-cols-12 max-sm:flex max-sm:flex-col gap-[12px]">
        <div class="lg:col-span-6">
            <label for="select-tipo-lesion" class="inline-flex items-center w-[178px] mb-2 text-md font-normal text-dark dark:text-title-dark">Tipo de Lesión</label>
            <div class="w-full">
                <select id="select-tipo-lesion" name="tipo_lesion" data-te-select-init data-te-select-filter="true" data-te-select-init data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto" data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none" data-te-class-notch-middle="!border-0 !shadow-none !outline-none" data-te-class-notch-trailing="!border-0 !shadow-none !outline-none" required>
                </select>
            </div>
        </div>
        <div class="lg:col-span-6">
            <label for="select-lesion-pie" class="inline-flex items-center w-[178px] mb-2 text-md font-normal text-dark dark:text-title-dark">Pie Afectado</label>
            <div class="w-full">
                <select id="select-lesion-pie" name="tipo_lesion" data-te-select-init data-te-select-filter="true" data-te-select-init data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto" data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none" data-te-class-notch-middle="!border-0 !shadow-none !outline-none" data-te-class-notch-trailing="!border-0 !shadow-none !outline-none" required>
                </select>
            </div>
        </div>
        <div class="col-span-12">
            <label for="field-lesion-ubicacion" class="inline-flex items-center w-[178px] mb-2 text-md font-normal text-dark dark:text-title-dark">Ubicación:</label>
            <div class="w-full">
                <textarea id="field-lesion-ubicacion" name="lesion_ubicacion" rows="3" class=" rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary resize-none" placeholder="Resumen de diagnostico..."></textarea>
            </div>
        </div>
        <div class="lg:col-span-4">
            <label for="field-lesion-largo" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">Largo (cm):</label>
            <div class="flex flex-col flex-1 md:flex-row">
                <input type="text" id="field-lesion-largo" class=" rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Largo en cm.">
            </div>
        </div>
        <div class="lg:col-span-4">
            <label for="field-lesion-ancho" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">Ancho (cm):</label>
            <div class="flex flex-col flex-1 md:flex-row">
                <input type="text" id="field-lesion-ancho" class=" rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Ancho en cm.">
            </div>
        </div>
        <div class="lg:col-span-4">
            <label for="field-lesion-profundidad" class="inline-flex items-center w-[178px] mb-[10px] text-sm font-medium capitalize text-body dark:text-title-dark">Profundidad (cm):</label>
            <div class="flex flex-col flex-1 md:flex-row">
                <input type="text" id="field-lesion-profundidad" class=" rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" placeholder="Profundidad en cm.">
            </div>
        </div>
        <div class="col-span-6">
            <label for="select-lesion-grado-wagner" class="inline-flex items-center w-[178px] mb-2 text-md font-normal text-dark dark:text-title-dark">Grado Wagner</label>
            <div class="w-full">
                <select id="select-lesion-grado-wagner" name="lesion_grado_wagner" data-te-select-init data-te-select-filter="true" data-te-select-init data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto" data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none" data-te-class-notch-middle="!border-0 !shadow-none !outline-none" data-te-class-notch-trailing="!border-0 !shadow-none !outline-none" required>
                </select>
            </div>
        </div>
        <div class="col-span-6">
            <label for="select-lesion-tipo-tejido" class="inline-flex items-center w-[178px] mb-2 text-md font-normal text-dark dark:text-title-dark">Tejido Observado</label>
            <div class="w-full">
                <select id="select-lesion-tipo-tejido" name="lesion_tipo_tejido" data-te-select-init data-te-select-filter="true" data-te-select-init data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto" data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none" data-te-class-notch-middle="!border-0 !shadow-none !outline-none" data-te-class-notch-trailing="!border-0 !shadow-none !outline-none" required>
                </select>
            </div>
        </div>
        <div class="col-span-6">
            <label for="select-lesion-evolucion" class="inline-flex items-center w-[178px] mb-2 text-md font-normal text-dark dark:text-title-dark">Evolución</label>
            <div class="w-full">
                <select id="select-lesion-evolucion" name="lesion_evolucion" data-te-select-init data-te-select-filter="true" data-te-select-init data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto" data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none" data-te-class-notch-middle="!border-0 !shadow-none !outline-none" data-te-class-notch-trailing="!border-0 !shadow-none !outline-none" required>
                </select>
            </div>
        </div>
        <div class="col-span-6">
            <label for="select-lesion-exudado" class="inline-flex items-center w-[178px] mb-2 text-md font-normal text-dark dark:text-title-dark">Exudado</label>
            <div class="w-full">
                <select id="select-lesion-exudado" name="lesion_exudado" data-te-select-init data-te-select-filter="true" data-te-select-init data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto" data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none" data-te-class-notch-middle="!border-0 !shadow-none !outline-none" data-te-class-notch-trailing="!border-0 !shadow-none !outline-none" required>
                </select>
            </div>
        </div>
        <div class="col-span-6">
            <label for="select-lesion-color-exudado" class="inline-flex items-center w-[178px] mb-2 text-md font-normal text-dark dark:text-title-dark">Color Exudado</label>
            <div class="w-full">
                <select id="select-lesion-color-exudado" name="lesion_color_exudado" data-te-select-init data-te-select-filter="true" data-te-select-init data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto" data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none" data-te-class-notch-middle="!border-0 !shadow-none !outline-none" data-te-class-notch-trailing="!border-0 !shadow-none !outline-none" required>
                </select>
            </div>
        </div>
        <div class="col-span-12">
            <div class="items-center mb-[0.125rem] block min-h-[1.5rem]">
                <input 
                    class="relative ltr:float-left rtl:float-right me-[6px] mt-[0.15rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-1 border-solid border-normal outline-none before:pointer-events-none before:absolute before:h-[10px] before:w-[0.5px] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:mt-0 checked:after:ms-[5px] checked:after:block checked:after:h-[10px] checked:after:w-[5px] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] dark:border-white/10 dark:checked:border-primary dark:checked:bg-primary after:top-[2px]"
                    type="checkbox"
                    value=""
                    id="chk-lesion-signos-infeccion" />
                <label class="inline-block ps-[0.15rem] hover:cursor-pointer" for="chk-lesion-signos-infeccion">
                    Presenta signos de infección
                </label>
            </div>
        </div>
        <div class="col-span-12">
            <label class="inline-flex items-center w-[178px] mb-2 text-md font-normal text-dark dark:text-title-dark">Nivel de Dolor</label>
            <div class="flex items-center gap-[5px]">
                <button data-value="0" class="nivel-dolor bg-primary hover:bg-primary-hbr border-solid border-1 border-primary text-white text-[14px] font-semibold border-none leading-[22px] inline-flex items-center justify-center rounded-[4px] px-[20px] h-[44px] shadow-btn dark:text-title-dark dark:bg-box-dark-up dark:hover:text-dark dark:hover:border-white/10 [&>span]:inline-flex gap-[6px] transition duration-300 ease-in-out">
                    0
                </button>
                <button data-value="1" class="nivel-dolor text-[14px] font-semibold border-none leading-[22px] inline-flex items-center justify-center rounded-[4px] px-[20px] h-[44px] shadow-btn dark:text-title-dark dark:bg-box-dark-up dark:hover:text-dark dark:hover:border-white/10 [&>span]:inline-flex gap-[6px] transition duration-300 ease-in-out">
                    1
                </button>
                <button data-value="2" class="nivel-dolor text-[14px] font-semibold border-none leading-[22px] inline-flex items-center justify-center rounded-[4px] px-[20px] h-[44px] shadow-btn dark:text-title-dark dark:bg-box-dark-up dark:hover:text-dark dark:hover:border-white/10 [&>span]:inline-flex gap-[6px] transition duration-300 ease-in-out">
                    2
                </button>
                <button data-value="3" class="nivel-dolor text-[14px] font-semibold border-none leading-[22px] inline-flex items-center justify-center rounded-[4px] px-[20px] h-[44px] shadow-btn dark:text-title-dark dark:bg-box-dark-up dark:hover:text-dark dark:hover:border-white/10 [&>span]:inline-flex gap-[6px] transition duration-300 ease-in-out">
                    3
                </button>
                <button data-value="4" class="nivel-dolor text-[14px] font-semibold border-none leading-[22px] inline-flex items-center justify-center rounded-[4px] px-[20px] h-[44px] shadow-btn dark:text-title-dark dark:bg-box-dark-up dark:hover:text-dark dark:hover:border-white/10 [&>span]:inline-flex gap-[6px] transition duration-300 ease-in-out">
                    4
                </button>
                <button data-value="5" class="nivel-dolor text-[14px] font-semibold border-none leading-[22px] inline-flex items-center justify-center rounded-[4px] px-[20px] h-[44px] shadow-btn dark:text-title-dark dark:bg-box-dark-up dark:hover:text-dark dark:hover:border-white/10 [&>span]:inline-flex gap-[6px] transition duration-300 ease-in-out">
                    5
                </button>
                <button data-value="6" class="nivel-dolor text-[14px] font-semibold border-none leading-[22px] inline-flex items-center justify-center rounded-[4px] px-[20px] h-[44px] shadow-btn dark:text-title-dark dark:bg-box-dark-up dark:hover:text-dark dark:hover:border-white/10 [&>span]:inline-flex gap-[6px] transition duration-300 ease-in-out">
                    6
                </button>
                <button data-value="7" class="nivel-dolor text-[14px] font-semibold border-none leading-[22px] inline-flex items-center justify-center rounded-[4px] px-[20px] h-[44px] shadow-btn dark:text-title-dark dark:bg-box-dark-up dark:hover:text-dark dark:hover:border-white/10 [&>span]:inline-flex gap-[6px] transition duration-300 ease-in-out">
                    7
                </button>
                <button data-value="8" class="nivel-dolor text-[14px] font-semibold border-none leading-[22px] inline-flex items-center justify-center rounded-[4px] px-[20px] h-[44px] shadow-btn dark:text-title-dark dark:bg-box-dark-up dark:hover:text-dark dark:hover:border-white/10 [&>span]:inline-flex gap-[6px] transition duration-300 ease-in-out">
                    8
                </button>
                <button data-value="9" class="nivel-dolor text-[14px] font-semibold border-none leading-[22px] inline-flex items-center justify-center rounded-[4px] px-[20px] h-[44px] shadow-btn dark:text-title-dark dark:bg-box-dark-up dark:hover:text-dark dark:hover:border-white/10 [&>span]:inline-flex gap-[6px] transition duration-300 ease-in-out">
                    9
                </button>
                <button data-value="10" class="nivel-dolor text-[14px] font-semibold border-none leading-[22px] inline-flex items-center justify-center rounded-[4px] px-[20px] h-[44px] shadow-btn dark:text-title-dark dark:bg-box-dark-up dark:hover:text-dark dark:hover:border-white/10 [&>span]:inline-flex gap-[6px] transition duration-300 ease-in-out">
                    10
                </button>
            </div>
        </div>
        <div class="col-span-12">
            <label for="field-lesion-observaciones" class="inline-flex items-center w-[178px] mb-2 text-md font-normal text-dark dark:text-title-dark">Observaciones:</label>
            <div class="w-full">
                <textarea id="field-lesion-observaciones" name="lesion_observaciones" rows="3" class=" rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary resize-none" placeholder="Observaciones..."></textarea>
            </div>
        </div>
        <div class="col-span-12">
            <div class="flex flex-row-reverse gap-[10px]">
                <button id="btn-agregar-captura-lesion" type="button" class="group text-[14px] border-normal font-semibold text-white dark:text-title-dark btn-outlined h-[44px] mb-[14px] dark:border-box-dark-up ps-[10px] pe-[20px] rounded-6 flex items-center gap-[5px] leading-[12px] hover:text-white hover:bg-primary-hbr bg-primary transition duration-300" data-te-ripple-init="" data-te-ripple-color="light">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                    </svg>
                    Agregar Lesión
                </button>
                <button id="btn-cancelar-captura-lesion" type="button" class="group text-[14px] border-normal font-semibold text-white dark:text-title-dark btn-outlined h-[44px] mb-[14px] dark:border-box-dark-up ps-[10px] pe-[20px] rounded-6 flex items-center gap-[5px] leading-[12px] hover:text-white hover:bg-danger-hbr bg-danger transition duration-300" data-te-ripple-init="" data-te-ripple-color="light">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                    </svg>
                    Cancelar
                </button>
            </div>
        </div>
    </div>
</fieldset>

<div class="sm:grid sm:grid-cols-12 max-sm:flex max-sm:flex-col gap-[12px]">
    <div class="col-span-12 overflow-x-auto">
        <table id="table-lesiones" class="w-full text-sm font-light text-start whitespace-nowrap product-container mt-2">
            <thead>
                <tr>
                    <th class="sticky top-0 capitalize bg-regularBG dark:bg-box-dark-up px-[25px] py-4 text-start text-light dark:text-title-dark text-[14px] font-medium border-none before:hidden rounded-s-[4px]">
                    Lesion</th>
                    <th class="sticky top-0 capitalize bg-regularBG dark:bg-box-dark-up px-4 py-4 text-light dark:text-title-dark text-[14px] font-medium border-none before:hidden">
                    Pie</th>
                    <th class="sticky top-0 capitalize bg-regularBG dark:bg-box-dark-up px-4 py-4 text-light dark:text-title-dark text-[14px] font-medium border-none before:hidden">
                    Ubicación</th>
                    <th class="sticky top-0 capitalize bg-regularBG dark:bg-box-dark-up px-4 py-4 text-light dark:text-title-dark text-[14px] font-medium border-none before:hidden">
                    Medidas</th>
                    <th class="sticky top-0 capitalize bg-regularBG dark:bg-box-dark-up px-4 py-4 text-light dark:text-title-dark text-[14px] font-medium border-none before:hidden">
                    Observaciones</th>
                    <th class="sticky top-0 capitalize bg-regularBG dark:bg-box-dark-up px-[25px] py-2.5 text-end text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden rounded-e-[4px]">
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-box-dark">
            </tbody>
        </table>
    </div>
</div>

<div class="flex flex-row-reverse items-center gap-[15px] mt-[15px] mb-[35px]">
    <button type="button" id="btn-guardar-lesion" class="group text-[14px] border-normal font-semibold text-white dark:text-title-dark btn-outlined h-[44px] dark:border-box-dark-up sm:px-[20px] px-[15px] rounded-6 flex items-center gap-[5px] leading-[22px] hover:text-white hover:bg-primary-hbr bg-primary transition duration-300" data-te-ripple-init="" data-te-ripple-color="light">
        Guardar Cambios
    </button>
</div>

<script>
    window.consultationModules = {
        ...window.consultationModules,
        sore: true
    };
</script>