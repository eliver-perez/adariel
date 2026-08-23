<div class=" bg-white dark:bg-box-dark rounded-10 xl:px-[60px] px-[30px] xl:pt-[80px] pt-[30px] pb-[30px]">
    <div class="print-body">
        <h1 class="leading-[1.27] text-center font-semibold text-dark dark:text-title-dark mb-[5px] text-[36px]">
            Recibo de Pago
        </h1>
        <div class=" flex items-center md:justify-between justify-center flex-wrap xl:gap-x-[30px] gap-x-[15px] gap-y-[15px] xl:px-[50px] px-[30px] py-[30px] mb-[40px] bg-regularBG dark:bg-box-dark rounded-[20px]">
            <div class="">
                <?= $payment->data->logo != null && $payment->data->logo != '' ? "<img class='max-h-[160px]' src='".config('url')."/media/".$payment->data->logo."' />" : "" ?>
            </div>
            <div class="p-[5px] rounded-10 bg-white dark:bg-box-dark-up flex text-center flex-col justify-center">
                <img class="w-full h-[120px]" src="<?= $qr ?>" alt="QR del recibo">
            </div>
            <address class="not-italic font-normal capitalize text-body dark:text-subtitle-dark text-[15px]">
                <div class="text-[15px] leading-[1.73] font-medium text-dark dark:text-title-dark">
                    Folio: <?= $payment->data->folio; ?>
                </div>
                <div class="text-[15px] leading-[1.73] font-medium text-dark dark:text-title-dark">
                    Fecha: <?= $payment->data->payment_date; ?>
                </div><br />
                <h5 class="text-[15px] leading-[1.6] font-medium text-dark dark:text-title-dark uppercase mb-[4px]">
                    Recibo De:
                </h5>
                <span>
                    <?= $payment->data->client; ?>
                </span><br>
                <span>
                    <span class="text-[15px] leading-[1.73] font-medium text-dark dark:text-title-dark">Metodo de Pago:</span>
                    <?= $payment->data->payment_method; ?>
                </span>
            </address>
        </div>
        <div class="pb-[15px] max-xl:scrollbar max-xl:overflow-x-auto">
            <table class="min-w-full text-sm font-light text-start whitespace-nowrap">
            <thead>
                <tr>
                    <th class="sticky top-0 capitalize bg-regularBG dark:bg-box-dark-up px-[25px] py-4 text-start text-light dark:text-title-dark text-[14px] font-medium border-none before:hidden rounded-s-[4px]">
                        #
                    </th>
                    <th class="sticky top-0 capitalize bg-regularBG dark:bg-box-dark-up px-4 py-4 text-start text-light dark:text-title-dark text-[14px] font-medium border-none before:hidden">
                        Producto
                    </th>
                    <th class="sticky top-0 capitalize bg-regularBG dark:bg-box-dark-up px-4 py-4 text-light dark:text-title-dark text-[14px] font-medium border-none before:hidden">
                        PU
                    </th>
                    <th class="sticky top-0 capitalize bg-regularBG dark:bg-box-dark-up px-4 py-4 text-light dark:text-title-dark text-[14px] font-medium border-none before:hidden text-end">
                        Total
                    </th>
                    <th class="sticky top-0 capitalize bg-regularBG dark:bg-box-dark-up px-[25px] py-2.5 text-end text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden rounded-e-[4px]">
                        Adeudo
                    </th>
                    <th class="sticky top-0 capitalize bg-regularBG dark:bg-box-dark-up px-[25px] py-2.5 text-end text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden rounded-e-[4px]">
                        Pago
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-box-dark">
                <?php
                    foreach($payment->data->details as $pd) { ?>
                        <tr class="group">
                            <td class="border-b border-regular dark:border-box-dark-up xl:px-4 px-1  py-[25px] font-normal text-start capitalize text-[14px] text-dark dark:text-title-dark group-hover:bg-transparent">
                                <?= intval($pd->quantity); ?>
                            </td>
                            <td class="border-b border-regular dark:border-box-dark-up xl:px-4 px-1  py-[23px] font-normal text-start capitalize text-[14px] text-light dark:text-subtitle-dark group-hover:bg-transparent">
                                <h6 class="capitalize mb-[8px] leading-[21px] text-dark dark:text-title-dark text-[15px] font-medium">
                                    <?= $pd->description; ?>
                                </h6>
                            </td>
                            <td class="border-b border-regular dark:border-box-dark-up xl:px-4 px-1  py-[23px] font-normal text-center capitalize text-[14px] text-light dark:text-subtitle-dark group-hover:bg-transparent">
                                <?= '$'.number_format($pd->base_cost, 2); ?>
                            </td>
                            <td class="border-b border-regular dark:border-box-dark-up xl:px-4 px-1  py-[23px] font-normal text-center capitalize text-[14px] text-light dark:text-subtitle-dark group-hover:bg-transparent">
                                <?= '$'.number_format($pd->total, 2); ?>
                            </td>
                            <td class="border-b border-regular dark:border-box-dark-up xl:px-4 ps-1 pe-4 py-[23px] font-normal text-end capitalize group-hover:bg-transparent text-dark dark:text-title-dark">
                                <?= '$'.number_format($pd->balance_before_payment, 2); ?>
                            </td>
                            <td class="border-b border-regular dark:border-box-dark-up xl:px-4 ps-1 pe-4 py-[23px] font-normal text-end capitalize group-hover:bg-transparent text-dark dark:text-title-dark">
                                <?= '$'.number_format($pd->payment_amount, 2); ?>
                            </td>
                        </tr>
                        <?php
                    }
                ?>
                <tr>
                    <td colspan="4" class="ps-[25px] pe-4 py-[23px]">
                    </td>
                    <td class="ps-[25px] pe-4 py-[23px]">
                        <div class="flex flex-col justify-end items-end text-[14px] font-medium text-body dark:text-subtitle-dark leading-[21px] gap-y-[15px] capitalize">
                            <h6 class="font-medium text-dark dark:text-title-dark text-[16px] mt-2">
                                Total :
                            </h6>
                        </div>
                    </td>
                    <td class="ps-[25px] pe-4 py-[23px]">
                        <div class="flex flex-col justify-end items-end text-[14px] font-medium text-body dark:text-title-dark leading-[21px] gap-y-[15px] capitalize">
                            <h5 class="font-medium text-primary text-[18px] mt-2">
                                <?= '$'.number_format($payment->data->amount_payment, 2); ?>
                            </h5>
                        </div>
                    </td>
                </tr>
            </tbody>
            </table>
        </div>
    </div>
</div>

<script src="<?= asset('js/pos/index.js'); ?>"></script>