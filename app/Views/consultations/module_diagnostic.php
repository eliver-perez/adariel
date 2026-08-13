<div class="sm:grid sm:grid-cols-12 max-sm:flex max-sm:flex-col gap-[12px]">
    <div class="lg:col-span-4">
        <label for="select-diagnostico" class="inline-flex items-center w-[178px] mb-2 text-md font-normal text-dark dark:text-title-dark">Diagnostico</label>
        <div class="w-full">
            <select id="select-diagnostico"
                name="diagnostico"
                data-te-select-init
                data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto"
                data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none"
                data-te-class-notch-middle="!border-0 !shadow-none !outline-none"
                data-te-class-notch-trailing="!border-0 !shadow-none !outline-none"
                required>
            </select>
        </div>
    </div>
    <div class="lg:col-span-4">
        <label for="select-diagnostico-tipo" class="inline-flex items-center w-[178px] mb-2 text-md font-normal text-dark dark:text-title-dark">Tipo de Diagnostico</label>
        <div class="w-full">
            <select id="select-diagnostico-tipo"
                name="tipo_diagnostico"
                data-te-select-init
                data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto"
                data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none"
                data-te-class-notch-middle="!border-0 !shadow-none !outline-none"
                data-te-class-notch-trailing="!border-0 !shadow-none !outline-none"
                required>
            </select>
        </div>
    </div>
    <div class="lg:col-span-4 inline-flex flex-row gap-[16px] mt-[28px]">
        <button id="btn-agregar-diagnostico" type="button" class="group text-[14px] border-normal font-semibold text-white dark:text-title-dark btn-outlined h-[44px] dark:border-box-dark-up ps-[10px] pe-[20px] rounded-6 flex items-center gap-[5px] leading-[12px] hover:text-white hover:bg-primary-hbr bg-primary transition duration-300" data-te-ripple-init="" data-te-ripple-color="light">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
            </svg>
            Agregar
        </button>
        <button id="btn-new-diagnostic" type="button" class="group text-[14px] border-normal font-semibold text-white dark:text-title-dark btn-outlined h-[44px] dark:border-box-dark-up ps-[10px] pe-[20px] rounded-6 flex items-center gap-[5px] leading-[12px] hover:text-white hover:bg-primary-hbr bg-primary transition duration-300" data-te-ripple-init="" data-te-ripple-color="light">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
            </svg>
            Nuevo
        </button>
    </div>
</div>    

<table id="table-diagnosticos" class="min-w-full text-sm font-light text-start whitespace-nowrap product-container mt-2">
    <thead>
        <tr>
            <th class="sticky top-0 capitalize bg-regularBG dark:bg-box-dark-up px-[25px] py-4 text-start text-light dark:text-title-dark text-[14px] font-medium border-none before:hidden rounded-s-[4px]">
            Diagnostico</th>
            <th class="sticky top-0 capitalize bg-regularBG dark:bg-box-dark-up px-4 py-4 text-light dark:text-title-dark text-[14px] font-medium border-none before:hidden">
            Tipo</th>
            <th class="sticky top-0 capitalize bg-regularBG dark:bg-box-dark-up px-4 py-4 text-light dark:text-title-dark text-[14px] font-medium border-none before:hidden">
            Observaciones</th>
            <th class="sticky top-0 capitalize bg-regularBG dark:bg-box-dark-up px-[25px] py-2.5 text-end text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden rounded-e-[4px]">
            </th>
        </tr>
    </thead>
    <tbody class="bg-white dark:bg-box-dark">
    </tbody>
</table>

<div>
    <label for="field-diagnostico-observaciones" class="inline-flex items-center w-[178px] mb-2 text-md font-normal text-dark dark:text-title-dark">Observaciones:</label>
    <div class="w-full">
        <textarea id="field-diagnostico-observaciones" name="diagnostico_observaciones" rows="3" class=" rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary resize-none" placeholder="Resumen de diagnostico..."></textarea>
    </div>
</div>

<div class="flex flex-row-reverse items-center gap-[15px] mt-[15px] mb-[35px]">
    <button type="button" id="btn-guardar-diagnostico" class="group text-[14px] border-normal font-semibold text-white dark:text-title-dark btn-outlined h-[44px] dark:border-box-dark-up sm:px-[20px] px-[15px] rounded-6 flex items-center gap-[5px] leading-[22px] hover:text-white hover:bg-primary-hbr bg-primary transition duration-300" data-te-ripple-init="" data-te-ripple-color="light">
        Guardar Cambios
    </button>
</div>

<script>
    window.consultationModules = {
        ...window.consultationModules,
        diagnostic: true
    };
</script>