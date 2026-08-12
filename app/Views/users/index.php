<?php
    $title = "Usuarios";
    $section = "Usuarios";

    require_once __DIR__.'/../layout/title.php';
?>

<div class="col-span-12 2xl:col-span-8">
    <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative h-full">
        <div class="px-[25px] text-dark dark:text-title-dark font-medium text-[17px] flex flex-wrap items-center justify-between max-sm:flex-col max-sm:h-auto max-sm:mb-[15px]">
        <h2 class="mb-0 inline-flex items-center py-[16px] max-sm:pb-[5px] overflow-hidden whitespace-nowrap text-ellipsis text-[18px] font-semibold text-dark dark:text-title-dark  capitalize">
            Usuarios
        </h2>
        <button id="btn-registrar-usuario" type="button" class="flex items-center px-[20px] text-sm text-white rounded-md font-semibold bg-primary border-primary h-10 gap-[6px] transition-[0.3s]">
            <i class="uil uil-plus"></i>
            <span class="m-0">Registrar Usuario</span>
        </button>
        </div>
        
        <div class="p-[25px] pt-0">
        <div class="max-h-[320px] scrollbar overflow-y-auto">
            <div data-te-tab-active class="hidden opacity-100 transition-opacity duration-150 ease-linear data-[te-tab-active]:block">
                <table class="min-w-full text-sm font-light text-left whitespace-nowrap" id="table-users">
                    <thead>
                        <tr>
                            <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-start text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden rounded-s-[4px]">
                                EMAIL</th>
                            <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                NOMBRE</th>
                            <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                EMPRESA</th>
                            <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                TIPO DE USUARIO</th>
                            <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                FECHA REGISTRO</th>
                            <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                ULTIMA CONEXIÓN</th>
                            <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-end text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden rounded-e-[4px]">
                                ACTIVO</th>
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

<script src="<?= asset('js/users/index.js'); ?>"></script>

<script>
    var currentLink = '<?= base_url('users'); ?>';
</script>