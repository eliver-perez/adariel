<?php
    $title = "Ajustes";
    $section = "Ajustes";

    require_once __DIR__.'/../layout/title.php';
?>

<div class="grid grid-cols-12 sm:gap-[25px] gap-y-[25px]">
    <div class="col-span-12 2xl:col-span-3">
        <div class="bg-white dark:bg-box-dark rounded-[10px] text-center">
            <div class="px-[50px] pt-[25px] pb-[18px]">
                <div class="inline-block text-center">
                    <img class="relative mb-0"
                        src="<?= asset('images/logos/logo_v.png'); ?>"
                        alt="Logo">
                </div>
                <!-- <h3 class="mt-[28px] text-[18px] mb-[6px] font-medium text-dark dark:text-title-dark leading-[23px] hover:[&>a]:text-primary">
                    <label class="text-dark dark:text-title-dark" ><?= config('name'); ?></label>
                </h3> -->
            </div>
            <div class="border-t border-regular dark:border-box-dark-up">
            <nav class="px-[20px] pt-8 pb-5">
                <ul class="listItemActive" role="tablist" data-te-nav-ref>
                    <li role="presentation">
                        <a href="#tabs-accountSettings" data-te-toggle="pill" data-te-target="#tabs-accountSettings" role="tab" aria-controls="tabs-accountSettings" aria-selected="true" class="[&.active]:bg-primary/10 [&.active]:text-primary bg-transparent cursor-pointer dark:text-subtitle-dark duration-300 flex font-normal gap-[12px] items-center px-[10px] [&.active]:px-[20px] hover:px-[20px] py-[10px] rounded-[6px] text-[14px] text-light transition-[0.3s]">
                        <i class="uil uil-setting text-[18px]"></i>
                        <span>Ajustes Generales</span>
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#tabs-passwordSettings" data-te-toggle="pill" data-te-target="#tabs-passwordSettings" role="tab" aria-controls="tabs-passwordSettings" aria-selected="false" class="[&.active]:bg-primary/10 [&.active]:text-primary bg-transparent cursor-pointer dark:text-subtitle-dark duration-300 flex font-normal gap-[12px] items-center px-[10px] [&.active]:px-[20px] hover:px-[20px] hover:bg-primary/10 hover:text-primary py-[10px] rounded-[6px] text-[14px] text-light transition-[0.3s]">
                        <i class="uil uil-key-skeleton text-[18px]"></i>
                        <span>Cambio de Contraseñas</span>
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#tabs-socialProfile" data-te-toggle="pill" data-te-target="#tabs-socialProfile" role="tab" aria-controls="tabs-socialProfile" aria-selected="false" class="[&.active]:bg-primary/10 [&.active]:text-primary bg-transparent cursor-pointer dark:text-subtitle-dark duration-300 flex font-normal gap-[12px] items-center px-[10px] [&.active]:px-[20px] hover:px-[20px] hover:bg-primary/10 hover:text-primary py-[10px] rounded-[6px] text-[14px] text-light transition-[0.3s] active" data-te-nav-active>
                        <i class="uil uil-whatsapp text-[18px]"></i>
                        <span>WhatsaApp</span>
                        </a>
                    </li>
                    <li role="presentation">
                        <a href="#tabs-notification" data-te-toggle="pill" data-te-target="#tabs-notification" role="tab" aria-controls="tabs-notification" aria-selected="false" class="[&.active]:bg-primary/10 [&.active]:text-primary bg-transparent cursor-pointer dark:text-subtitle-dark duration-300 flex font-normal gap-[12px] items-center px-[10px] [&.active]:px-[20px] hover:px-[20px] hover:bg-primary/10 hover:text-primary py-[10px] rounded-[6px] text-[14px] text-light transition-[0.3s]">
                        <i class="uil uil-bell text-[18px]"></i>
                        <span>Notificaciones</span>
                        </a>
                    </li>
                </ul>
            </nav>
            </div>
        </div>
    </div>
    <div class="col-span-12 2xl:col-span-9">
        <!-- sector -->
        <div class="hidden opacity-100 transition-opacity duration-150 ease-linear data-[te-tab-active]:block" id="tabs-accountSettings" role="tabpanel" aria-labelledby="tabs-accountSettings-tab">
            <div class="bg-white dark:bg-box-dark rounded-10">
            <div class="py-[18px] px-[25px] text-dark dark:text-title-dark font-medium text-[17px] border-regular dark:border-box-dark-up border-b">
                <h1 class="mb-0 text-lg font-medium text-dark dark:text-title-dark">Ajustes Generales</h1>
                <span class="mb-0.5 text-light dark:text-subtitle-dark text-13 font-normal">Controla los ajustes de la plataforma</span>
            </div>
            <div class="px-[25px] pb-[50px] pt-[40px]">
                <div class="grid grid-cols-12 sm:gap-[25px] gap-y-[25px] content-center">
                </div>
            </div>
            </div>
        </div>
        <!-- end sector -->
        <!-- sector -->
        <div class="hidden opacity-100 transition-opacity duration-150 ease-linear data-[te-tab-active]:block" id="tabs-passwordSettings" role="tabpanel" aria-labelledby="tabs-passwordSettings-tab">
            <div class="bg-white dark:bg-box-dark rounded-10">
            <div class="py-[18px] px-[25px] text-dark dark:text-title-dark font-medium text-[17px] border-regular dark:border-box-dark-up border-b">
                <h1 class="mb-0 text-lg font-medium text-dark dark:text-title-dark">Cambio de Contraseña</h1>
                <span class="mb-0.5 text-light dark:text-subtitle-dark text-13 font-normal">Modifica tu contraseña para acceder a la plataforma</span>
            </div>
            <div class="px-[25px] pb-[50px] pt-[40px]">
                <div class="grid grid-cols-12 sm:gap-[25px] gap-y-[25px] content-center">
                    <div class="col-span-12 xl:col-start-4 xl:col-span-6">
                        <form>
                        <div class="mb-6">
                            <label for="oldPassword" class="block mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">Password Anterior:</label>
                            <input type="text" id="oldPassword" class="w-full rounded-6 border-regular border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary" placeholder="old password" autocomplete="off" required>
                        </div>
                        <div class="mb-6">
                            <label for="password" class="block mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">Nuevo Password:</label>
                            <div class="relative w-full">
                                <div class="absolute inset-y-0 end-0 flex items-center px-[15px]">
                                    <input class="hidden js-password-toggle" id="toggle" type="checkbox" autocompleted="">
                                    <label class=" rounded cursor-pointer text-light text-[15px] js-password-label dark:text-subtitle-dark" for="toggle"><i class="uil uil-eye-slash"></i></label>
                                </div>
                                <input type="password"
                                        id="password"
                                        class="js-password w-full rounded-6 border-regular border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] min-h-[50px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark focus:ring-primary focus:border-primary"
                                        placeholder="new password"
                                        value=""
                                        autocomplete="off"
                                        required>
                            </div>
                            <p class="mt-[14px] text-light dark:text-subtitle-dark text-[13px]">
                                Mínimo 8 caracteres</p>
                        </div>
                        </form>
                        <div class="static flex flex-wrap items-center gap-[10px] sm:mt-[43px] mt-[27] ">
                        <button type="button" class="group text-[13px] border-normal font-semibold text-white dark:text-title-dark btn-outlined h-[37px] dark:border-box-dark-up sm:px-[20px] px-[15px] rounded-6 flex items-center gap-[5px] leading-[22px] hover:text-white hover:bg-primary-hbr bg-primary transition duration-300" data-te-ripple-init="" data-te-ripple-color="light">
                            Modificar Contraseña
                        </button>
                        <button type="button" class="group text-[13px] font-semibold text-theme-gray bg-normalBG dark:bg-box-dark-up dark:text-title-dark btn-outlined h-[37px] dark:border-box-dark-up sm:px-[20px] px-[15px] rounded-6 flex items-center gap-[5px] leading-[22px] hover:text-white hover:bg-dark transition duration-300 border-1 border-normal" data-te-ripple-init="" data-te-ripple-color="light">
                            Cancelar
                        </button>

                        </div>
                    </div>
                </div>
            </div>
            </div>
        </div>
        <!-- end sector -->
        <!-- sector -->
        <div class="hidden opacity-100 transition-opacity duration-150 ease-linear data-[te-tab-active]:block" id="tabs-socialProfile" role="tabpanel" aria-labelledby="tabs-socialProfile-tab" data-te-tab-active>
            <div class="bg-white dark:bg-box-dark rounded-10">
            <div class="py-[18px] px-[25px] text-dark dark:text-title-dark font-medium text-[17px] border-regular dark:border-box-dark-up border-b">
                <h1 class="mb-0 text-lg font-medium text-dark dark:text-title-dark">Integración WhatsApp</h1>
                <span class="mb-0.5 text-light dark:text-subtitle-dark text-13 font-normal">Integra WhatsApp para enviar notificaciones a tus clientes</span>
            </div>
            <div class="px-[25px] pb-[50px] pt-[40px]">




                        <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark  text-[15px] rounded-10">
                            <div class="p-[25px]">
                                <div id="accordion-whatsapp-integrations">
                                    <div class="bg-white border rounded-md overflow-hidden border-regular dark:border-box-dark-up dark:bg-box-dark mb-[6px]">
                                        <h2 class="mb-0" id="acoordion-integracion-meta">

                                            <button class="group relative flex w-full items-center justify-between border-b border-transparent bg-white px-5 py-[14px] text-body text-left font-normal text-[14px] transition [overflow-anchor:none] hover:z-[2] focus:z-[3] focus:outline-none dark:bg-box-dark-up dark:text-white [&:not([data-te-collapse-collapsed])]:bg-white [&:not([data-te-collapse-collapsed])]:text-primary [&:not([data-te-collapse-collapsed])]:border-regular dark:[&:not([data-te-collapse-collapsed])]:bg-box-dark-up dark:[&:not([data-te-collapse-collapsed])]:text-primary-400 dark:[&:not([data-te-collapse-collapsed])]:border-white/10"
                                                    type="button"
                                                    data-te-collapse-init
                                                    data-te-target="#accordion-whatsapp-integration-meta"
                                                    aria-expanded="true"
                                                    aria-controls="accordion-whatsapp-integration-meta">
                                                <span>
                                                     <i class="inline-flex align-middle"><svg fill="#000000"
                                                                                            width="18px"
                                                                                            height="18px"
                                                                                            viewBox="0 0 32 32"
                                                                                            version="1.1"
                                                                                            xml:space="preserve"
                                                                                            xmlns="http://www.w3.org/2000/svg"
                                                                                            xmlns:xlink="http://www.w3.org/1999/xlink">
                                                                                            <path d="M5,19.5c0-4.6,2.3-9.4,5-9.4c1.5,0,2.7,0.9,4.6,3.6c-1.8,2.8-2.9,4.5-2.9,4.5c-2.4,3.8-3.2,4.6-4.5,4.6  C5.9,22.9,5,21.7,5,19.5 M20.7,17.8L19,15c-0.4-0.7-0.9-1.4-1.3-2c1.5-2.3,2.7-3.5,4.2-3.5c3,0,5.4,4.5,5.4,10.1  c0,2.1-0.7,3.3-2.1,3.3S23.3,22,20.7,17.8 M16.4,11c-2.2-2.9-4.1-4-6.3-4C5.5,7,2,13.1,2,19.5c0,4,1.9,6.5,5.1,6.5  c2.3,0,3.9-1.1,6.9-6.3c0,0,1.2-2.2,2.1-3.7c0.3,0.5,0.6,1,0.9,1.6l1.4,2.4c2.7,4.6,4.2,6.1,6.9,6.1c3.1,0,4.8-2.6,4.8-6.7  C30,12.6,26.4,7,22.1,7C19.8,7,18,8.8,16.4,11"/>
                                                                                        </svg>
                                                                                    </i>
                                                    Meta WhatsApp Cloud API
                                                </span>
                                                <span class="me-[10px] text-[20px] h-5 w-5 shrink-0 rotate-[-180deg] text-current transition-transform duration-200 ease-in-out group-[[data-te-collapse-collapsed]]:rotate-0 group-[[data-te-collapse-collapsed]]:text-[#212529] motion-reduce:transition-none dark:group-[[data-te-collapse-collapsed]]:text-white inline-flex items-center">
                                                    <i class="uil uil-angle-down"></i>
                                                </span>
                                            </button>

                                            
                                        </h2>
                                        <div id="accordion-whatsapp-integration-meta" class="!visible" data-te-collapse-item aria-labelledby="acoordion-integracion-meta" data-te-parent="#accordion-whatsapp-integrations" data-te-collapse-item data-te-collapse-show>
                                            <div class="bg-regularBG dark:bg-box-dark-up px-[25px] pb-[25px] pt-[15px] max-sm:px-[15px] rounded-[10px]">
                                                <form id="form-meta-integration" action="javascript:RegisterMetaCloudAPI()">
                                                    <div class="flex items-center justify-between h-[50px]">
                                                        <h2 class="text-light dark:text-white/60 text-[15px] font-medium">Integracion de Meta WhatsApp Cloud API</h2>
                                                    </div>
                                                    <div class="bg-white dark:bg-box-dark shadow-[0_5px_20px_rgba(173,181,217,0.05)] rounded-[10px]">
                                                        <ul>
                                                            <li class="flex items-center justify-between mb-0 px-[25px] py-[20px] border-b border-regular last:border-none dark:border-box-dark-up gap-[15px]">

                                                                <div>
                                                                    <h4 class="mb-0.5 text-body dark:text-title-dark text-sm font-medium capitalize">Phone Number ID</h4>
                                                                    <p class="mb-0 text-sm capitalize text-light dark:text-subtitle-dark">Captura el identificador <strong>Phone Number ID</strong></p>
                                                                </div>
                                                                <div class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[15px] py-[12px] min-h-[50px] focus:ring-primary focus:border-primary gap-[12px]  flex items-center">
                                                                    <span class="inline-flex items-center text-sm text-light dark:text-subtitle-dark me-[8px]">
                                                                        <i class="uil uil-copy text-[16px]"></i>
                                                                    </span>
                                                                    <input type="text"
                                                                            id="field-meta-phone-number-id"
                                                                            name="phone_number_id"
                                                                            class="outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full bg-transparent"
                                                                            placeholder="Phone Number ID"
                                                                            maxlength="256"
                                                                            autocomplete="off"
                                                                            value="">
                                                                </div>

                                                            </li>
                                                            <li class="flex items-center justify-between mb-0 px-[25px] py-[20px] border-b border-regular last:border-none dark:border-box-dark-up gap-[15px]">

                                                                <div>
                                                                    <h4 class="mb-0.5 text-body dark:text-title-dark text-sm font-medium capitalize">WhatsApp Business Account ID</h4>
                                                                    <p class="mb-0 text-sm capitalize text-light dark:text-subtitle-dark">Captura el identificador <strong>WhatsApp Business Account ID</strong></p>
                                                                </div>
                                                                <div class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[15px] py-[12px] min-h-[50px] focus:ring-primary focus:border-primary gap-[12px]  flex items-center">
                                                                    <span class="inline-flex items-center text-sm text-light dark:text-subtitle-dark me-[8px]">
                                                                        <i class="uil uil-copy text-[16px]"></i>
                                                                    </span>
                                                                    <input type="text"
                                                                            id="field-meta-business-account-id"
                                                                            name="business_account_id"
                                                                            class="outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full bg-transparent"
                                                                            placeholder="Business Account ID"
                                                                            maxlength="256"
                                                                            autocomplete="off"
                                                                            value="">
                                                                </div>

                                                            </li>
                                                            <li class="flex items-center justify-between mb-0 px-[25px] py-[20px] border-b border-regular last:border-none dark:border-box-dark-up gap-[15px]">

                                                                <div>
                                                                    <h4 class="mb-0.5 text-body dark:text-title-dark text-sm font-medium capitalize">Access Token</h4>
                                                                    <p class="mb-0 text-sm capitalize text-light dark:text-subtitle-dark">Si no se captura un dato no se modifica el token de acceso.</p>
                                                                </div>
                                                                <div class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[15px] py-[12px] min-h-[50px] focus:ring-primary focus:border-primary gap-[12px]  flex items-center">
                                                                    <span class="inline-flex items-center text-sm text-light dark:text-subtitle-dark me-[8px]">
                                                                        <i class="uil uil-copy text-[16px]"></i>
                                                                    </span>
                                                                    <input type="text"
                                                                            id="field-meta-access-token"
                                                                            name="meta_access_token"
                                                                            class="outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full bg-transparent"
                                                                            placeholder="Access Token"
                                                                            maxlength="1024"
                                                                            autocomplete="off"
                                                                            value="">
                                                                </div>

                                                            </li>
                                                            <li class="flex items-center justify-between mb-0 px-[25px] py-[20px] border-b border-regular last:border-none dark:border-box-dark-up gap-[15px]">

                                                                <div>
                                                                    <h4 class="mb-0.5 text-body dark:text-title-dark text-sm font-medium capitalize">Teléfono Destino</h4>
                                                                    <p class="mb-0 text-sm capitalize text-light dark:text-subtitle-dark">Captura el teléfono para hacer una prueba de mensaje</p>
                                                                </div>
                                                                <div class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[15px] py-[12px] min-h-[50px] focus:ring-primary focus:border-primary gap-[12px]  flex items-center">
                                                                    <span class="inline-flex items-center text-sm text-light dark:text-subtitle-dark me-[8px]">
                                                                        <i class="uil uil-phone text-[16px]"></i>
                                                                    </span>
                                                                    <input type="text"
                                                                            id="field-meta-test-recipient"
                                                                            name="meta_test_recipient"
                                                                            class="outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full bg-transparent"
                                                                            placeholder="Recipiente"
                                                                            maxlength="16"
                                                                            autocomplete="off"
                                                                            value="">
                                                                </div>

                                                            </li>
                                                            <li class="flex items-center justify-between mb-0 px-[25px] py-[20px] border-b border-regular last:border-none dark:border-box-dark-up gap-[15px]">

                                                                <div>
                                                                    <h4 class="mb-0.5 text-body dark:text-title-dark text-sm font-medium capitalize">Activo</h4>
                                                                    <p class="mb-0 text-sm capitalize text-light dark:text-subtitle-dark">Activa si utilizarás Meta WhatsApp Cloud API como tu mensajeria para WhatsApp</strong></p>
                                                                </div>
                                                                <label for="chk-meta-active"
                                                                        class="relative inline-flex items-center cursor-pointer">
                                                                    <input id="chk-meta-active"
                                                                            name="chk-meta-active"
                                                                            type="checkbox"
                                                                            value=""
                                                                            class="sr-only peer switch-group">
                                                                    <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                                                                </label>

                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="flex justify-between mt-2">
                                                        <div class="flex gap-[10px]">
                                                            <button type="button" id="btn-meta-integration-test" class="px-[30px] h-[34px] text-white bg-primary border-regular hover:bg-primary-hbr disabled:text-neutral-600 disabled:bg-lightgray disabled:cursor-not-allowed font-medium rounded-4 text-sm w-full sm:w-auto text-center inline-flex items-center justify-center capitalize transition-all duration-300 ease-linear" data-te-ripple-init="" data-te-ripple-color="light">
                                                                Probar Conexión
                                                            </button>
                                                            <button type="button" id="btn-meta-integration-test-message" class="px-[30px] h-[34px] text-white bg-primary border-regular hover:bg-primary-hbr disabled:text-neutral-600 disabled:bg-lightgray disabled:cursor-not-allowed font-medium rounded-4 text-sm w-full sm:w-auto text-center inline-flex items-center justify-center capitalize transition-all duration-300 ease-linear" data-te-ripple-init="" data-te-ripple-color="light">
                                                                Enviar Mensaje
                                                            </button>
                                                        </div>
                                                        <div class="flex flex-row-reverse gap-[10px]">
                                                            <button type="submit" id="btn-meta-integration-save" class="group text-[13px] border-normal font-semibold text-white dark:text-title-dark btn-outlined h-[37px] dark:border-box-dark-up sm:px-[20px] px-[15px] rounded-6 flex items-center gap-[5px] leading-[22px] hover:text-white hover:bg-primary-hbr bg-primary transition duration-300" data-te-ripple-init="" data-te-ripple-color="light">
                                                                Guardar Cambios
                                                            </button>
                                                            <button type="button" id="btn-meta-integration-cancel" class="group text-[13px] font-semibold text-theme-gray bg-normalBG dark:bg-box-dark-up dark:text-title-dark btn-outlined h-[37px] dark:border-box-dark-up sm:px-[20px] px-[15px] rounded-6 flex items-center gap-[5px] leading-[22px] hover:text-white hover:bg-danger transition duration-300 border-1 border-normal" data-te-ripple-init="" data-te-ripple-color="light">
                                                                Cancelar
                                                            </button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>



            </div>
            </div>
        </div>
        <!-- end sector -->
        <!-- sector -->
        <div class="hidden opacity-100 transition-opacity duration-150 ease-linear data-[te-tab-active]:block" id="tabs-notification" role="tabpanel" aria-labelledby="tabs-notification-tab">
            <div class="bg-white dark:bg-box-dark rounded-10">
                <div class="py-[18px] px-[25px] text-dark dark:text-title-dark font-medium text-[17px] border-regular dark:border-box-dark-up border-b">
                    <h1 class="mb-0 text-lg font-medium text-dark dark:text-title-dark">Notificaciones</h1>
                    <span class="mb-0.5 text-light dark:text-subtitle-dark text-13 font-normal">Elige las notificaciones que deseas recibir</span>
                </div>
                <div class="px-[25px] pb-[37px] pt-[30px]">
                    <div class="bg-regularBG dark:bg-box-dark-up px-[25px] pb-[25px] pt-[15px] max-sm:px-[15px] rounded-[10px]">
                        <div class="flex items-center justify-between h-[50px]">
                            <h2 class="text-light dark:text-white/60 text-[15px] font-medium">Notificaciones</h2>
                            <button class="switch-trigger font-normal text-info text-[13px] border-none outline-none shadow-none bg-transparent">
                            Seleccionar todas
                            </button>
                        </div>
                        <div class="bg-white dark:bg-box-dark shadow-[0_5px_20px_rgba(173,181,217,0.05)] rounded-[10px]">
                            <ul>
                            <li class="flex items-center justify-between mb-0 px-[25px] py-[20px] border-b border-regular last:border-none dark:border-box-dark-up gap-[15px]">

                                <div>
                                    <h4 class="mb-0.5 text-body dark:text-title-dark text-sm font-medium capitalize">Notificaciones Generales</h4>
                                    <p class="mb-0 text-sm capitalize text-light dark:text-subtitle-dark">Obten las notificaciones generales generadas por la plataforma</p>
                                </div>
                                <label for="switch1" class="relative inline-flex items-center cursor-pointer">
                                    <input id="switch1" name="switch1" type="checkbox" value="" class="sr-only peer switch-group">
                                    <div class="w-9 h-5 bg-gray-200 peer-focus:outline-none rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:border-gray-600 peer-checked:bg-primary"></div>
                                </label>

                            </li>

                            </ul>
                        </div>
                    </div>
                    <div class="static flex flex-wrap items-center gap-[10px] sm:mt-[43px] mt-[24] ">
                        <button type="button" class="group text-[13px] border-normal font-semibold text-white dark:text-title-dark btn-outlined h-[37px] dark:border-box-dark-up sm:px-[20px] px-[15px] rounded-6 flex items-center gap-[5px] leading-[22px] hover:text-white hover:bg-primary-hbr bg-primary transition duration-300" data-te-ripple-init="" data-te-ripple-color="light">
                            Guardar Cambios
                        </button>
                        <button type="button" class="group text-[13px] font-semibold text-theme-gray bg-normalBG dark:bg-box-dark-up dark:text-title-dark btn-outlined h-[37px] dark:border-box-dark-up sm:px-[20px] px-[15px] rounded-6 flex items-center gap-[5px] leading-[22px] hover:text-white hover:bg-dark transition duration-300 border-1 border-normal" data-te-ripple-init="" data-te-ripple-color="light">
                            Cancelar
                        </button>

                    </div>
                </div>
            </div>
        </div>
        <!-- end sector -->
    </div>
    <!-- End Content -->
</div>

<script src="<?= asset('js/settings/index.js'); ?>"></script>

<script>
    var currentLink = '<?= base_url('settings'); ?>';
</script>