<div 
    data-te-modal-init
    class="fixed inset-0 z-[1055] hidden overflow-y-auto overflow-x-hidden outline-none"
    id="modal-procedures-staff"
    tabindex="-1"
    aria-hidden="true">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div 
            data-te-modal-dialog-ref 
            class="pointer-events-none relative opacity-0 transition-all duration-300 ease-in-out min-[1280px]:max-w-[1100px]"
            >
            <form id="form-procedures-staff" no-validate action="javascript:RegisterProcedureStaff()">
                <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white h-full bg-clip-padding text-current shadow-lg outline-none dark:bg-neutral-600">
                    <div class="flex items-center justify-between flex-shrink-0 p-4 border-b border-opacity-100 rounded-t-md border-regular dark:border-box-dark-up">
                        <h5 class="text-xl font-medium leading-normal text-neutral-800 dark:text-neutral-200" id="modal-procedures-staff-title">
                            Personal
                        </h5>
                        <button type="button" class="box-content border-none rounded-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none" data-te-modal-dismiss aria-label="Close">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-dark dark:text-title-dark">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="relative flex-auto p-4" data-te-modal-body-ref>
                        <div class="flex flex-col gap-[15px] w-[480px]">
                            <div class="w-full">
                                <label for="field-procedure-staff-procedure" class="inline-flex items-center mb-[2px] text-[14px] font-medium capitalize dark:text-title-dark">
                                    Servicio / Procedimiento
                                </label>
                                <div class="flex flex-col flex-1 md:flex-row">
                                    <input type="text"
                                            id="field-procedure-staff-procedure"
                                            name="procedure_staff_procedure"
                                            class="rounded-4 border-normal border-1 text-[14px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[6px] min-h-[40px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                            placeholder="Servicio /Procedimiento"
                                            disabled>
                                </div>
                            </div>
                            <div class="w-full">
                                <label for="select-procedure-staff" class="inline-flex items-center mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">Personal</label>
                                <div class="flex items-center flex-1">
                                    <div class="w-full">
                                        <select id="select-procedure-staff"
                                            name="procedure_staff"
                                            autocomplete="off"
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
                            <div class="w-full">
                                <label for="field-procedure-staff-cost" class="inline-flex items-center mb-[2px] text-[14px] font-medium capitalize dark:text-title-dark">
                                    Costo
                                </label>
                                <div class="flex flex-col flex-1 md:flex-row">
                                    <input type="text"
                                            id="field-procedure-staff-cost"
                                            name="procedure_staff_cost"
                                            class="rounded-4 border-normal border-1 text-[14px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[6px] min-h-[40px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                            placeholder="Costo"
                                            maxlength="10">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="flex flex-wrap items-center justify-end flex-shrink-0 gap-[5px] p-4 border-t-2 border-b border-opacity-100 rounded-b-md border-regular dark:border-box-dark-up">
                        <button
                            type="button"
                            id="btn-close-procedures-staff-modal"
                            class="ml-1 inline-block rounded bg-danger px-6 pb-2 pt-2.5 text-14 font-medium capitalize leading-normal text-white  transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]"
                            data-te-modal-dismiss
                            data-te-ripple-init
                            data-te-ripple-color="light">
                            Cerrar
                        </button>
                        <button
                            type="submit"
                            id="btn-register-procedures-staff"
                            class="ml-1 inline-block rounded bg-primary px-6 pb-2 pt-2.5 text-14 font-medium capitalize leading-normal text-white  transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]"
                            data-te-ripple-init
                            data-te-ripple-color="light">
                            Asociar Personal
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>