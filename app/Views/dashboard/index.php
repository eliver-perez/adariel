<?php
    $title = "Dashboard";

    require_once __DIR__.'/../layout/title.php';
?>

<div class="grid grid-cols-12 gap-[25px]">
    <div class="col-span-12 2xl:col-span-3 sm:col-span-6">

        <div bordered="false" class="bg-white dark:bg-box-dark py-[25px] px-[25px] pb-[12px] overflow-hidden rounded-10 relative text-[15px] text-body dak:text-subtitle-dark">
            <div class="flex justify-between">
            <div>
                <span class="font-normal text-body dark:text-subtitle-dark text-15">Total Pagos</span>
                <h4 class="mb-0 text-3xl max-lg:text-[26px] max-sm:text-2xl font-semibold leading-normal text-dark dark:text-title-dark">
                    <div class="flex items-center countCategories" data-number="0">

                        <span>$</span>

                        <span class="countNumber">0</span>

                    </div>
                </h4>
                <div>
                    <span class="inline-flex items-center w-full h-11 rounded-lg gap-[10px]">

                        <span class="flex font-medium gap-[2px] items-center text-sm text-success">
                        <i class="uil uil-arrow-up text-[18px]"></i> 0% </span>
                        <span class="text-sm text-light dark:text-subtitle-dark">que el último mes</span>

                    </span>
                </div>
            </div>
            <div class="absolute bg-primary/10 flex h-full items-center justify-start ltr:-right-[140px] max-md:w-[210px] max-ssm:w-[230px] overflow-hidden px-[30px] rounded-full rtl:-left-[140px] text-primary top-0 w-[230px]">
                <div class="flex items-center justify-center text-primary">
                    <div class="flex items-center text-primary text-[36px]">
                        <i class="uil uil-arrow-growth"></i>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
    <div class="col-span-12 2xl:col-span-3 sm:col-span-6">

        <div bordered="false" class="bg-white dark:bg-box-dark py-[25px] px-[25px] pb-[12px] overflow-hidden rounded-10 relative text-[15px] text-body dak:text-subtitle-dark">
            <div class="flex justify-between">
            <div>
                <span class="font-normal text-body dark:text-subtitle-dark text-15">Total Ventas</span>
                <h4 class="mb-0 text-3xl max-lg:text-[26px] max-sm:text-2xl font-semibold leading-normal text-dark dark:text-title-dark">
                    <div class="flex items-center countCategories" data-number="0">

                        <span>$</span>

                        <span class="countNumber">0</span>

                    </div>
                </h4>
                <div>
                    <span class="inline-flex items-center w-full h-11 rounded-lg gap-[10px]">

                        <span class="flex font-medium gap-[2px] items-center text-sm text-success">
                        <i class="uil uil-arrow-up text-[18px]"></i> 0% </span>
                        <span class="text-sm text-light dark:text-subtitle-dark">que el último mes</span>

                    </span>
                </div>
            </div>
            <div class="absolute bg-info/10 flex h-full items-center justify-start ltr:-right-[140px] max-md:w-[210px] max-ssm:w-[230px] overflow-hidden px-[30px] rounded-full rtl:-left-[140px] text-info top-0 w-[230px]">
                <div class="flex items-center justify-center text-info">
                    <div class="flex items-center text-info text-[36px]">
                        <i class="uil uil-users-alt"></i>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
    <div class="col-span-12 2xl:col-span-3 sm:col-span-6">

        <div bordered="false" class="bg-white dark:bg-box-dark py-[25px] px-[25px] pb-[12px] overflow-hidden rounded-10 relative text-[15px] text-body dak:text-subtitle-dark">
            <div class="flex justify-between">
            <div>
                <span class="font-normal text-body dark:text-subtitle-dark text-15">Cantidad Citas</span>
                <h4 class="mb-0 text-3xl max-lg:text-[26px] max-sm:text-2xl font-semibold leading-normal text-dark dark:text-title-dark">
                    <div class="flex items-center countCategories" data-number="0">

                        <span class="countNumber">0</span>
                    </div>
                </h4>
                <div>
                    <span class="inline-flex items-center w-full h-11 rounded-lg gap-[10px]">

                        <span class="flex font-medium gap-[2px] items-center text-sm text-danger">
                        <i class="uil uil-arrow-down text-[18px]"></i> 0% </span>
                        <span class="text-sm text-light dark:text-subtitle-dark">que el último mes</span>

                    </span>
                </div>
            </div>
            <div class="absolute bg-secondary/10 flex h-full items-center justify-start ltr:-right-[140px] max-md:w-[210px] max-ssm:w-[230px] overflow-hidden px-[30px] rounded-full rtl:-left-[140px] text-secondary top-0 w-[230px]">
                <div class="flex items-center justify-center text-secondary">
                    <div class="flex items-center text-secondary text-[36px]">
                        <i class="uil uil-usd-circle"></i>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
    <div class="col-span-12 2xl:col-span-3 sm:col-span-6">

        <div bordered="false" class="bg-white dark:bg-box-dark py-[25px] px-[25px] pb-[12px] overflow-hidden rounded-10 relative text-[15px] text-body dak:text-subtitle-dark">
            <div class="flex justify-between">
            <div>
                <span class="font-normal text-body dark:text-subtitle-dark text-15">Adeudos</span>
                <h4 class="mb-0 text-3xl max-lg:text-[26px] max-sm:text-2xl font-semibold leading-normal text-dark dark:text-title-dark">
                    <div class="flex items-center countCategories" data-number="0">


                        <span>$</span>

                        <span class="countNumber">0</span>


                    </div>
                </h4>
                <div>
                    <span class="inline-flex items-center w-full h-11 rounded-lg gap-[10px]">

                        <span class="flex font-medium gap-[2px] items-center text-sm text-success">
                        <i class="uil uil-arrow-up text-[18px]"></i> 0% </span>
                        <span class="text-sm text-light dark:text-subtitle-dark">que el último mes</span>

                    </span>
                </div>
            </div>
            <div class="absolute bg-primary/10 flex h-full items-center justify-start ltr:-right-[140px] max-md:w-[210px] max-ssm:w-[230px] overflow-hidden px-[30px] rounded-full rtl:-left-[140px] text-primary top-0 w-[230px]">
                <div class="flex items-center justify-center text-primary">
                    <div class="flex items-center text-primary text-[36px]">
                        <i class="uil uil-tachometer-fast"></i>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
    
    <div class="col-span-12 2xl:col-span-12">
        <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative h-full">
            <div class="px-[25px] text-dark dark:text-title-dark font-medium text-[17px] flex flex-wrap items-center justify-between max-sm:flex-col max-sm:h-auto max-sm:mb-[15px]">
                <h2 class="mb-0 inline-flex items-center py-[16px] max-sm:pb-[5px] overflow-hidden whitespace-nowrap text-ellipsis text-[18px] font-semibold text-dark dark:text-title-dark capitalize">
                    Ventas/Pagos en los últimos 12 meses
                </h2>
            </div>
            <div class="p-[25px] pt-0">
                <div class="flex items-center justify-center max-ssm:flex-col flex-wrap max-ssm:gap-y-[15px]">
                    <div class="relative flex items-center mx-3">
                        <span class="flex items-center text-sm ps-3 text-body dark:text-subtitle-dark before:absolute before:bg-primary before:w-2 before:h-2 before:rounded-full ltr:before:left-0 rtl:before:right-0 before:top-1/2 before:-translate-y-2/4">Ventas</span>
                        <span class="inline-block text-dark dark:text-title-dark me-1 ms-2.5 text-22 font-semibold">$0.00</span><span class="flex items-center text-sm font-medium text-success"><i class="uil uil-arrow-up text-[18px]"></i> 0%
                        </span>
                    </div>
                    <div class="relative flex items-center mx-3">
                        <span class="flex items-center text-sm ps-3 text-body dark:text-subtitle-dark before:absolute before:bg-info before:w-2 before:h-2 before:rounded-full ltr:before:left-0 rtl:before:right-0 before:top-1/2 before:-translate-y-2/4">Pagos</span>
                        <span class="inline-block text-dark dark:text-title-dark me-1 ms-2.5 text-22 font-semibold">$0.00</span><span class="flex items-center text-sm font-medium text-danger"><i class="uil uil-arrow-down text-[18px]"></i>0%
                        </span>
                    </div>
                </div>
                <div dir="ltr" class="graph-sales-payments">
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= asset('js/dashboard/index.js'); ?>"></script>

<script>
    var currentLink = '<?= base_url(''); ?>';
    var callBack = '<?= isset($_GET['callBack']) ? $_GET['callBack'] : ''; ?>';
</script>