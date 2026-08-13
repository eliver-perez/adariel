<?php
    $title = "Agendar Cita";
    $section = "Agenda";
    $subsection = "Agendar Cita";

    require_once __DIR__.'/../layout/title.php';
?>

            <form id="form-appointment-schedule" no-validate action="javascript:RegisterAppointment()">
               <div class="col-span-12 xl:col-span-6">
                  <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative">
                     <div class="px-[25px] text-dark dark:text-title-dark font-medium text-[17px] flex flex-wrap items-center justify-between max-sm:flex-col max-sm:h-auto border-b border-regular dark:border-box-dark-up">
                        <h1 class="mb-0 inline-flex items-center py-[16px] overflow-hidden whitespace-nowrap text-ellipsis text-[18px] font-semibold text-dark dark:text-title-dark capitalize">
                           Captura los datos para agendar la cita
                        </h1>
                     </div>

                     <div class="p-[25px]">
                        <div class="grid grid-cols-12 gap-[25px] items-start">
                            <div class="col-span-4">
                                <label class="block text-md font-semibold text-dark dark:text-title-dark">
                                    Datos Generales
                                </label>
                                <p class="mt-2 text-md text-gray-500">
                                    Captura los datos generales de la cita.
                                </p>
                            </div>

                            <div class="col-span-8">
                                <div class="grid grid-cols-12 gap-[25px] items-start">
                                    <div class="col-span-6 pr-2">
                                        <label for="select-tipo-cita" class="inline-flex items-center w-[178px] mb-2 text-md font-normal text-dark dark:text-title-dark">Tipo de cita</label>
                                        <div class="w-full">
                                            <select id="select-tipo-cita"
                                                name="tipo_cita"
                                                data-te-select-init 
                                                data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto"
                                                data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none"
                                                data-te-class-notch-middle="!border-0 !shadow-none !outline-none"
                                                data-te-class-notch-trailing="!border-0 !shadow-none !outline-none"
                                                required>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-span-6 pl-2">
                                        <label for="select-como-agendo" class="inline-flex items-center w-[178px] mb-2 text-md font-normal text-dark dark:text-title-dark">¿Como se agendó la cita?</label>
                                        <div class="w-full">
                                            <select id="select-como-agendo"
                                                name="como_agendo"
                                                data-te-select-init 
                                                data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto"
                                                data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none"
                                                data-te-class-notch-middle="!border-0 !shadow-none !outline-none"
                                                data-te-class-notch-trailing="!border-0 !shadow-none !outline-none"
                                                required>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                     <div class="px-[25px] text-dark dark:text-title-dark font-medium text-[17px] flex flex-wrap items-center justify-between max-sm:flex-col max-sm:h-auto border-b border-regular dark:border-box-dark-up">
                        <h1 class="mb-0 inline-flex items-center py-[16px] overflow-hidden whitespace-nowrap text-ellipsis text-[18px] font-semibold text-dark dark:text-title-dark capitalize">
                        
                        </h1>
                     </div>

                     <div class="p-[25px]">
                            <div class="grid grid-cols-12 gap-[25px] items-start">
                                <div class="col-span-4">
                                    <label class="block text-md font-semibold text-dark dark:text-title-dark">
                                        Paciente
                                    </label>
                                    <p class="mt-2 text-md text-gray-500">
                                        Selecciona el paciente al que pertenece la cita.
                                    </p>
                                </div>

                                <!-- Lado derecho -->
                                <div class="col-span-8">
                                    <!-- Input + botones -->
                                    <div class="flex items-center gap-3">
                                        <div id="field-paciente-parent" class="w-[480px] rounded-4 border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up px-[15px] py-[12px] min-h-[42px] flex items-center gap-3">
                                            <input
                                                type="text"
                                                id="field-selected-paciente"
                                                name="paciente"
                                                class="w-full bg-transparent outline-none text-body dark:text-subtitle-dark"
                                                placeholder="Selecciona un paciente"
                                                readonly
                                                value=""
                                            >
                                        </div>

                                        <button
                                            type="button"
                                            id="btn-mostrar-busqueda"
                                            class="h-[42px] px-[18px] border hover:border-[#000] rounded-4 inline-flex items-center justify-center gap-2 whitespace-nowrap focus:border-black focus:ring-1 focus:ring-black transition-all duration-200"
                                        >
                                            <i class="uil uil-search text-[18px]"></i>
                                        </button>

                                        <button
                                            type="button"
                                            id="btn-nuevo-paciente"
                                            class="h-[42px] px-[18px] border hover:border-[#000] rounded-4 inline-flex items-center justify-center gap-2 whitespace-nowrap focus:border-black focus:ring-1 focus:ring-black transition-all duration-200"
                                        >
                                            <i class="uil uil-plus text-[18px]"></i>
                                            Nuevo Paciente
                                        </button>
                                    </div>

                                    <div class="grid grid-cols-12 gap-[5px] mt-2 items-end sector-seleccion-paciente">
                                        <div class="col-span-4">
                                            <label class="block text-lg font-semibold text-dark dark:text-title-dark">
                                                Selecciona un Paciente
                                            </label>
                                        </div>
                                        <div class="col-span-8 flex justify-end">
                                            <div class="w-[328px] rounded-4 border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up px-[15px] py-[12px] min-h-[22px] flex items-center gap-3 flex-reverse">
                                                <input
                                                    type="text"
                                                    id="field-schedule-search-patient"
                                                    name="schedule-search-patient"
                                                    class="w-full bg-transparent outline-none text-body dark:text-subtitle-dark"
                                                    placeholder="Busqueda..."
                                                    value=""
                                                >
                                            </div>
                                        </div>
                                        <div class="col-span-12">
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
                                </div>
                            </div>
                    </div>

                     <div class="px-[25px] text-dark dark:text-title-dark font-medium text-[17px] flex flex-wrap items-center justify-between max-sm:flex-col max-sm:h-auto border-b border-regular dark:border-box-dark-up">
                        <h1 class="mb-0 inline-flex items-center py-[16px] overflow-hidden whitespace-nowrap text-ellipsis text-[18px] font-semibold text-dark dark:text-title-dark capitalize">
                        
                        </h1>
                     </div>

                     <div class="p-[25px]">
                            <div class="grid grid-cols-12 gap-[25px] items-start">
                                <div class="col-span-4">
                                    <label class="block text-md font-semibold text-dark dark:text-title-dark">
                                        Servicios
                                    </label>
                                    <p class="mt-2 text-md text-gray-500">
                                        Selecciona los servicios y personal que atenderá la cita del cliente.
                                    </p>
                                </div>

                                <!-- Lado derecho -->
                                <div class="col-span-8">
                                    <!-- Input + botones -->
                                    <div class="flex items-center gap-3">
                                        <button
                                            type="button"
                                            id="btn-nuevo-servicio"
                                            class="h-[42px] px-[18px] border hover:border-[#000] rounded-4 inline-flex items-center justify-center gap-2 whitespace-nowrap focus:border-black focus:ring-1 focus:ring-black transition-all duration-200"
                                        >
                                            <i class="uil uil-plus text-[18px]"></i>
                                            Nuevo Servicio
                                        </button>
                                    </div>

                                    <div class="grid grid-cols-12 gap-[5px] mt-4  items-end sector-agregar-servicio">
                                        <div class="col-span-6 pr-2">
                                            <label for="select-servicio" class="inline-flex items-center w-[178px] mb-2 text-md font-normal text-dark dark:text-title-dark">Servicio</label>
                                            <div class="w-full">
                                                <select id="select-servicio" name="servicio"
                                                    data-te-select-init
                                                    data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto"
                                                    data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none"
                                                    data-te-class-notch-middle="!border-0 !shadow-none !outline-none"
                                                    data-te-class-notch-trailing="!border-0 !shadow-none !outline-none">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-span-6 pl-2">
                                            <label for="select-servicio-atiende" class="inline-flex items-center w-[178px] mb-2 text-md font-normal text-dark dark:text-title-dark">Quien atiende</label>
                                            <div class="w-full">
                                                <select id="select-servicio-atiende" name="atiende"
                                                    data-te-select-init
                                                    data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto"
                                                    data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none"
                                                    data-te-class-notch-middle="!border-0 !shadow-none !outline-none"
                                                    data-te-class-notch-trailing="!border-0 !shadow-none !outline-none">
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-span-12 flex justify-end mt-2">
                                             <button
                                                type="button"
                                                id="btn-agregar-servicio"
                                                class="h-[42px] px-[18px] bg-dark hover:bg-dark-hbr text-white border hover:border-[#000] rounded-4 inline-flex items-center justify-center gap-2 whitespace-nowrap focus:border-black focus:ring-1 focus:ring-black transition-all duration-200"
                                            >
                                                <i class="uil uil-plus text-[18px]"></i>
                                                Agregar Servicio
                                            </button>
                                        </div>
                                    </div>

                                    <ul class="p-0 mt-2 procedimientos-agregados">
                                        
                                    </ul>

                                    <div class="mt-4 border-b border-regular"></div>
                                    <div class="grid grid-cols-12 gap-[5px] mt-2 items-end">
                                        <div class="col-span-6">
                                            <label class="block text-lg font-semibold text-dark dark:text-title-dark">Total Tiempo: <span id="field-procedimientos-tiempo">0</span> Min</label>
                                        </div>
                                        <div class="col-span-6 flex justify-end">
                                            <label class="block text-lg font-semibold text-dark dark:text-title-dark">Total de la Cita: <span id="field-procedimientos-costo">$0.00</span></label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    </div>

                     <div class="px-[25px] text-dark dark:text-title-dark font-medium text-[17px] flex flex-wrap items-center justify-between max-sm:flex-col max-sm:h-auto border-b border-regular dark:border-box-dark-up">
                        <h1 class="mb-0 inline-flex items-center py-[16px] overflow-hidden whitespace-nowrap text-ellipsis text-[18px] font-semibold text-dark dark:text-title-dark capitalize">
                        
                        </h1>
                     </div>

                     <div class="p-[25px]">
                        <div class="grid grid-cols-12 gap-[25px] items-start">
                            <div class="col-span-4">
                                <label class="block text-md font-semibold text-dark dark:text-title-dark">
                                    Fecha de Cita
                                </label>
                                <p class="mt-2 text-md text-gray-500">
                                    Selecciona la fecha para la cita.
                                </p>
                            </div>

                            <!-- Lado derecho -->
                            <div class="col-span-8">
                                <!-- Input + botones -->
                                <div class="flex items-center flex-1">
                                    <div class="w-full rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[15px] py-[12px] min-h-[50px] focus:ring-primary focus:border-primary gap-[12px]  flex items-center">
                                        <span class="inline-flex items-center text-sm text-light dark:text-subtitle-dark me-[8px]">
                                        <i class="uil uil-schedule text-[16px]"></i>
                                        </span>
                                        <input type="text" id="field-fecha-cita" name="fecha_cita" class="outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full bg-transparent" placeholder="dd/mm/yyyy" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                     <div class="px-[25px] text-dark dark:text-title-dark font-medium text-[17px] flex flex-wrap items-center justify-between max-sm:flex-col max-sm:h-auto border-b border-regular dark:border-box-dark-up">
                        <h1 class="mb-0 inline-flex items-center py-[16px] overflow-hidden whitespace-nowrap text-ellipsis text-[18px] font-semibold text-dark dark:text-title-dark capitalize">
                        
                        </h1>
                     </div>

                     <div class="p-[25px]">
                        <div class="grid grid-cols-12 gap-[25px] items-start">
                            <div class="col-span-4">
                                <label class="block text-md font-semibold text-dark dark:text-title-dark">
                                    Horario
                                </label>
                                <p class="mt-2 text-md text-gray-500">
                                    Horarios disponibles para la fecha y servicios seleccionados.
                                </p>
                            </div>

                            <!-- Lado derecho -->
                            <div class="col-span-8">
                                <div class="grid grid-cols-12 gap-[25px] items-start">
                                    <div class="col-span-12">
                                        <div class="sector-no-horarios">No hay horarios disponibles</div>
                                        <div class="flex items-center gap-3">
                                            <button
                                                type="button"
                                                id="btn-mostrar-horarios"
                                                class="h-[42px] px-[18px] border hover:border-[#000] rounded-4 inline-flex items-center justify-center gap-2 whitespace-nowrap focus:border-black focus:ring-1 focus:ring-black transition-all duration-200"
                                                style="display: none;"
                                            >
                                                <svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 256 256" class="me-1.5 h-5 w-5" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path d="M72,40a8,8,0,0,1,8-8h96a8,8,0,0,1,0,16H80A8,8,0,0,1,72,40Zm159.39,92.94A8,8,0,0,0,224,128H184V72a8,8,0,0,0-8-8H80a8,8,0,0,0-8,8v56H32a8,8,0,0,0-5.66,13.66l96,96a8,8,0,0,0,11.32,0l96-96A8,8,0,0,0,231.39,132.94Z"></path></svg>
                                                Mostrar Horarios Disponibles
                                            </button>
                                        </div>
                                    </div>

                                    <div class="col-span-12 sector-horarios-disponibles hidden">
                                    </div>

                                    
                                    <div class="col-span-12 sector-horario-seleccionado hidden">
                                        <div class="max-3xl:flex flex-col items-center justify-center text-center pt-[45px] px-[25px] rounded-10 bg-white dark:bg-box-dark shadow-[0_5px_20px_rgba(173,181,217,0.03)] md:h-full">
                                            <i class="uil uil-schedule text-[42px]"></i>
                                            <h2 class="text-[30px] font-semibold leading-[36px] text-dark dark:text-title-dark mb-[13px]">Horario Seleccionado</h2>
                                            <p class="mb-[30px] text-[16px] font-semibold text-theme-gray dark:text-subtitle-dark" id="field-horario-seleccionado"></p>
                                        </div>
                                    </div>

                                    <div class="col-span-12 flex flex-col sector-horario-seleccionado gap-[15px] hidden" id="field-horario-seleccionado-detalles">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                     <div class="p-[25px]">
                        <div class="grid grid-cols-12 gap-[25px] items-start">
                            <div class="col-span-4">
                                <label class="block text-md font-semibold text-dark dark:text-title-dark">
                                    Motivo de Consulta
                                </label>
                                <p class="mt-2 text-md text-gray-500">
                                    Breve descripión de lo que pasa al paciente (Opcional).
                                </p>
                            </div>

                            <!-- Lado derecho -->
                            <div class="col-span-8">
                                <label for="field-motivo-consulta" class="block align-top w-[178px] mb-2 text-md font-medium capitalize text-dark dark:text-title-dark">Motivo de Consulta</label>
                                <div class="flex flex-1">
                                    <div class="w-full">
                                        <textarea id="field-motivo-consulta" name="motivo_consulta" rows="5" class=" rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary resize-none" placeholder="Captura observaciones generales del paciente..."></textarea>
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

<script src="<?= asset('js/appointments/add.js'); ?>"></script>

<script>
    var currentLink = '<?= base_url('appointments/add'); ?>';
</script>