<div 
    data-te-modal-init
    class="fixed inset-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
    id="modal-receipt"
    tabindex="-1"
    aria-hidden="true">
    <div class="flex min-h-screen items-center justify-center p-4">
        <div 
            data-te-modal-dialog-ref 
            class="pointer-events-none relative w-full opacity-0 transition-all duration-300 ease-in-out h-70 min-[1280px]:max-w-[1100px]"
            >
            <div class="min-[576px]:shadow-[0_0.5rem_1rem_rgba(#000, 0.15)] pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white h-full bg-clip-padding text-current shadow-lg outline-none dark:bg-neutral-600">
                <div class="flex items-center justify-between flex-shrink-0 p-4 border-b border-opacity-100 rounded-t-md border-regular dark:border-box-dark-up">
                    <h5 class="text-xl font-medium leading-normal text-neutral-800 dark:text-neutral-200" id="modal-receipt-title">
                        Recibo de Pago
                    </h5>
                    <button type="button" class="close-receipt-modal box-content border-none rounded-none hover:no-underline hover:opacity-75 focus:opacity-100 focus:shadow-none focus:outline-none" data-te-modal-dismiss aria-label="Close">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5 text-dark dark:text-title-dark">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <iframe id="receipt-preview"
                        name="receipt-preview"
                        class="w-full"
                        style="margin: 0 auto; height: 85vh;"></iframe>

                <div class="flex flex-wrap items-center justify-end flex-shrink-0 gap-2 p-4 border-t-2 border-b border-opacity-100 rounded-b-md border-regular dark:border-box-dark-up">
                    <button id="btn-print-receipt" class="capitalize border-1 border-regular bg-regular dark:bg-white text-light dark:text-dark bg-transparent text-[14px] font-semibold leading-[22px] inline-flex items-center justify-center rounded-[10px] px-[30px] h-[38px] transition duration-300 ease-in-out gap-[7px] dark:pointer-events-none" data-te-ripple-init data-te-ripple-color="dark">
                        <i class="uil uil-print text-light dark:text-dark "></i>
                        Imprimir
                    </button>
                    <!-- <button class="invoice-btn capitalize border-1 border-regular bg-regular dark:bg-white text-light dark:text-dark bg-transparent text-[14px] font-semibold leading-[22px] inline-flex items-center justify-center rounded-[10px] px-[30px] h-[38px] transition duration-300 ease-in-out gap-[7px]" data-te-ripple-init data-te-ripple-color="dark">
                        <i class="uil uil-message text-light dark:text-dark "></i>
                        invoice
                    </button> -->
                    <button onclick="javascript:ViewReport()" class="capitalize border-1 border-primary bg-primary text-white text-[14px] font-semibold leading-[22px] inline-flex items-center justify-center rounded-[10px] px-[30px] h-[38px] transition duration-300 ease-in-out gap-[7px] dark:pointer-events-none" data-te-ripple-init data-te-ripple-color="light">
                        <i class="uil uil-eye "></i>
                        Ver
                    </button>
                    <button type="button" id="btn-close-payment-report-modal" class="close-receipt-modal ml-1 inline-block rounded bg-danger px-6 pb-2 pt-2.5 text-14 font-medium capitalize leading-normal text-white  transition duration-150 ease-in-out hover:bg-primary-600 hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:bg-primary-600 focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] focus:outline-none focus:ring-0 active:bg-primary-700 active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.3),0_4px_18px_0_rgba(59,113,202,0.2)] dark:shadow-[0_4px_9px_-4px_rgba(59,113,202,0.5)] dark:hover:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:focus:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)] dark:active:shadow-[0_8px_9px_-4px_rgba(59,113,202,0.2),0_4px_18px_0_rgba(59,113,202,0.1)]" data-te-modal-dismiss data-te-ripple-init data-te-ripple-color="light">
                        Cerrar
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>