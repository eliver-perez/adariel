<div 
    data-te-modal-init
    class="fixed inset-0 z-[1055] hidden overflow-y-auto overflow-x-hidden outline-none"
    id="modal-schedule-appointment"
    tabindex="-1"
    aria-hidden="true"
    style="z-index: 99999;">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div 
            data-te-modal-dialog-ref 
            class="pointer-events-none relative opacity-0 transition-all duration-300 ease-in-out min-[1280px]:max-w-[1100px]">
            <div class="appointment-modal shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white h-full bg-clip-padding text-current shadow-lg outline-none dark:bg-neutral-600">
                <div class="flex items-center justify-between flex-shrink-0 p-4 border-b border-opacity-100 rounded-t-md border-regular dark:border-box-dark-up">
                    <h5 class="text-xl font-medium leading-normal text-neutral-800 dark:text-neutral-200" id="modal-schedule-appointment-title">
                        Nueva Cita
                    </h5>
                    <button type="button" class="box-content border-none rounded-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none" data-te-modal-dismiss aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-dark dark:text-title-dark">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="relative flex-auto p-4" data-te-modal-body-ref>
                    <div class="flex flex-col gap-[15px]">
                        <div class="flex flex-row gap-[5px]">
                            <div class="w-full">
                                <label for="select-schedule-type" class="inline-flex items-center mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">Tipo de Cita</label>
                                <div class="flex items-center flex-1">
                                    <div class="w-full">
                                        <select id="select-schedule-type"
                                            name="schedule_type"
                                            autocomplete="off"
                                            data-te-select-init
                                            data-te-select-filter="true"
                                            data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto"
                                            data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none"
                                            data-te-class-notch-middle="!border-0 !shadow-none !outline-none"
                                            data-te-class-notch-trailing="!border-0 !shadow-none !outline-none"
                                            data-te-class-select-dropdown-container="z-[100000]">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="w-full">
                                <label for="select-schedule-booking-type" class="inline-flex items-center mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">¿Como se agendó la cita?</label>
                                <div class="flex items-center flex-1">
                                    <div class="w-full">
                                        <select id="select-schedule-booking-type"
                                            name="schedule-booking-type"
                                            autocomplete="off"
                                            data-te-select-init
                                            data-te-select-filter="true"
                                            data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto"
                                            data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none"
                                            data-te-class-notch-middle="!border-0 !shadow-none !outline-none"
                                            data-te-class-notch-trailing="!border-0 !shadow-none !outline-none"
                                            data-te-class-select-dropdown-container="z-[100000]">
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-full">
                            <label for="field-schedule-patient" class="inline-flex items-center mb-[2px] text-[14px] font-medium capitalize dark:text-title-dark">
                                Paciente
                            </label>
                            <div class="flex flex-col flex-1 md:flex-row">
                                <div id="field-schedule-patient-parent" class="w-full rounded-4 h-[42px]">
                                    <input type="text"
                                            id="field-schedule-patient"
                                            name="schedule_patient"
                                            class="rounded-4 border-normal border-1 text-[14px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[6px] h-[42px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                            placeholder="Paciente"
                                            readonly>
                                </div>
                                <button
                                    type="button"
                                    id="btn-schedule-show-search-patient"
                                    class="h-[42px] px-[18px] border hover:border-[#000] rounded-4 inline-flex items-center justify-center gap-2 whitespace-nowrap focus:border-black focus:ring-1 focus:ring-black transition-all duration-200">
                                    <i class="uil uil-search text-[18px]"></i>
                                </button>

                                <button
                                    type="button"
                                    id="btn-schedule-new-patient"
                                    class="h-[42px] px-[18px] border hover:border-[#000] rounded-4 inline-flex items-center justify-center gap-2 whitespace-nowrap focus:border-black focus:ring-1 focus:ring-black transition-all duration-200">
                                    <i class="uil uil-plus text-[18px]"></i>
                                </button>
                            </div>

                            <fieldset class="sector-schedule-new-patient col-span-12 border border-gray-300 rounded-lg px-6 pt-1 pb-1 bg-white shadow-sm mb-[5px]">
                                <legend class="text-[16px] font-semibold text-gray-700 px-2">
                                    Registrar Paciente
                                </legend>
                                <form id="form-register-patient" no-validate action="javascript:RegisterPatient()">
                                    <div class="grid grid-cols-12 gap-[5px] mt-2 items-end">
                                        <div class="col-span-12 md:col-span-4 xl:col-span-4">
                                            <label for="field-schedule-register-patient-name" class="inline-flex items-center w-[178px] mb-[2px] text-[14px] font-medium capitalize dark:text-title-dark">
                                                Nombre
                                            </label>
                                            <div class="flex flex-col flex-1 md:flex-row">
                                                <input type="text"
                                                        id="field-schedule-register-patient-name"
                                                        name="nombre"
                                                        class="rounded-4 border-normal border-1 text-[14px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[6px] min-h-[40px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" 
                                                        placeholder="Nombre"
                                                        maxlength="60"
                                                        required>
                                            </div>
                                        </div>
                                        <div class="col-span-12 md:col-span-4 xl:col-span-4">
                                            <label for="field-schedule-register-patient-lastname" class="inline-flex items-center w-[178px] mb-[2px] text-[14px] font-medium capitalize dark:text-title-dark">
                                                Apellido Paterno
                                            </label>
                                            <div class="flex flex-col flex-1 md:flex-row">
                                                <input type="text"
                                                        id="field-schedule-register-patient-lastname"
                                                        name="paterno"
                                                        class="rounded-4 border-normal border-1 text-[14px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[6px] min-h-[40px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" 
                                                        placeholder="Apellido Paterno"
                                                        maxlength="60"
                                                        required>
                                            </div>
                                        </div>
                                        <div class="col-span-12 md:col-span-4 xl:col-span-4">
                                            <label for="field-schedule-register-patient-lastname2" class="inline-flex items-center w-[178px] mb-[2px] text-[14px] font-medium capitalize dark:text-title-dark">
                                                Apellido Materno
                                            </label>
                                            <div class="flex flex-col flex-1 md:flex-row">
                                                <input type="text"
                                                        id="field-schedule-register-patient-lastname2"
                                                        name="materno"
                                                        class="rounded-4 border-normal border-1 text-[14px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[6px] min-h-[40px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" 
                                                        placeholder="Apellido Materno"
                                                        maxlength="60">
                                            </div>
                                        </div>
                                        <div class="col-span-12 md:col-span-4 xl:col-span-4">
                                            <label for="field-duration" class="inline-flex items-center w-[178px] mb-[2px] text-[14px] font-medium capitalize dark:text-title-dark">
                                                Fecha de Nacimiento
                                            </label>
                                            <div class="flex items-center flex-1">
                                                <div class="w-full rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[15px] py-[6px] min-h-[40px] focus:ring-primary focus:border-primary gap-[12px]  flex items-center">
                                                    <span class="inline-flex items-center text-sm text-light dark:text-subtitle-dark me-[8px]">
                                                    <i class="uil uil-schedule text-[16px]"></i>
                                                    </span>
                                                    <input type="text"
                                                        id="field-schedule-register-patient-dob"
                                                        name="fecha_nacimiento"
                                                        class="outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full bg-transparent"
                                                        placeholder="dd/mm/yyyy"
                                                        readonly>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-span-12 md:col-span-4 xl:col-span-4">
                                            <label for="field-duration" class="inline-flex items-center w-[178px] mb-[2px] text-[14px] font-medium capitalize dark:text-title-dark">
                                                Teléfono Móvil
                                            </label>
                                            <div class="flex flex-col flex-1 md:flex-row">
                                                <input type="text"
                                                        id="field-schedule-register-patient-phone"
                                                        name="telefono_movil"
                                                        oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                                        class="rounded-4 border-normal border-1 text-[14px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[6px] min-h-[40px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                                        placeholder="Teléfono Móvil"
                                                        maxlength="15">
                                            </div>
                                        </div>
                                        <div class="col-span-12 md:col-span-4 xl:col-span-4">
                                            <label for="select-schedule-gender"
                                                class="inline-flex items-center mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">
                                                Genero
                                            </label>
                                            <div class="flex items-center flex-1">
                                                <div class="w-full">
                                                    <select id="select-schedule-gender"
                                                        name="genero"
                                                        autocomplete="off"
                                                        data-te-select-init
                                                        data-te-select-filter="true"
                                                        data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto"
                                                        data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none"
                                                        data-te-class-notch-middle="!border-0 !shadow-none !outline-none"
                                                        data-te-class-notch-trailing="!border-0 !shadow-none !outline-none"
                                                        data-te-class-select-dropdown-container="z-[100000]">
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-span-12 md:col-span-12 xl:col-span-12">
                                            <label for="field-schedule-register-patient-email" class="inline-flex items-center w-[178px] mb-[2px] text-[14px] font-medium capitalize dark:text-title-dark">
                                                E-Mail
                                            </label>
                                            <div class="flex flex-col flex-1 md:flex-row">
                                                <input type="text"
                                                        id="field-schedule-register-patient-email"
                                                        name="email"
                                                        class="rounded-4 border-normal border-1 text-[14px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[6px] min-h-[40px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" 
                                                        placeholder="E-Mail"
                                                        maxlength="255">
                                            </div>
                                        </div>
                                        <div class="col-span-12 flex flex-row-reverse items-center gap-[5px]">
                                            <button type="submit"
                                                id="btn-schedule-register-patient"
                                                class="px-[30px] h-[34px] mb-[14px] text-white bg-primary border-regular hover:bg-primary-hbr disabled:text-neutral-600 disabled:bg-lightgray disabled:cursor-not-allowed font-medium rounded-4 text-sm w-full sm:w-auto text-center inline-flex items-center justify-center capitalize transition-all duration-300 ease-linear"
                                                data-te-ripple-init=""
                                                data-te-ripple-color="light">
                                                Registrar
                                            </button>
                                            <button type="button"
                                                id="btn-schedule-cancel-patient"
                                                class="px-[30px] h-[34px] mb-[14px] text-white bg-danger border-regular hover:bg-danger-hbr disabled:text-neutral-600 disabled:bg-lightgray disabled:cursor-not-allowed font-medium rounded-4 text-sm w-full sm:w-auto text-center inline-flex items-center justify-center capitalize transition-all duration-300 ease-linear"
                                                data-te-ripple-init=""
                                                data-te-ripple-color="light">
                                                Cancelar
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </fieldset>

                            <fieldset class="sector-schedule-select-patient col-span-12 border border-gray-300 rounded-lg px-6 py-0 bg-white shadow-sm">
                                <legend class="text-[16px] font-semibold text-gray-700 px-2">
                                    Selecciona un Paciente
                                </legend>
                                <div class="grid grid-cols-12 gap-[5px] mt-2 items-end ">
                                    <div class="col-span-4">
                                        
                                    </div>
                                    <div class="col-span-8 flex justify-end">
                                        <div id="" class="w-[328px] rounded-4 border-normal border-1 dark:bg-box-dark-up dark:border-box-dark-up px-[15px] py-[6px] min-h-[22px] flex items-center gap-3 flex-reverse">
                                            <input
                                                type="text"
                                                id="field-busqueda-paciente"
                                                name="busqueda_paciente"
                                                class="w-full bg-transparent outline-none text-body dark:text-subtitle-dark"
                                                placeholder="Busqueda..."
                                                value=""
                                            >
                                        </div>
                                    </div>
                                    <div class="col-span-12">
                                        <table class="min-w-full text-sm font-light text-left whitespace-nowrap" id="table-schedule-patients">
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
                            </fieldset>

                        </div>
                        <div class="flex flex-row gap-[5px]">
                            <div class="w-full">
                                <label for="select-schedule-staff" class="inline-flex items-center mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">Personal</label>
                                <div class="flex items-center flex-1">
                                    <div class="w-full">
                                        <select id="select-schedule-staff"
                                            name="schedule_staff"
                                            autocomplete="off"
                                            data-te-select-init
                                            data-te-select-filter="true"
                                            data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto"
                                            data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none"
                                            data-te-class-notch-middle="!border-0 !shadow-none !outline-none"
                                            data-te-class-notch-trailing="!border-0 !shadow-none !outline-none"
                                            data-te-class-select-dropdown-container="z-[100000]">
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="w-full">
                                <label for="select-schedule-procedure" class="inline-flex items-center mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">Procedimiento</label>
                                <div class="flex items-center flex-1">
                                    <div class="w-full">
                                        <select id="select-schedule-procedure"
                                            name="schedule-procedure"
                                            autocomplete="off"
                                            data-te-select-init
                                            data-te-select-filter="true"
                                            data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto"
                                            data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none"
                                            data-te-class-notch-middle="!border-0 !shadow-none !outline-none"
                                            data-te-class-notch-trailing="!border-0 !shadow-none !outline-none"
                                            data-te-class-select-dropdown-container="z-[100000]">
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="flex flex-row gap-[5px]">
                            <div class="w-full">
                                <label for="field-schedule-register-date" class="inline-flex items-center w-[178px] mb-[2px] text-[14px] font-medium capitalize dark:text-title-dark">
                                    Fecha de Cita
                                </label>
                                <div class="flex items-center flex-1">
                                    <div class="w-full rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[15px] py-[6px] min-h-[40px] focus:ring-primary focus:border-primary gap-[12px]  flex items-center">
                                        <span class="inline-flex items-center text-sm text-light dark:text-subtitle-dark me-[8px]">
                                        <i class="uil uil-schedule text-[16px]"></i>
                                        </span>
                                        <input type="text"
                                            id="field-schedule-register-date"
                                            name="fecha_cita"
                                            class="outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full bg-transparent"
                                            placeholder="dd/mm/yyyy"
                                            readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="w-full">
                                <label for="field-schedule-register-time" class="inline-flex items-center w-[178px] mb-[2px] text-[14px] font-medium capitalize dark:text-title-dark">
                                    Hora de Cita
                                </label>
                                <div class="flex items-center flex-1">
                                    <div class="w-full rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[15px] py-[6px] min-h-[40px] focus:ring-primary focus:border-primary gap-[12px]  flex items-center">
                                        <span class="inline-flex items-center text-sm text-light dark:text-subtitle-dark me-[8px]">
                                        <i class="uil uil-schedule text-[16px]"></i>
                                        </span>
                                        <input type="time"
                                            id="field-schedule-register-time"
                                            name="hora_cita"
                                            class="px-4 block w-full bg-layer border-layer-line rounded-lg sm:text-sm text-foreground placeholder:text-muted-foreground-1 focus:border-primary-focus focus:ring-primary-focus disabled:opacity-50 disabled:pointer-events-none"
                                            step="300"
                                            placeholder="Selecciona la hora de la cita">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="w-full">
                            <label for="field-schedule-register-chief-complaint" class="inline-flex items-center w-[178px] mb-[2px] text-[14px] font-medium capitalize dark:text-title-dark">
                                Motivo Consulta
                            </label>
                                    <textarea id="field-schedule-register-chief-complaint"
                                        name="schedule_register_chief_complaint"
                                        rows="5"
                                        class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary resize-none"
                                        placeholder="Captura motivo de consulta (opcional)"></textarea>
                        </div>
                    </div>
                </div>

                <div class="flex flex-wrap items-center justify-end flex-shrink-0 gap-[5px] p-4 border-t-2 border-b border-opacity-100 rounded-b-md border-regular dark:border-box-dark-up">
                    <button
                        type="button"
                        id="btn-close-schedule-modal"
                        class="ml-1 inline-block rounded bg-danger px-6 pb-2 pt-2.5 text-14 font-medium capitalize leading-normal text-white  transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]"
                        data-te-modal-dismiss
                        data-te-ripple-init
                        data-te-ripple-color="light">
                        Cerrar
                    </button>
                    <button
                        type="button"
                        id="btn-schedule-appointment"
                        class="ml-1 inline-block rounded bg-primary px-6 pb-2 pt-2.5 text-14 font-medium capitalize leading-normal text-white  transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]"
                        data-te-ripple-init
                        data-te-ripple-color="light">
                        Registrar Cita
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>