<!doctype html>
<html lang="en" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <!-- Meta Tags -->
    <meta name="description" content="Sistema de Autenticacioon">

    <!-- Title -->
    <title><?= config('name'); ?> - Autenticaci&oacute;n</title>
    <link rel="icon" type="image/png" sizes="32x32"
        href="<?= base_url('../template/'); ?>images/favicon.ico">


    <!-- inject:css-->
    <link rel="stylesheet" href="<?= base_url('../template/'); ?>tailwind.css">
    <!-- endinject -->

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Icons -->
    <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css">

</head>

<body class="scrollbar">
    <!-- Main Content -->
    <main
        class="relative bg-top bg-no-repeat bg-[url('<?= asset('images/admin/admin-bg-light.png'); ?>')] dark:bg-[url('<?= asset('images/admin/admin-bg-dark.png'); ?>')] dark:bg-[#1e2836] bg-contain bg-normalBG">


        <!-- Main content container with responsive design -->
        <div class="h-[calc(var(--vh,1vh)_*_100)] w-full overflow-y-auto scrollbar">

            <!-- Login form container -->
            <div class="flex flex-col justify-center w-full max-w-[516px] px-[30px] mx-auto my-[150px]">
                <a href="#" class="text-center">
                    <!-- Logo for the light theme -->
                    <img src="<?= asset('images/logos/logo_v.png'); ?>"
                        alt="image" class="inline dark:hidden" style="height: 130px">
                    <!-- Logo for the dark theme -->
                    <img src="<?= asset('images/logos/logo_v.png'); ?>"
                        alt="image" class="hidden dark:inline" style="height: 120px">
                </a>

                <!-- Login form background -->
                <div class="rounded-6 mt-[25px] shadow-regular dark:shadow-xl bg-white dark:bg-[#111726]">
                    <div class="p-[25px] text-center border-b border-regular dark:border-white/[.05] top">
                        <!-- Heading for the login form -->
                        <h2 class="text-18 font-semibold leading-[1] mb-0 text-dark dark:text-title-dark">
                            Autenticaci&oacute;n</h2>
                    </div>

                    <!-- Login form inputs and elements -->
                    <div class="py-[30px] px-[40px]">
                        <form id="form-autenticar" action="javascript:Autenticar()">

                            <!-- Email Address input -->
                            <div class="mb-6">
                                <label for="field-email"
                                    class="text-[14px] w-full leading-[1.4285714286] font-medium text-dark dark:text-gray-300 mb-[8px] capitalize inline-block">E-Mail</label>
                                <input type="text" id="field-email"
                                    class="flex items-center shadow-none py-[10px] px-[20px] h-[48px] border-1 border-regular rounded-4 w-full text-[14px] font-normal leading-[1.5] placeholder:text-[#A0A0A0] focus:ring-primary focus:border-primary"
                                    placeholder="E-Mail" autocomplete="off" value="" required>
                            </div>

                            <!-- Password input -->
                            <div class="mb-6">
                                <label for="field-password"
                                    class="text-[14px] w-full leading-[1.4285714286] font-medium text-dark dark:text-gray-300 mb-[8px] capitalize inline-block">
                                    Password</label>
                                <div class="relative w-full">
                                    <div class="absolute inset-y-0 end-0 flex items-center px-[15px]">
                                        <input class="hidden js-password-toggle" id="toggle" type="checkbox">
                                        <label
                                            class=" rounded cursor-pointer text-light text-[15px] js-password-label dark:text-subtitle-dark"
                                            for="toggle"><i class="uil uil-eye-slash"></i></label>
                                    </div>
                                    <input
                                        class="flex items-center shadow-none py-[10px] px-[20px] h-[48px] border-1 border-regular rounded-4 w-full text-[14px] font-normal leading-[1.5] placeholder:text-[#A0A0A0] focus:ring-primary focus:border-primary js-password"
                                        id="field-password" type="password" value="" autocomplete="off"
                                        placeholder="Password" required>
                                </div>
                            </div>

                            <!-- Remember me and forgot password options -->
                            <div
                                class="flex items-center sm:justify-between justify-center max-sm:flex-wrap capitalize mb-[19px] mt-[23px] gap-[15px]">
                                <div class="flex">
                                    <div class="flex items-center h-5">
                                        <input id="chk-remember" type="checkbox" value=""
                                            class="relative ltr:float-left rtl:float-right me-[6px] mt-[0.15rem] h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-1 border-solid border-normal outline-none before:pointer-events-none before:absolute before:h-[10px] before:w-[0.5px] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:mt-0 checked:after:ms-[5px] checked:after:block checked:after:h-[10px] checked:after:w-[5px] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] dark:border-box-dark-up dark:checked:border-primary dark:checked:bg-primary after:top-[2px]">
                                    </div>
                                    <label for="chk-remember"
                                        class="text-sm text-gray-500 ms-1 dark:text-gray-400">Mantenerme
                                        conectado</label>
                                </div>
                                <a class="text-13 text-primary hover:text-dark dark:hover:text-title-dark"
                                    href="#">¿Olvidaste tu Contrase&ntilde;a?</a>
                            </div>

                            <!-- Submit button for the login form -->
                            <button type="submit"
                                class="inline-flex items-center justify-center w-full h-[48px] text-14 rounded-6 font-medium bg-primary text-white cursor-pointer hover:bg-primary-hbr border-primary transition duration-300"
                                value="submit">Conectarme</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- End of the content block -->

    </main>


    <script src="<?= base_url('../template/'); ?>assets/theme_assets/js/sweetalert2.all.min.js"></script>
    <script src="<?= base_url('../template/'); ?>assets/theme_assets/js/sweetalert.init.js"></script>
    <script src="<?= base_url('../template/'); ?>assets/theme_assets/js/jquery-3.7.1.min.js"></script>
    <script src="<?= asset('js/sweetalert.js') ?>"></script>
    <script src="<?= asset('js/sha512.js') ?>"></script>
    <script src="<?= asset('js/forms.js') ?>"></script>
    <script src="<?= asset('js/autenticar.js') ?>"></script>

    <script>
        $(document).ready(function (e) {
            InitializeValues('<?= base_url(''); ?>');
        });
    </script>
</body>

</html>