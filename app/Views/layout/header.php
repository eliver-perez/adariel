<!doctype html>
<html lang="en" dir="ltr" class="scroll-smooth focus:scroll-auto">

<head>
   <meta charset="UTF-8">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <meta http-equiv="X-UA-Compatible" content="ie=edge">
   <link rel="icon" type="image/png" sizes="32x32" href="images/favicon.ico">
   <title><?php echo config('name'); ?></title>

   <!-- inject:css-->
   <link rel="stylesheet" href="<?= base_url('../template/assets/vendor_assets/css/apexcharts.min.css'); ?>">
   <link rel="stylesheet" href="<?= base_url('../template/assets/vendor_assets/css/datepicker.css'); ?>">
   <link rel="stylesheet" href="<?= base_url('../template/assets/vendor_assets/css/line-awesome.min.css'); ?>">
   <link rel="stylesheet" href="<?= base_url('../template/assets/vendor_assets/css/nouislider.min.css'); ?>">
   <link rel="stylesheet" href="<?= base_url('../template/assets/vendor_assets/css/quill.snow.css'); ?>">
   <link rel="stylesheet" href="<?= base_url('../template/assets/vendor_assets/css/svgMap.min.css'); ?>">
   <link rel="stylesheet" href="<?= base_url('../template/assets/vendor_assets/css/global.css'); ?>">
   <link rel="stylesheet" href="<?= base_url('../template/tailwind.css'); ?>">
   <!-- endinject -->

   <!-- Fonts -->
   <link rel="stylesheet" href="<?= base_url('../template/assets/theme_assets/fonts/jost/jost.css'); ?>">
   <!-- <link href="https://fonts.googleapis.com/css2?family=Jost:wght@400;500;600;700&display=swap" rel="stylesheet"> -->
   <!-- Icons -->
   <!-- <link rel="stylesheet" href="https://unicons.iconscout.com/release/v4.0.8/css/line.css"> -->
   <link rel="stylesheet" href="<?= base_url('../template/assets/theme_assets/css/line.css'); ?>">
</head>

<body class=" bg-white [&.dark]:bg-main-dark font-jost relative text-[15px] font-normal leading-[1.5] m-0 p-0">
   <!-- Aside -->

   <aside id="asideBar" class="asidebar dark:bg-box-dark fixed start-0 top-0 z-[1035] h-screen overflow-hidden bg-white xl:!w-[280px] xl:[&.collapsed]:!w-[80px] [&.collapsed]:!w-[250px] xl:[&.TopCollapsed]:!w-[0px] [&.TopCollapsed]:!w-[250px] !transition-all !duration-[0.2s] ease-linear delay-[0s] !w-0 xl:[&.collapsed>.logo-wrapper]:w-[80px]">
      <div class="flex w-[280px] border-e border-[#edf2f9] dark:border-box-dark-up logo-wrapper items-center h-[71px] dark:bg-box-dark-up max-xl:hidden">
         <a href="<?= base_url(''); ?>" class="block text-center">
            <div class="logo-full">
               <img class="ps-[27px] dark:hidden" src="<?= asset('images/logos/logo_h.png'); ?>" alt="Logo" style="max-width: 80%;">
               <img class="ps-[27px] hidden dark:block" src="<?= asset('images/logos/logo_h.png'); ?>" alt="Logo" style="max-width: 80%;">
            </div>
            <div class="hidden logo-fold">
               <img class="p-[27px] max-w-[80px]" src="<?= asset('images/logos/logo_fold.png'); ?>" alt="Logo">
            </div>
         </a>
      </div>
      <nav id="navBar" class="navBar dark:bg-box-dark start-0 max-xl:top-[80px] z-[1035] h-full overflow-y-auto bg-white xl:!w-[280px] xl:[&.collapsed]:!w-[80px] [&.collapsed]:!w-[250px] xl:[&.TopCollapsed]:!w-[0px] [&.TopCollapsed]:!w-[250px] !transition-all !duration-[0.2s] ease-linear delay-[0s] !w-0 pb-[100px] scrollbar xl:[&.collapsed]:ps-[7px] relative">
         <ul class="relative m-0 list-none px-[0.2rem] ">
               <li class="relative sub-item-wrapper group">
                  <a class="group-[.open]:bg-primary/10 rounded-e-[20px] hover:bg-primary/10 focus:bg-primary/10 dark:text-subtitle-dark flex h-12 cursor-pointer items-center gap-[16px] truncate px-6 py-4 text-[14px] font-medium text-body outline-none transition duration-300 ease-linear hover:text-primary dark:hover:text-title-dark hover:outline-none focus:text-primary dark:focus:text-title-dark focus:outline-none motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up group capitalize">
                     <span class="nav-icon dark:text-subtitle-dark text-[18px] text-light-extra group-hover:text-current group-focus:text-current">
                        <i class="uil uil-apps"></i>
                     </span>
                     <span class="capitalize title">Dashboard</span>
                     <span class=" arrow-down text-gray-600 dark:text-subtitle-dark absolute end-0 me-[0.8rem] ms-auto text-[18px] transition-transform duration-300 ease-linear motion-reduce:transition-none group-hover:text-current">
                        <i class="uil uil-angle-down"></i>
                     </span>
                  </a>
                  <ul class="sub-item !visible m-0 hidden list-none p-0 [&.show]:block scrollbar overflow-y-scroll">
                     <li class="relative">
                        <a href="<?= base_url(''); ?>" class="capitalize rounded-e-[20px] text-gray-600 hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark focus:bg-primary/10 focus:text-inherit active:bg-primary/10 active:text-inherit [&.active]:text-primary dark:[&.active]:text-title-dark dark:text-subtitle-dark flex cursor-pointer items-center truncate py-[10px] pe-6 ps-[60px] text-[14px] outline-none transition duration-300 ease-linear hover:outline-none focus:outline-none active:outline-none motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up ">
                           <span>Dashboard</span>
                        </a>
                     </li>
                  </ul>
               </li>
            <?php if($session->verifyUserRights(['superadmin', 'gerente-general'])) { ?>
               <li class="relative">
                  <span class="slug dark:text-white/40 mb-[10px] mt-[30px] block px-6 text-[12px] font-medium uppercase text-light">ADMINISTRACIÓN</span>
               </li>
            <?php } ?>
            <?php if($session->verifyUserRights(['superadmin', 'gerente-general'])) { ?>
               <li class="relative">
                  <a href="<?= base_url('pos'); ?>" class="rounded-e-[20px] hover:bg-primary/10 focus:bg-primary/10 active:bg-primary/10 dark:text-subtitle-dark flex h-12 cursor-pointer items-center gap-[16px] truncate px-6 py-4 text-[14px] font-medium text-body outline-none transition duration-300 ease-linear hover:text-primary dark:hover:text-title-dark hover:outline-none focus:text-primary dark:focus:text-title-dark focus:outline-none active:text-primary active:outline-none [&.active]:text-primary dark:[&.active]:text-title-dark  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up group capitalize ">
                     <span class="nav-icon dark:text-subtitle-dark text-[18px] text-light-extra  group-hover:text-current group-[&.active]:text-current group-focus:text-current">
                        <i class="uil uil-store-alt"></i>
                     </span>
                     <span class="capitalize title">Punto de Venta</span>
                  </a>
               </li>
            <?php } ?>
            <?php if($session->verifyUserRights(['superadmin', 'gerente-general'])) { ?>
               <li class="relative">
                  <a href="<?= base_url('cash-reconciliation'); ?>" class="rounded-e-[20px] hover:bg-primary/10 focus:bg-primary/10 active:bg-primary/10 dark:text-subtitle-dark flex h-12 cursor-pointer items-center gap-[16px] truncate px-6 py-4 text-[14px] font-medium text-body outline-none transition duration-300 ease-linear hover:text-primary dark:hover:text-title-dark hover:outline-none focus:text-primary dark:focus:text-title-dark focus:outline-none active:text-primary active:outline-none [&.active]:text-primary dark:[&.active]:text-title-dark  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up group capitalize ">
                     <span class="nav-icon dark:text-subtitle-dark text-[18px] text-light-extra  group-hover:text-current group-[&.active]:text-current group-focus:text-current">
                        <i class="uil uil-money-bill-stack"></i>
                     </span>
                     <span class="capitalize title">Cortes</span>
                  </a>
               </li>
            <?php } ?>
            <?php if($session->verifyUserRights(['superadmin', 'gerente-general'])) { ?>
               <li class="relative">
                  <a href="<?= base_url('payments'); ?>" class="rounded-e-[20px] hover:bg-primary/10 focus:bg-primary/10 active:bg-primary/10 dark:text-subtitle-dark flex h-12 cursor-pointer items-center gap-[16px] truncate px-6 py-4 text-[14px] font-medium text-body outline-none transition duration-300 ease-linear hover:text-primary dark:hover:text-title-dark hover:outline-none focus:text-primary dark:focus:text-title-dark focus:outline-none active:text-primary active:outline-none [&.active]:text-primary dark:[&.active]:text-title-dark  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up group capitalize ">
                     <span class="nav-icon dark:text-subtitle-dark text-[18px] text-light-extra  group-hover:text-current group-[&.active]:text-current group-focus:text-current">
                        <i class="uil uil-invoice"></i>
                     </span>
                     <span class="capitalize title">Pagos</span>
                  </a>
               </li>
            <?php } ?>
            <?php if($session->verifyUserRights(['superadmin', 'gerente-general'])) { ?>
               <li class="relative">
                  <a href="<?= base_url('sales'); ?>" class="rounded-e-[20px] hover:bg-primary/10 focus:bg-primary/10 active:bg-primary/10 dark:text-subtitle-dark flex h-12 cursor-pointer items-center gap-[16px] truncate px-6 py-4 text-[14px] font-medium text-body outline-none transition duration-300 ease-linear hover:text-primary dark:hover:text-title-dark hover:outline-none focus:text-primary dark:focus:text-title-dark focus:outline-none active:text-primary active:outline-none [&.active]:text-primary dark:[&.active]:text-title-dark  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up group capitalize ">
                     <span class="nav-icon dark:text-subtitle-dark text-[18px] text-light-extra  group-hover:text-current group-[&.active]:text-current group-focus:text-current">
                        <i class="uil uil-shopping-cart"></i>
                     </span>
                     <span class="capitalize title">Ventas</span>
                  </a>
               </li>
            <?php } ?>
            <?php if($session->verifyUserRights(['superadmin', 'gerente-general'])) { ?>
               <li class="relative">
                  <span class="slug dark:text-white/40 mb-[10px] mt-[30px] block px-6 text-[12px] font-medium uppercase text-light">USUARIOS</span>
               </li>
            <?php } ?>
            <?php if($session->verifyUserRights(['superadmin', 'gerente-general'])) { ?>
               <li class="relative">
                  <a href="<?= base_url('staff'); ?>" class="rounded-e-[20px] hover:bg-primary/10 focus:bg-primary/10 active:bg-primary/10 dark:text-subtitle-dark flex h-12 cursor-pointer items-center gap-[16px] truncate px-6 py-4 text-[14px] font-medium text-body outline-none transition duration-300 ease-linear hover:text-primary dark:hover:text-title-dark hover:outline-none focus:text-primary dark:focus:text-title-dark focus:outline-none active:text-primary active:outline-none [&.active]:text-primary dark:[&.active]:text-title-dark  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up group capitalize">
                     <span class="nav-icon dark:text-subtitle-dark text-[18px] text-light-extra  group-hover:text-current group-[&.active]:text-current group-focus:text-current">
                        <i class="uil uil-house-user"></i>
                     </span>
                     <span class="capitalize title">Personal</span>
                  </a>
               </li>
            <?php } ?>
            <?php if($session->verifyUserRights(['superadmin'])) { ?>
               <li class="relative">
                  <a href="<?= base_url('organizations'); ?>" class="rounded-e-[20px] hover:bg-primary/10 focus:bg-primary/10 active:bg-primary/10 dark:text-subtitle-dark flex h-12 cursor-pointer items-center gap-[16px] truncate px-6 py-4 text-[14px] font-medium text-body outline-none transition duration-300 ease-linear hover:text-primary dark:hover:text-title-dark hover:outline-none focus:text-primary dark:focus:text-title-dark focus:outline-none active:text-primary active:outline-none [&.active]:text-primary dark:[&.active]:text-title-dark  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up group capitalize ">
                     <span class="nav-icon dark:text-subtitle-dark text-[18px] text-light-extra  group-hover:text-current group-[&.active]:text-current group-focus:text-current">
                        <i class="uil uil-building"></i>
                     </span>
                     <span class="capitalize title">Empresas</span>
                  </a>
               </li>
            <?php } ?>
            <?php if($session->verifyUserRights(['superadmin'])) { ?>
               <li class="relative">
                  <a href="<?= base_url('users'); ?>" class="rounded-e-[20px] hover:bg-primary/10 focus:bg-primary/10 active:bg-primary/10 dark:text-subtitle-dark flex h-12 cursor-pointer items-center gap-[16px] truncate px-6 py-4 text-[14px] font-medium text-body outline-none transition duration-300 ease-linear hover:text-primary dark:hover:text-title-dark hover:outline-none focus:text-primary dark:focus:text-title-dark focus:outline-none active:text-primary active:outline-none [&.active]:text-primary dark:[&.active]:text-title-dark  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up group capitalize ">
                     <span class="nav-icon dark:text-subtitle-dark text-[18px] text-light-extra  group-hover:text-current group-[&.active]:text-current group-focus:text-current">
                        <i class="uil uil-users-alt"></i>
                     </span>
                     <span class="capitalize title">Usuarios</span>
                  </a>
               </li>
            <?php } ?>
            <?php if($session->verifyUserRights(['superadmin'])) { ?>
               <li class="relative">
                  <a href="<?= base_url('roles'); ?>" class="rounded-e-[20px] hover:bg-primary/10 focus:bg-primary/10 active:bg-primary/10 dark:text-subtitle-dark flex h-12 cursor-pointer items-center gap-[16px] truncate px-6 py-4 text-[14px] font-medium text-body outline-none transition duration-300 ease-linear hover:text-primary dark:hover:text-title-dark hover:outline-none focus:text-primary dark:focus:text-title-dark focus:outline-none active:text-primary active:outline-none [&.active]:text-primary dark:[&.active]:text-title-dark  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up group capitalize ">
                     <span class="nav-icon dark:text-subtitle-dark text-[18px] text-light-extra  group-hover:text-current group-[&.active]:text-current group-focus:text-current">
                        <i class="uil uil-lock-open-alt"></i>
                     </span>
                     <span class="capitalize title">Roles y Permisos</span>
                  </a>
               </li>
            <?php } ?>
            <?php if($session->verifyUserRights(['superadmin', 'gerente-general'])) { ?>
               <li class="relative">
                  <a href="<?= base_url('staff-availibility'); ?>" class="rounded-e-[20px] hover:bg-primary/10 focus:bg-primary/10 active:bg-primary/10 dark:text-subtitle-dark flex h-12 cursor-pointer items-center gap-[16px] truncate px-6 py-4 text-[14px] font-medium text-body outline-none transition duration-300 ease-linear hover:text-primary dark:hover:text-title-dark hover:outline-none focus:text-primary dark:focus:text-title-dark focus:outline-none active:text-primary active:outline-none [&.active]:text-primary dark:[&.active]:text-title-dark  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up group capitalize ">
                     <span class="nav-icon dark:text-subtitle-dark text-[18px] text-light-extra  group-hover:text-current group-[&.active]:text-current group-focus:text-current">
                        <i class="uil uil-clock"></i>
                     </span>
                     <span class="capitalize title">Horario Disponibilidad</span>
                  </a>
               </li>
            <?php } ?>
            <?php if($session->verifyUserRights(['superadmin', 'gerente-general', 'citas-atender'])) { ?>
               <li class="relative">
                  <span class="slug dark:text-white/40 mb-[10px] mt-[30px] block px-6 text-[12px] font-medium uppercase text-light">CLINICA</span>
               </li>
            <?php } ?>
            <?php if($session->verifyUserRights(['citas-atender'])) { ?>
               <li class="relative">
                  <a href="<?= base_url('consultations'); ?>" class="rounded-e-[20px] hover:bg-primary/10 focus:bg-primary/10 active:bg-primary/10 dark:text-subtitle-dark flex h-12 cursor-pointer items-center gap-[16px] truncate px-6 py-4 text-[14px] font-medium text-body outline-none transition duration-300 ease-linear hover:text-primary dark:hover:text-title-dark hover:outline-none focus:text-primary dark:focus:text-title-dark focus:outline-none active:text-primary active:outline-none [&.active]:text-primary dark:[&.active]:text-title-dark  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up group capitalize ">
                     <span class="nav-icon dark:text-subtitle-dark text-[18px] text-light-extra  group-hover:text-current group-[&.active]:text-current group-focus:text-current">
                        <i class="uil uil-band-aid"></i>
                     </span>
                     <span class="capitalize title">Consultas</span>
                  </a>
               </li>
            <?php } ?>
            <?php if($session->verifyUserRights(['superadmin', 'gerente-general'])) { ?>
               <li class="relative">
                  <a href="<?= base_url('procedures'); ?>" class="rounded-e-[20px] hover:bg-primary/10 focus:bg-primary/10 active:bg-primary/10 dark:text-subtitle-dark flex h-12 cursor-pointer items-center gap-[16px] truncate px-6 py-4 text-[14px] font-medium text-body outline-none transition duration-300 ease-linear hover:text-primary dark:hover:text-title-dark hover:outline-none focus:text-primary dark:focus:text-title-dark focus:outline-none active:text-primary active:outline-none [&.active]:text-primary dark:[&.active]:text-title-dark  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up group capitalize ">
                     <span class="nav-icon dark:text-subtitle-dark text-[18px] text-light-extra  group-hover:text-current group-[&.active]:text-current group-focus:text-current">
                        <i class="uil uil-band-aid"></i>
                     </span>
                     <span class="capitalize title">Servicios & Procedimientos</span>
                  </a>
               </li>
            <?php } ?>
            <?php if($session->verifyUserRights(['superadmin', 'gerente-general'])) { ?>
               <li class="relative">
                  <a href="<?= base_url('consents'); ?>" class="rounded-e-[20px] hover:bg-primary/10 focus:bg-primary/10 active:bg-primary/10 dark:text-subtitle-dark flex h-12 cursor-pointer items-center gap-[16px] truncate px-6 py-4 text-[14px] font-medium text-body outline-none transition duration-300 ease-linear hover:text-primary dark:hover:text-title-dark hover:outline-none focus:text-primary dark:focus:text-title-dark focus:outline-none active:text-primary active:outline-none [&.active]:text-primary dark:[&.active]:text-title-dark  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up group capitalize ">
                     <span class="nav-icon dark:text-subtitle-dark text-[18px] text-light-extra  group-hover:text-current group-[&.active]:text-current group-focus:text-current">
                        <i class="uil uil-band-aid"></i>
                     </span>
                     <span class="capitalize title">Consentimientos</span>
                  </a>
               </li>
            <?php } ?>
            <?php if($session->verifyUserRights(['superadmin', 'gerente-general'])) { ?>
               <li class="relative">
                  <a href="<?= base_url('patients'); ?>" class="rounded-e-[20px] hover:bg-primary/10 focus:bg-primary/10 active:bg-primary/10 dark:text-subtitle-dark flex h-12 cursor-pointer items-center gap-[16px] truncate px-6 py-4 text-[14px] font-medium text-body outline-none transition duration-300 ease-linear hover:text-primary dark:hover:text-title-dark hover:outline-none focus:text-primary dark:focus:text-title-dark focus:outline-none active:text-primary active:outline-none [&.active]:text-primary dark:[&.active]:text-title-dark  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up group capitalize ">
                     <span class="nav-icon dark:text-subtitle-dark text-[18px] text-light-extra  group-hover:text-current group-[&.active]:text-current group-focus:text-current">
                        <i class="uil uil-document-layout-left"></i>
                     </span>
                     <span class="capitalize title">Pacientes</span>
                  </a>
               </li>
            <?php } ?>
            <?php if($session->verifyUserRights(['superadmin', 'gerente-general'])) { ?>
               <li class="relative">
                  <a href="<?= base_url('appointments'); ?>" class="rounded-e-[20px] hover:bg-primary/10 focus:bg-primary/10 active:bg-primary/10 dark:text-subtitle-dark flex h-12 cursor-pointer items-center gap-[16px] truncate px-6 py-4 text-[14px] font-medium text-body outline-none transition duration-300 ease-linear hover:text-primary dark:hover:text-title-dark hover:outline-none focus:text-primary dark:focus:text-title-dark focus:outline-none active:text-primary active:outline-none [&.active]:text-primary dark:[&.active]:text-title-dark  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up group capitalize ">
                     <span class="nav-icon dark:text-subtitle-dark text-[18px] text-light-extra  group-hover:text-current group-[&.active]:text-current group-focus:text-current">
                        <i class="uil uil-calendar-alt"></i>
                     </span>
                     <span class="capitalize title">Agenda</span>
                  </a>
               </li>
            <?php } ?>
            <?php if($session->verifyUserRights(['superadmin', 'gerente-general'])) { ?>
               <li class="relative">
                  <a href="<?= base_url('appointments/add'); ?>" class="rounded-e-[20px] hover:bg-primary/10 focus:bg-primary/10 active:bg-primary/10 dark:text-subtitle-dark flex h-12 cursor-pointer items-center gap-[16px] truncate px-6 py-4 text-[14px] font-medium text-body outline-none transition duration-300 ease-linear hover:text-primary dark:hover:text-title-dark hover:outline-none focus:text-primary dark:focus:text-title-dark focus:outline-none active:text-primary active:outline-none [&.active]:text-primary dark:[&.active]:text-title-dark  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up group capitalize ">
                     <span class="nav-icon dark:text-subtitle-dark text-[18px] text-light-extra  group-hover:text-current group-[&.active]:text-current group-focus:text-current">
                        <i class="uil uil-file-plus-alt"></i>
                     </span>
                     <span class="capitalize title">Nueva Cita</span>
                  </a>
               </li>
            <?php } ?>
            <?php if($session->verifyUserRights(['superadmin', 'gerente-general'])) { ?>
               <li class="relative">
                  <span class="slug dark:text-white/40 mb-[10px] mt-[30px] block px-6 text-[12px] font-medium uppercase text-light">INVENTARIOS</span>
               </li>
            <?php } ?>
            <?php if($session->verifyUserRights(['superadmin', 'gerente-general'])) { ?>
               <li class="relative">
                  <a href="<?= base_url('suppliers'); ?>" class="rounded-e-[20px] hover:bg-primary/10 focus:bg-primary/10 active:bg-primary/10 dark:text-subtitle-dark flex h-12 cursor-pointer items-center gap-[16px] truncate px-6 py-4 text-[14px] font-medium text-body outline-none transition duration-300 ease-linear hover:text-primary dark:hover:text-title-dark hover:outline-none focus:text-primary dark:focus:text-title-dark focus:outline-none active:text-primary active:outline-none [&.active]:text-primary dark:[&.active]:text-title-dark  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up group capitalize ">
                     <span class="nav-icon dark:text-subtitle-dark text-[18px] text-light-extra  group-hover:text-current group-[&.active]:text-current group-focus:text-current">
                        <i class="uil uil-user"></i>
                     </span>
                     <span class="capitalize title">Proveedores</span>
                  </a>
               </li>
            <?php } ?>
            <?php if($session->verifyUserRights(['superadmin', 'gerente-general'])) { ?>
               <li class="relative">
                  <a href="<?= base_url('inventory'); ?>" class="rounded-e-[20px] hover:bg-primary/10 focus:bg-primary/10 active:bg-primary/10 dark:text-subtitle-dark flex h-12 cursor-pointer items-center gap-[16px] truncate px-6 py-4 text-[14px] font-medium text-body outline-none transition duration-300 ease-linear hover:text-primary dark:hover:text-title-dark hover:outline-none focus:text-primary dark:focus:text-title-dark focus:outline-none active:text-primary active:outline-none [&.active]:text-primary dark:[&.active]:text-title-dark  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up group capitalize ">
                     <span class="nav-icon dark:text-subtitle-dark text-[18px] text-light-extra  group-hover:text-current group-[&.active]:text-current group-focus:text-current">
                        <i class="uil uil-slack-alt"></i>
                     </span>
                     <span class="capitalize title">Inventario</span>
                  </a>
            </li>
            <?php } ?>
            <?php if($session->verifyUserRights(['superadmin', 'gerente-general'])) { ?>
               <li class="relative">
                  <a href="<?= base_url('products'); ?>" class="rounded-e-[20px] hover:bg-primary/10 focus:bg-primary/10 active:bg-primary/10 dark:text-subtitle-dark flex h-12 cursor-pointer items-center gap-[16px] truncate px-6 py-4 text-[14px] font-medium text-body outline-none transition duration-300 ease-linear hover:text-primary dark:hover:text-title-dark hover:outline-none focus:text-primary dark:focus:text-title-dark focus:outline-none active:text-primary active:outline-none [&.active]:text-primary dark:[&.active]:text-title-dark  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up group capitalize ">
                     <span class="nav-icon dark:text-subtitle-dark text-[18px] text-light-extra  group-hover:text-current group-[&.active]:text-current group-focus:text-current">
                        <i class="uil uil-cube"></i>
                     </span>
                     <span class="capitalize title">Productos</span>
                  </a>
               </li>
            <?php } ?>
            <?php if($session->verifyUserRights(['superadmin', 'gerente-general'])) { ?>
               <li class="relative">
                  <a href="<?= base_url('requisition'); ?>" class="rounded-e-[20px] hover:bg-primary/10 focus:bg-primary/10 active:bg-primary/10 dark:text-subtitle-dark flex h-12 cursor-pointer items-center gap-[16px] truncate px-6 py-4 text-[14px] font-medium text-body outline-none transition duration-300 ease-linear hover:text-primary dark:hover:text-title-dark hover:outline-none focus:text-primary dark:focus:text-title-dark focus:outline-none active:text-primary active:outline-none [&.active]:text-primary dark:[&.active]:text-title-dark  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up group capitalize ">
                     <span class="nav-icon dark:text-subtitle-dark text-[18px] text-light-extra  group-hover:text-current group-[&.active]:text-current group-focus:text-current">
                        <i class="uil uil-parcel"></i>
                     </span>
                     <span class="capitalize title">Requisiciones</span>
                  </a>
               </li>
            <?php } ?>
            <?php if($session->verifyUserRights(['superadmin', 'gerente-general'])) { ?>
               <li class="relative">
                  <a href="<?= base_url('purchase-orders'); ?>" class="rounded-e-[20px] hover:bg-primary/10 focus:bg-primary/10 active:bg-primary/10 dark:text-subtitle-dark flex h-12 cursor-pointer items-center gap-[16px] truncate px-6 py-4 text-[14px] font-medium text-body outline-none transition duration-300 ease-linear hover:text-primary dark:hover:text-title-dark hover:outline-none focus:text-primary dark:focus:text-title-dark focus:outline-none active:text-primary active:outline-none [&.active]:text-primary dark:[&.active]:text-title-dark  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up group capitalize ">
                     <span class="nav-icon dark:text-subtitle-dark text-[18px] text-light-extra  group-hover:text-current group-[&.active]:text-current group-focus:text-current">
                        <i class="uil uil-receipt"></i>
                     </span>
                     <span class="capitalize title">Ordenes de Compra</span>
                  </a>
               </li>
            <?php } ?>
            <?php if($session->verifyUserRights(['superadmin', 'gerente-general'])) { ?>
               <li class="relative">
                  <span class="slug dark:text-white/40 mb-[10px] mt-[30px] block px-6 text-[12px] font-medium uppercase text-light">AJUSTES</span>
               </li>
            <?php } ?>
            <?php if($session->verifyUserRights(['superadmin', 'gerente-general'])) { ?>
               <li class="relative sub-item-wrapper group">
                  <a class="group-[.open]:bg-primary/10 rounded-e-[20px] hover:bg-primary/10 focus:bg-primary/10 dark:text-subtitle-dark flex h-12 cursor-pointer items-center gap-[16px] truncate px-6 py-4 text-[14px] font-medium text-body outline-none transition duration-300 ease-linear hover:text-primary dark:hover:text-title-dark hover:outline-none focus:text-primary dark:focus:text-title-dark focus:outline-none motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up group capitalize">
                     <span class="nav-icon dark:text-subtitle-dark text-[18px] text-light-extra group-hover:text-current group-focus:text-current">
                        <i class="uil uil-palette"></i>
                     </span>
                     <span class="capitalize title">Plantillas</span>
                     <span class=" arrow-down text-gray-600 dark:text-subtitle-dark absolute end-0 me-[0.8rem] ms-auto text-[18px] transition-transform duration-300 ease-linear motion-reduce:transition-none group-hover:text-current">
                        <i class="uil uil-angle-down"></i>
                     </span>
                  </a>
                  <ul class="sub-item !visible m-0 hidden list-none p-0 [&.show]:block scrollbar overflow-y-scroll">
                     <li class="relative">
                        <a href="<?= base_url('consent-templates'); ?>" class="capitalize rounded-e-[20px] text-gray-600 hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark focus:bg-primary/10 focus:text-inherit active:bg-primary/10 active:text-inherit [&.active]:text-primary dark:[&.active]:text-title-dark dark:text-subtitle-dark flex cursor-pointer items-center truncate py-[10px] pe-6 ps-[60px] text-[14px] outline-none transition duration-300 ease-linear hover:outline-none focus:outline-none active:outline-none motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up ">
                           <span>Consentimiento</span>
                        </a>
                     </li>
                  </ul>
               </li>
            <?php } ?>
            <?php if($session->verifyUserRights(['superadmin', 'gerente-general'])) { ?>
               <li class="relative">
                  <a href="<?= base_url('settings'); ?>" class="rounded-e-[20px] hover:bg-primary/10 focus:bg-primary/10 active:bg-primary/10 dark:text-subtitle-dark flex h-12 cursor-pointer items-center gap-[16px] truncate px-6 py-4 text-[14px] font-medium text-body outline-none transition duration-300 ease-linear hover:text-primary dark:hover:text-title-dark hover:outline-none focus:text-primary dark:focus:text-title-dark focus:outline-none active:text-primary active:outline-none [&.active]:text-primary dark:[&.active]:text-title-dark  motion-reduce:transition-none dark:hover:bg-box-dark-up dark:focus:bg-box-dark-up dark:active:bg-box-dark-up group capitalize ">
                     <span class="nav-icon dark:text-subtitle-dark text-[18px] text-light-extra  group-hover:text-current group-[&.active]:text-current group-focus:text-current">
                        <i class="uil uil-setting"></i>
                     </span>
                     <span class="capitalize title">Ajustes</span>
                  </a>
               </li>
            <?php } ?>
         </ul>
      </nav>
   </aside>

   <!-- End: Aside -->

   <!-- Wrapping Content -->
   <div class="relative flex flex-col flex-1 xl:ps-[280px] xl:[&.expanded]:ps-[80px] xl:[&.TopExpanded]:ps-[0px] !transition-all !duration-[0.2s] ease-linear delay-[0s] bg-normalBG dark:bg-main-dark" id="content">
      <!-- Header -->
      <header class="sticky top-0 flex w-full bg-white xl:z-[999] max-xl:z-[9999] drop-shadow-1 dark:bg-box-dark dark:drop-shadow-none min-h-[70px]">
         <!-- Navigation -->
         <div class="flex flex-1 nav-wrap md:ps-[20px] ps-[30px] pe-[30px] max-xs:ps-[15px] max-xs:pe-[15px] bg-white dark:bg-box-dark">
            <!-- Header left menu -->

            <ul class="flex items-center mb-0 list-none nav-left ps-0 gap-x-[14px] gap-y-[9px]">
               <!-- Navigation Items -->
               <li class="xl:hidden xl:[&.flex]:flex" id="topMenu-logo">
                  <div class="flex md:w-[190px] xs:w-[170px] max-xs:w-[100px] max-md:pe-[30px] max-xs:pe-[15px] border-e border-[#edf2f9] dark:border-box-dark-up logo-wrapper items-center h-[71px] dark:bg-box-dark-up">
                     <a href="<?= base_url(''); ?>" class="block text-center">
                        <div class="logo-full">
                           <img class="md:ps-[15px] dark:hidden" src="<?= base_url('../template/images/logos/logo-dark.png'); ?>" alt="Logo">
                           <img class="md:ps-[15px] hidden dark:block" src="<?= base_url('../template/images/logos/logo-white.png'); ?>" alt="Logo">
                        </div>
                     </a>
                  </div>
               </li>
               <li>
                  <a class="flex items-center justify-center sm:min-w-[40px] sm:w-[40px] sm:h-[40px] min-w-[34px] h-[34px] rounded-full bg-transparent hover:bg-primary/10 hover:text-primary dark:hover:text-title-dark dark:hover:bg-box-dark-up group transition duration-200 ease-in-out text-[#525768] dark:text-subtitle-dark max-md:dark:hover:bg-box-dark-up sm:text-[20px] text-[19px] cursor-pointer xl:[&.hide]:hidden max-md:bg-normalBG max-md:dark:bg-box-dark-up max-md:dark:hover:text-subtitle-dark" id="slim-toggler">
                     <i class="uil uil-align-center-alt text-current [&.is-folded]:hidden flex items-center"></i>
                  </a>
               </li>
            </ul>

            <!-- Header right menu -->

            <div class="flex items-center ms-auto py-[15px] sm:gap-x-[25px] max-sm:gap-x-[15px] gap-y-[15px] relative">

               <button type="button" class="flex xl:hidden items-center text-[22px] text-[#a0a0a0] dark:text-subtitle-dark min-h-[40px]">
                  <i class="uil uil-ellipsis-v text-[18px]"></i>
               </button>
               <ul id="right-ellipsis-trigger" class="xl:flex hidden items-center justify-end flex-auto mb-0 list-none ps-0 sm:gap-x-[25px] max-sm:gap-x-[15px] gap-y-[15px] max-xl:absolute max-xl:z-[1000] max-xl:m-0 max-xl:rounded-lg max-xl:border-none max-xl:bg-white max-xl:bg-clip-padding max-xl:text-left max-xl:shadow-lg max-xl:dark:bg-neutral-700 max-xl:[&.active]:flex max-xl:end-0 max-xl:px-[20px] max-sm:px-[15px] max-xl:py-[10px] max-xl:top-[70px]">
                  
                  <li>

                     <div class="relative" data-te-dropdown-ref>
                        <button type="button" id="btn-header-menu-cart" onclick="javascript:window.open('<?= base_url('pos/') ?>', '_self')" class="flex items-center text-[22px] text-[#a0a0a0] dark:text-subtitle-dark min-h-[40px]">
                           <i class="uil uil-shopping-cart-alt"></i>
                           <?php
                              $cart_sales = count($_SESSION['cart']['sales'] ?? []);
                              $cart_products = count($_SESSION['cart']['products'] ?? []);
                              if(($cart_sales + $cart_products) > 0) { ?>
                                 <span class="text-[11px] leading-[12px] absolute top-2 start-[15px] transform -translate-y-1/2 px-[4.50px] py-[1px] bg-danger border-2 border-white dark:border-gray-800 rounded-[15px] inline-flex items-center justify-center text-white dark:text-dark"><?= $cart_sales + $cart_products; ?></span>
                           <?php
                              }
                           ?>
                        </button>
                     </div>

                  </li>

                  <li>

                     <div class="relative" data-te-dropdown-ref>
                        <button type="button" id="author-dropdown" data-te-dropdown-toggle-ref aria-expanded="false" class="flex items-center me-1.5 text-body dark:text-subtitle-dark text-sm font-medium rounded-full md:me-0 group whitespace-nowrap">
                           <span class="sr-only">Men&uacute; de Usuario</span>
                           <img class="min-w-[32px] w-8 h-8 rounded-full xl:me-2" src="<?= base_url('../template/images/avatars/thumbs.png'); ?>" alt="user photo">
                           <span class="hidden xl:block"><?php echo $_SESSION['ADARIEL_ERP_EMAIL']; ?></span>
                           <i class="uil uil-angle-down text-light dark:text-subtitle-dark text-[18px] hidden xl:block"></i>
                        </button>

                        <!-- Dropdown menu -->
                        <div class="absolute z-[1000] ltr:float-left rtl:float-right m-0 hidden min-w-max list-none overflow-hidden rounded-lg border-none bg-white bg-clip-padding text-left text-base shadow-lg dark:shadow-boxLargeDark dark:bg-box-dark-down  [&[data-te-dropdown-show]]:block" aria-labelledby="author-dropdown" data-te-dropdown-menu-ref>
                           <div class="min-w-[310px] max-sm:min-w-full pt-4 px-[15px] py-[12px] bg-white dark:bg-box-dark shadow-[0_2px_8px_rgba(0,0,0,.15)] dark:shadow-[0_5px_30px_rgba(1,4,19,.60)] rounded-4">
                              <figure class="flex items-center text-sm rounded-[8px] bg-section dark:bg-box-dark-up py-[20px] px-[25px] mb-[12px] gap-[15px]">
                                 <img class="w-8 h-8 rounded-full bg-regular" src="<?= base_url('../template/images/avatars/thumbs.png'); ?>" alt="user">
                                 <figcaption>
                                    <div class="text-dark dark:text-title-dark mb-0.5 text-sm"><?php echo $_SESSION['ADARIEL_ERP_EMAIL']; ?></div>
                                    <div class="mb-0 text-xs font-medium dark:text-subtitle-dark"><?php echo $_SESSION['ADARIEL_ERP_ORGANIZATION']; ?></div>
                                    <div class="mb-0 text-xs text-body dark:text-subtitle-dark"><?php echo $_SESSION['ADARIEL_ERP_USER_TYPE']; ?></div>
                                 </figcaption>
                              </figure>
                              <ul class="m-0 pb-[10px] overflow-x-hidden overflow-y-auto scrollbar bg-transparent max-h-[230px]">
                                 <li class="w-full">
                                    <div class="p-0 dark:hover:text-white hover:bg-primary/10 dark:hover:bg-box-dark-up rounded-4">
                                       <button class="inline-flex items-center text-light dark:text-subtitle-dark hover:text-primary hover:ps-6 w-full px-2.5 py-3 text-sm transition-[0.3s] gap-[10px]">
                                          <i class="text-[16px] uil uil-user"></i>
                                          Perfil
                                       </button>
                                    </div>
                                 </li>
                                 <li class="w-full">
                                    <div class="p-0 dark:hover:text-white hover:bg-primary/10 dark:hover:bg-box-dark-up rounded-4">
                                       <button class="inline-flex items-center text-light dark:text-subtitle-dark hover:text-primary hover:ps-6 w-full px-2.5 py-3 text-sm transition-[0.3s] gap-[10px]">
                                          <i class="text-[16px] uil uil-setting"></i>
                                          Ajustes
                                       </button>
                                    </div>
                                 </li>
                                 <li class="w-full">
                                    <div class="p-0 dark:hover:text-white hover:bg-primary/10 dark:hover:bg-box-dark-up rounded-4">
                                       <button class="inline-flex items-center text-light dark:text-subtitle-dark hover:text-primary hover:ps-6 w-full px-2.5 py-3 text-sm transition-[0.3s] gap-[10px]">
                                          <i class="text-[16px] uil uil-key-skeleton"></i>
                                          Facturacion
                                       </button>
                                    </div>
                                 </li>
                                 <li class="w-full">
                                    <div class="p-0 dark:hover:text-white hover:bg-primary/10 dark:hover:bg-box-dark-up rounded-4">
                                       <button class="inline-flex items-center text-light dark:text-subtitle-dark hover:text-primary hover:ps-6 w-full px-2.5 py-3 text-sm transition-[0.3s] gap-[10px]">
                                          <i class="text-[16px] uil uil-users-alt"></i>
                                          Actividad
                                       </button>
                                    </div>
                                 </li>
                                 <li class="w-full">
                                    <div class="p-0 dark:hover:text-white hover:bg-primary/10 dark:hover:bg-box-dark-up rounded-4">
                                       <button class="inline-flex items-center text-light dark:text-subtitle-dark hover:text-primary hover:ps-6 w-full px-2.5 py-3 text-sm transition-[0.3s] gap-[10px]">
                                          <i class="text-[16px] uil uil-bell"></i>
                                          Ayuda
                                       </button>
                                    </div>
                                 </li>
                              </ul>
                              <a class="flex items-center justify-center text-sm font-medium bg-normalBG dark:bg-box-dark-up h-[50px] text-light hover:text-primary dark:hover:text-subtitle-dark dark:text-title-dark mx-[-15px] mb-[-15px] rounded-b-6 gap-[6px]" href="javascript:CerrarSesion('<?= config('url'); ?>');">
                                 <i class="uil uil-sign-out-alt"></i> Salir</a>
                           </div>
                        </div>
                     </div>


                  </li>
               </ul>
            </div>

         </div>
         <!-- End: Navigation -->
      </header>
      <!-- End: Header -->

      <!-- Main Content -->
      <main class="bg-normalBG dark:bg-main-dark">
         <div class=" mx-[30px] min-h-[calc(100vh-195px)] mb-[30px] ssm:mt-[30px] mt-[15px]">