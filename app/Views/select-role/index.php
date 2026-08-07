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
                        alt="image" class="hidden dark:inline" style="height: 130px">
                </a>

                <!-- Login form background -->
                <div class="rounded-6 mt-[25px] shadow-regular dark:shadow-xl bg-white dark:bg-[#111726]">
                    <div class="p-[25px] text-center border-b border-regular dark:border-white/[.05] top">
                        <h2 class="text-18 font-semibold leading-[1] mb-0 text-dark dark:text-title-dark">Selecciona un rol</h2>
                    </div>

                    <div class="py-[30px] px-[40px]">
                        
                    </div>
                </div>
                <a href="javascript:CerrarSesion('<?= config('url'); ?>');" class="text-primary text-end text-[14px]">Cerrar Sesión</a>
            </div>
        </div>

        <!-- End of the content block -->

    </main>


    <script src="<?= base_url('../template/'); ?>assets/theme_assets/js/sweetalert2.all.min.js"></script>
    <script src="<?= base_url('../template/'); ?>assets/theme_assets/js/sweetalert.init.js"></script>
    <script src="<?= base_url('../template/'); ?>assets/theme_assets/js/jquery-3.7.1.min.js"></script>
    <script src="<?= asset('js/sweetalert.js') ?>"></script>
    <script src="<?= asset('js/forms.js') ?>"></script>
    <script src="<?= asset('js/select-role/index.js') ?>"></script>
    <script src="<?= asset('js/global.js'); ?>"></script>

    <script>
        $(document).ready(function (e) {
            InitializeValues('<?= base_url(''); ?>');
        });
    </script>
</body>

</html>