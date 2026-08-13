<div class="sm:grid sm:grid-cols-12 max-sm:flex max-sm:flex-col gap-[25px]">
    <div class="lg:col-span-6">
        <label for="select-tipo-pie" class="inline-flex items-center w-[178px] mb-2 text-md font-normal text-dark dark:text-title-dark">Tipo de pie</label>
        <div class="w-full">
            <select id="select-tipo-pie"
                name="tipo_pie"
                data-te-select-init data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto"
                data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none"
                data-te-class-notch-middle="!border-0 !shadow-none !outline-none"
                data-te-class-notch-trailing="!border-0 !shadow-none !outline-none"
                required>
            </select>
        </div>
    </div>

    <div class="lg:col-span-6">
        <label for="select-formula-metatarsal" class="inline-flex items-center w-[178px] mb-2 text-md font-normal text-dark dark:text-title-dark">Formula Metatarsal</label>
        <div class="w-full">
            <select id="select-formula-metatarsal"
                name="formula_metatarsal"
                data-te-select-init data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto"
                data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none"
                data-te-class-notch-middle="!border-0 !shadow-none !outline-none"
                data-te-class-notch-trailing="!border-0 !shadow-none !outline-none"
                required>
            </select>
        </div>
    </div>

    <div class="col-span-12">
        <label for="field-alteraciones-marcha" class="block align-top w-[178px] mb-2 text-md font-medium capitalize text-dark dark:text-title-dark">Alteraciones de Marcha</label>
        <div class="flex flex-1">
            <div class="w-full">
                <textarea id="field-alteraciones-marcha"
                    name="alteraciones_marcha"
                    rows="5"
                    class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary resize-none"
                    placeholder="Captura si hay alteraciones..."></textarea>
            </div>
        </div>
    </div>

    <fieldset class="col-span-12 border border-gray-300 rounded-lg p-6 bg-white shadow-sm">
        <legend class="text-[16px] font-semibold text-gray-700 px-2">
            Pulso Pedio
        </legend>
        <div class="sm:grid sm:grid-cols-12 max-sm:flex max-sm:flex-col gap-[25px]">
            <div class="lg:col-span-6">
                <label for="select-pulso-pedio-izquierdo" class="inline-flex items-center w-[178px] mb-2 text-md font-normal text-dark dark:text-title-dark">Pie Izquierdo</label>
                <div class="w-full">
                    <select id="select-pulso-pedio-izquierdo"
                        name="pulso_pedio_izquierdo"
                        data-te-select-init data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto"
                        data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none"
                        data-te-class-notch-middle="!border-0 !shadow-none !outline-none"
                        data-te-class-notch-trailing="!border-0 !shadow-none !outline-none"
                        required>
                    </select>
                </div>
            </div>

            <div class="lg:col-span-6">
                <label for="select-pulso-pedio-derecho" class="inline-flex items-center w-[178px] mb-2 text-md font-normal text-dark dark:text-title-dark">Pie Derecho</label>
                <div class="w-full">
                    <select id="select-pulso-pedio-derecho"
                        name="pulso_pedio_derecho"
                        data-te-select-init data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto"
                        data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none"
                        data-te-class-notch-middle="!border-0 !shadow-none !outline-none"
                        data-te-class-notch-trailing="!border-0 !shadow-none !outline-none"
                        required>
                    </select>
                </div>
            </div>
        </div>
    </fieldset>

    <fieldset class="col-span-12 border border-gray-300 rounded-lg p-6 bg-white shadow-sm">
        <legend class="text-[16px] font-semibold text-gray-700 px-2">
            Sensibilidad
        </legend>
        <div class="sm:grid sm:grid-cols-12 max-sm:flex max-sm:flex-col gap-[25px]">
            <div class="lg:col-span-6">
                <label for="select-sensibilidad-izquierdo" class="inline-flex items-center w-[178px] mb-2 text-md font-normal text-dark dark:text-title-dark">Pie Izquierdo</label>
                <div class="w-full">
                    <select id="select-sensibilidad-izquierdo"
                        name="sensibilidad_izquierdo"
                        data-te-select-init data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto"
                        data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none"
                        data-te-class-notch-middle="!border-0 !shadow-none !outline-none"
                        data-te-class-notch-trailing="!border-0 !shadow-none !outline-none"
                        required>
                    </select>
                </div>
            </div>

            <div class="lg:col-span-6">
                <label for="select-sensibilidad-derecho" class="inline-flex items-center w-[178px] mb-2 text-md font-normal text-dark dark:text-title-dark">Pie Derecho</label>
                <div class="w-full">
                    <select id="select-sensibilidad-derecho"
                        name="sensibilidad_derecho"
                        data-te-select-init data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto"
                        data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none"
                        data-te-class-notch-middle="!border-0 !shadow-none !outline-none"
                        data-te-class-notch-trailing="!border-0 !shadow-none !outline-none"
                        required>
                    </select>
                </div>
            </div>
        </div>
    </fieldset>

    <div class="lg:col-span-6">
        <label for="select-temperatura-pies" class="inline-flex items-center w-[178px] mb-2 text-md font-normal text-dark dark:text-title-dark">Temperatura de Pies</label>
        <div class="w-full">
            <select id="select-temperatura-pies"
                name="temperatura_pies"
                data-te-select-init data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto"
                data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none"
                data-te-class-notch-middle="!border-0 !shadow-none !outline-none"
                data-te-class-notch-trailing="!border-0 !shadow-none !outline-none"
                required>
            </select>
        </div>
    </div>

    <div class="lg:col-span-6">
        <label for="select-coloracion-pies" class="inline-flex items-center w-[178px] mb-2 text-md font-normal text-dark dark:text-title-dark">Coloracion Pies</label>
        <div class="w-full">
            <select id="select-coloracion-pies"
                name="coloracion_pies"
                data-te-select-init data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto"
                data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none"
                data-te-class-notch-middle="!border-0 !shadow-none !outline-none"
                data-te-class-notch-trailing="!border-0 !shadow-none !outline-none"
                required>
            </select>
        </div>
    </div>

    <div class="col-span-12">
        <label for="field-exploracion-podologica-observaciones" class="block align-top w-[178px] mb-2 text-md font-medium capitalize text-dark dark:text-title-dark">Observaciones</label>
        <div class="flex flex-1">
            <div class="w-full">
                <textarea id="field-exploracion-podologica-observaciones"
                    name="exploracion_podologica_observaciones"
                    rows="5"
                    class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary resize-none"
                    placeholder="Captura si hay alteraciones..."></textarea>
            </div>
        </div>
    </div>

    <div class="col-span-12">
        <label for="field-exploracion-podologica-recomendaciones" class="block align-top w-[178px] mb-2 text-md font-medium capitalize text-dark dark:text-title-dark">Recomendaciones</label>
        <div class="flex flex-1">
            <div class="w-full">
                <textarea id="field-exploracion-podologica-recomendaciones"
                    name="exploracion_podologica_recomendaciones"
                    rows="5"
                    class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary resize-none"
                    placeholder="Captura si hay recomendaciones..."></textarea>
            </div>
        </div>
    </div>
</div>

<script>
    window.consultationModules = {
        ...window.consultationModules,
        podiatricExploration: true
    };
</script>