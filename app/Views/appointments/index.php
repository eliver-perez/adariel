<?php
    $title = "Agenda";
    $section = "Agenda";

    require_once __DIR__.'/../layout/title.php';
    require_once __DIR__.'/calendar_modal.php';
    require_once __DIR__.'/modal_scheduleappointment.php';
?>

<style>
    /* .fc-timegrid-event {
      height: 45px;
      max-height: 45px !important;
    } */

    .fc-timegrid-event {
      min-height: 34px;
   }

    .fc .fc-timegrid-slot-label {
      font-size: .9rem;
   }

    .fc-timegrid-event .fc-event-title {
      white-space: normal;
      overflow: hidden;
      font-weight: 600;
   }

   .fc-timegrid-event .fc-event-main {
      overflow: visible !important;
   }

   .fc-timegrid-event .fc-event-main-frame {
      display: block !important;
   }

   .fc-timegrid-event .fc-event-time {
      display: block;
   }

   .fc-timegrid-event .fc-event-title-container,
   .fc-timegrid-event .fc-event-title {
      display: block !important;
      white-space: normal !important;
      overflow: visible !important;
   }

   .fc .fc-timegrid-slot {
      height: 42px;
   }
</style>

<!-- Responsive Toggler -->
<div class="flex items-center justify-center 4xl:hidden ssm:mb-[30px] mb-[15px]">
   <button id="chat-sidebar-selector" type="button" class=" text-danger text-sm font-semibold inline-flex justify-center items-center w-[40px] h-[40px] bg-white rounded-6 dark:bg-box-dark-up">
      <i class="uil uil-align-left text-[24px]"></i>
   </button>
</div>
<div class="w-full mx-auto">
   <div class="flex flex-wrap mx-[-15px]">
      <div class="4xl:w-[25%] w-full px-[15px] 4xl:mb-[30px]">
         <div id="chat-sidebar-target" class="max-4xl:bg-white max-4xl:dark:bg-[#3a3b3c] 4xl:rounded-[10px] max-4xl:rounded-e-[10px] max-4xl:w-[280px] max-4xl:fixed max-4xl:z-[9999] max-4xl:start-0 [&.nav-open]:translate-x-0 max-4xl:top-0 max-4xl:h-full ltr:max-4xl:translate-x-[-280px] rtl:max-4xl:translate-x-[280px] max-4xl:shadow-lg duration-200 max-4xl:p-[15px] max-4xl:overflow-auto scrollbar">
            <!-- Create New  Event -->
            <button type="button" id="btn-new-appointment" class=" h-[50px] text-[14px] font-medium w-full rounded-[8px] mb-[25px] bg-primary border-primary text-white flex items-center justify-center gap-[6px] px-[30px]" data-te-ripple-init="" data-te-ripple-color="light">
               <i class="uil uil-plus"></i>
               Agendar Cita
            </button>
            <!-- Mini calendar -->
            <div class="agenda-datepicker mb-[25px]">
               <div class="mini-datepicker [&>div]:w-full max-4xl:border-1 max-4xl:border-regular max-4xl:dark:border-box-dark-up max-4xl:rounded-6" id="mini-calendario" data-date="<?php echo date('d/m/Y'); ?>"></div>
            </div>
            <!-- Calendar Events -->
            <div class="bg-white max-4xl:border-1 max-4xl:border-regular max-4xl:dark:border-box-dark-up dark:bg-box-dark rounded-10 p-[25px]">
               <div class="flex items-center justify-between mb-[18px] text-dark dark:text-title-dark">
                  <h3 class="font-medium text-[18px] text-dark dark:text-title-dark">
                     Estatus de Cita
                  </h3>
               </div>
               <ul id="sector-estatus">
                     
               </ul>
            </div>
         </div>
      </div>
      <div class="4xl:w-[75%] w-full px-[15px] mb-[30px]">
         <div>
            <div id='calendario-agenda' class='full-calendar w-full relative bg-white main-calendar dark:bg-box-dark rounded-10 p-[25px] overflow-x-auto scrollbar'>
            </div>
         </div>
      </div>
   </div>
</div>

<script src="<?= asset('js/appointments/index.js'); ?>"></script>

<script>
    var currentLink = '<?= base_url('appointments'); ?>';
    var action = '<?= $_GET['action'] ?? ''; ?>';
</script>