
         </div>

         <!-- Footer -->
         <footer class="px-[25px] bg-white dark:bg-box-dark">

            <!-- Footer content -->
            <div class="flex justify-between flex-wrap py-[22px] gap-x-[30px] gap-y-[15px] max-ssm:gap-y-[8px] items-center max-md:flex-col">
               <!-- Copyright information -->
               <div class="flex items-center gap-[4px] text-[14px] font-medium max-md:text-center text-body dark:text-subtitle-dark">© <span class="current-year"><?php echo date('Y'); ?></span> <a href="#" class="text-primary"><?php echo config('name'); ?></a></div>
            </div>

         </footer>
         <!-- end: Footer -->
      </main>
      <!-- End: Main Content -->
   </div>
   <!-- End: Wrapping Content -->

   <!-- Customizing option -->

   <!-- End: Customizing option -->

   <!-- Preloader -->

   <div class="preloader fixed w-full h-full z-[9999] flex items-center justify-center top-0 bg-white dark:bg-black">
      <div class="animate-spin inline-block w-[50px] h-[50px] border-[3px] border-current border-t-transparent text-primary rounded-full" role="status" aria-label="loading">
         <span class="sr-only">Loading...</span>
      </div>
   </div>

   <!-- End: Preloader -->

   <!-- inject:js-->
   <script src="<?= base_url('template/assets/vendor_assets/js/apexcharts.min.js'); ?>"></script>
   <script src="<?= base_url('template/assets/vendor_assets/js/datepicker-full.js'); ?>"></script>
   <script src="<?= base_url('template/assets/vendor_assets/js/fslightbox.js'); ?>"></script>
   <script src="<?= base_url('template/assets/vendor_assets/js/index.global.min.js'); ?>"></script>
   <script src="<?= base_url('template/assets/vendor_assets/js/fullcalendar-locales-es-min.js'); ?>"></script>
   <script src="<?= base_url('template/assets/vendor_assets/js/mixitup.min.js'); ?>"></script>
   <script src="<?= base_url('template/assets/vendor_assets/js/moment.min.js'); ?>"></script>
   <script src="<?= base_url('template/assets/vendor_assets/js/nouislider.min.js'); ?>"></script>
   <script src="<?= base_url('template/assets/vendor_assets/js/svg-pan-zoom.min.js'); ?>"></script>
   <script src="<?= base_url('template/assets/vendor_assets/js/svgMap.min.js'); ?>"></script>
   <script src="<?= base_url('template/assets/vendor_assets/js/tw-elements.umd.min.js'); ?>"></script>
   <script src="<?= base_url('template/assets/vendor_assets/js/yscountdown.min.js'); ?>"></script>
   <script src="<?= base_url('template/assets/theme_assets/js/apex-custom.js'); ?>"></script>
   <script src="<?= base_url('template/assets/theme_assets/js/full-calendar.js'); ?>"></script>
   <script src="<?= base_url('template/assets/theme_assets/js/googlemap-init.js'); ?>"></script>
   <script src="<?= base_url('template/assets/theme_assets/js/main.js'); ?>"></script>
   <script src="<?= base_url('template/assets/theme_assets/js/svgMapData.js'); ?>"></script>
   
    <script src="<?= base_url('template/assets/theme_assets/js/sweetalert2.all.min.js'); ?>"></script>
    <script src="<?= base_url('template/assets/theme_assets/js/sweetalert.init.js'); ?>"></script>
    <script src="<?= base_url('template/assets/theme_assets/js/jquery-3.7.1.min.js'); ?>"></script>
    <script src="<?= asset('js/global.js'); ?>"></script>
    <script src="<?= asset('js/sweetalert.js'); ?>"></script>
    <script src="<?= asset('js/sha512.js'); ?>"></script>
    <script src="<?= asset('js/forms.js'); ?>"></script>
    <script src="<?= asset('js/accounting.js'); ?>"></script>
   <!-- endinject-->

    <script>
        $(document).ready(function(e) {
			InitializeValues('<?= base_url(''); ?>');
         ActiveMenu(typeof currentLink !== 'undefined' && currentLink !== null 
            ? currentLink 
            : '<?= base_url(''); ?>');
		});
    </script>
</body>

</html>