<div data-te-modal-init
     id="e-info-modal"
     tabindex="-1"
     aria-hidden="true"
     class="fixed inset-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
     style="z-index: 99999;">

     <div class="flex min-h-screen items-center justify-center p-4">

        <div data-te-modal-dialog-ref
        class="pointer-events-none relative w-full max-w-[500px] opacity-0 transition-all duration-300 ease-in-out
                min-[576px]:max-w-[500px]">

            <div class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-lg outline-none dark:bg-box-dark">

                <div class="flex items-center justify-between rounded-t-md bg-primary px-6 py-4">
                    <div class="flex items-center justify-end gap-[10px]">
                        <h5 class="e-info-title text-[16px] font-semibold text-white">
                            Título
                        </h5>
                        <button type="button"
                                class="text-white hover:opacity-75">
                            <i class="uil uil-edit"></i>
                        </button>
                        <button type="button"
                                class="text-white hover:opacity-75">
                            <i class="uil uil-message"></i>
                        </button>
                        <button type="button"
                                id="btn-appointment-cancel"
                                class="text-white hover:opacity-75">
                            <i class="uil uil-trash-alt"></i>
                        </button>
                    </div>

                        <button type="button"
                                data-te-modal-dismiss
                                class="text-white hover:opacity-75 appointment-modal-close">
                            <i class="uil uil-times"></i>
                        </button>
                </div>

                <div class="px-6 py-3">
                    <div class="flex items-center justify-between rounded-t-md py-1">
                        <div>
                            <span class="font-medium"><i class="uil uil-atom"></i> </span>
                            <span class="e-info-age font-medium text-dark"></span>
                        </div>
                        <div>
                            <span class="font-medium"><i class="uil uil-schedule"></i> </span>
                            <span class="e-info-dob font-medium text-dark"></span>
                        </div>
                        <div>
                            <span class="font-medium"><i class="uil uil-qrcode-scan"></i> </span>
                            <span class="e-info-patient-code font-medium text-dark"></span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between rounded-t-md py-1 mb-2">
                        <div>
                            <span class="font-medium"><i class="uil uil-envelope"></i> </span>
                            <span class="e-info-email font-medium text-dark"></span>
                        </div>
                        <div>
                            <span class="font-medium"><i class="uil uil-mobile-android"></i> </span>
                            <span class="e-info-phone font-medium text-dark"></span>
                        </div>
                    </div>
                    <ul class="space-y-4 text-[14px] text-body dark:text-subtitle-dark">
                        <li>
                            <span class="font-medium"><i class="uil uil-schedule"></i> </span>
                            <span class="e-info-date font-medium text-dark"></span>
                        </li>

                        <li>
                            <span class="font-medium"><i class="uil uil-clock"></i> </span>
                            <span class="e-info-time font-medium text-dark"></span>
                        </li>

                        <li>
                            <span class="font-medium"><i class="uil uil-align"></i> </span>
                            <span class="e-info-description font-medium"></span>
                        </li>
                    </ul>
                </div>

                <div class="flex items-center justify-between rounded-t-md px-6 gap-[10px] mt-[14px] pe-4 pt-2 bg-[#f0f0f0] border-t border-gray-300">
                    <div class="flex items-center">
                        <button type="button"
                            title="Firmar Consentimiento"
                            class="text-dark text-[1.3rem] h-[34px] mb-[10px] hover:opacity-75">
                            <i class="uil uil-file-edit-alt"></i>
                        </button>
                    </div>

                    <div id="sec-appointment-bottom-right-side" class="flex flex-row-reverse items-center">
                        <div class="flex flex-row-reverse items-center sec-check-in">
                            <button type="button" id="btn-appointment-check-in" class="px-[30px] h-[34px] mb-[10px] text-white bg-primary border-primary hover:bg-primary-hbr font-medium rounded-4 text-xs xs:w-auto text-center inline-flex items-center justify-center capitalize transition-all duration-300 ease-linear" data-te-ripple-init="" data-te-ripple-color="light">Check-In</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>