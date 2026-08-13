<div class="sm:grid sm:grid-cols-12 max-sm:flex max-sm:flex-col gap-[12px]">
    <div class="lg:col-span-4">
        <div class="w-full">
            <select id="select-search-procedure"
                name="tipo_pie"
                data-te-select-init data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto"
                data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none"
                data-te-class-notch-middle="!border-0 !shadow-none !outline-none"
                data-te-class-notch-trailing="!border-0 !shadow-none !outline-none"
                required>
            </select>
        </div>
    </div>
    <div class="lg:col-span-4">
        <button id="btn-add-procedure" type="button" class="group text-[14px] border-normal font-semibold text-white dark:text-title-dark btn-outlined h-[44px] dark:border-box-dark-up ps-[10px] pe-[20px] rounded-6 flex items-center gap-[5px] leading-[12px] hover:text-white hover:bg-primary-hbr bg-primary transition duration-300" data-te-ripple-init="" data-te-ripple-color="light">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
            </svg>
            Agregar
        </button>
    </div>
</div>    

<table id="table-consultation-procedures" class="min-w-full text-sm font-light text-start whitespace-nowrap product-container mt-2">
    <thead>
        <tr>
            <th class="sticky top-0 capitalize bg-regularBG dark:bg-box-dark-up px-[25px] py-4 text-start text-light dark:text-title-dark text-[14px] font-medium border-none before:hidden rounded-s-[4px]">
            Procedimiento</th>
            <th class="sticky top-0 capitalize bg-regularBG dark:bg-box-dark-up px-4 py-4 text-light dark:text-title-dark text-[14px] font-medium border-none before:hidden">
            Costo</th>
            <th class="sticky top-0 capitalize bg-regularBG dark:bg-box-dark-up px-4 py-4 text-light dark:text-title-dark text-[14px] font-medium border-none before:hidden">
            Cantidad</th>
            <th class="sticky top-0 capitalize bg-regularBG dark:bg-box-dark-up px-4 py-4 text-light dark:text-title-dark text-[14px] font-medium border-none before:hidden">
            Total</th>
            <th class="sticky top-0 capitalize bg-regularBG dark:bg-box-dark-up px-4 py-4 text-light dark:text-title-dark text-[14px] font-medium border-none before:hidden">
            Cobrar</th>
            <th class="sticky top-0 capitalize bg-regularBG dark:bg-box-dark-up px-[25px] py-2.5 text-end text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden rounded-e-[4px]">
            </th>
        </tr>
    </thead>
    <tbody class="bg-white dark:bg-box-dark">
    </tbody>
</table>
<div class="flex flex-row-reverse items-center gap-[15px] mt-[15px] mb-[35px]">
    <button type="button" id="btn-save-procedures" class="group text-[14px] border-normal font-semibold text-white dark:text-title-dark btn-outlined h-[44px] dark:border-box-dark-up sm:px-[20px] px-[15px] rounded-6 flex items-center gap-[5px] leading-[22px] hover:text-white hover:bg-primary-hbr bg-primary transition duration-300" data-te-ripple-init="" data-te-ripple-color="light">
        Guardar Cambios
    </button>
</div>

<script>
    window.consultationModules = {
        ...window.consultationModules,
        procedures: true
    };
</script>