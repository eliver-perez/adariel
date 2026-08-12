<?php
    $title = "Agregar Usuario";
    $section = "Usuarios";
    $subsection = "Agregar Usuario";

    require_once __DIR__.'/../layout/title.php';
?>

<form id="form-user-add" no-validate action="javascript:RegisterUser()">
    <div class="col-span-12 xl:col-span-6">
        <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative">
            <div class="px-[25px] text-dark dark:text-title-dark font-medium text-[17px] flex flex-wrap items-center justify-between max-sm:flex-col max-sm:h-auto border-b border-regular dark:border-box-dark-up">
            <h1 class="mb-0 inline-flex items-center py-[16px] overflow-hidden whitespace-nowrap text-ellipsis text-[18px] font-semibold text-dark dark:text-title-dark capitalize">
                Datos de Usuario
            </h1>
            </div>
            <div class="p-[25px]">
                <div class="sm:grid sm:grid-cols-12 max-sm:flex max-sm:flex-col gap-[25px]">
                    <div class="col-span-12 md:col-span-6">
                        <div class="flex flex-col pb-4 md:flex-row">
                            <label for="field-name" class="inline-flex items-center w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">Nombre</label>
                            <div class="flex items-center flex-1">
                                <div class="w-full rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[15px] py-[12px] min-h-[50px] focus:ring-primary focus:border-primary gap-[12px]  flex items-center">
                                    <span class="inline-flex items-center text-sm text-light dark:text-subtitle-dark me-[8px]">
                                    <i class="uil uil-user text-[16px]"></i>
                                    </span>
                                    <input type="text" id="field-name" name="field-name" class="outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full bg-transparent" placeholder="Nombre" required maxlength="120" value="" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 md:col-span-6">
                        <div class="flex flex-col pb-4 md:flex-row">
                            <label for="field-email" class="inline-flex items-center w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">Email</label>
                            <div class="flex items-center flex-1">
                                <div class="w-full rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[15px] py-[12px] min-h-[50px] focus:ring-primary focus:border-primary gap-[12px]  flex items-center">
                                    <span class="inline-flex items-center text-sm text-light dark:text-subtitle-dark me-[8px]">
                                    <i class="uil uil-envelope text-[16px]"></i>
                                    </span>
                                    <input type="email" id="field-email" name="field-email" class="outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full bg-transparent" placeholder="example@gmail.com" maxlength="150" value="" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 md:col-span-6">
                        <div class="flex flex-col pb-4 md:flex-row">
                            <label for="field-password" class="inline-flex items-center w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">Password</label>
                            <div class="flex items-center flex-1">
                                <div class="w-full rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[15px] py-[12px] min-h-[50px] focus:ring-primary focus:border-primary gap-[12px]  flex items-center">
                                    <span class="inline-flex items-center text-sm text-light dark:text-subtitle-dark me-[8px]">
                                    <i class="uil uil-user text-[16px]"></i>
                                    </span>
                                    <input type="text" id="field-password" name="field-password" class="outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full bg-transparent" placeholder="Password" value="" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 md:col-span-6">
                        <div class="flex flex-col pb-4 md:flex-row">
                            <label for="select-user-type" class="inline-flex items-center w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">Tipo de Usuario</label>
                            <div class="flex items-center flex-1">
                                <div class="w-full">
                                    <select id="select-user-type" name="select-user-type" data-te-select-init data-te-select-filter="true" data-te-select-init data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto" data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none" data-te-class-notch-middle="!border-0 !shadow-none !outline-none" data-te-class-notch-trailing="!border-0 !shadow-none !outline-none" required>
                                        
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-[25px] text-dark dark:text-title-dark font-medium text-[17px] flex flex-wrap items-center justify-between max-sm:flex-col max-sm:h-auto border-b border-regular dark:border-box-dark-up">
            <h1 class="mb-0 inline-flex items-center py-[16px] overflow-hidden whitespace-nowrap text-ellipsis text-[18px] font-semibold text-dark dark:text-title-dark capitalize">
                Empresa
            </h1>
            </div>

            <div class="p-[25px]">
                <div class="sm:grid sm:grid-cols-12 max-sm:flex max-sm:flex-col gap-[25px]">
                    <div class="col-span-12 md:col-span-6">
                        <div class="flex flex-col pb-4 md:flex-row">
                            <label for="select-organization" class="inline-flex items-center w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">Empresa</label>
                            <div class="flex items-center flex-1">
                                <div class="w-full">
                                    <select id="select-organization" name="select-organization" data-te-select-init data-te-select-filter="true" data-te-select-init data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto" data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none" data-te-class-notch-middle="!border-0 !shadow-none !outline-none" data-te-class-notch-trailing="!border-0 !shadow-none !outline-none">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 md:col-span-6">
                        <div class="flex flex-col pb-4 md:flex-row">
                            <label for="select-branch" class="inline-flex items-center w-[178px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">Sucursal</label>
                            <div class="flex items-center flex-1">
                                <div class="w-full">
                                    <select id="select-branch" name="select-branch" data-te-select-init data-te-select-filter="true" data-te-select-init data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto" data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none" data-te-class-notch-middle="!border-0 !shadow-none !outline-none" data-te-class-notch-trailing="!border-0 !shadow-none !outline-none">
                                    </select>
                                </div>
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

<script src="<?= asset('js/users/add.js'); ?>"></script>

<script>
    var callbackRequest = '<?= isset($_GET['callback']) ? $_GET['callback'] : ''; ?>';
    var currentLink = '<?= base_url('users'); ?>';
</script>