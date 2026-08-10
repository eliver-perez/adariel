<?php
    use App\Controllers\ConsultationsController;
    
    $consultationsController = new ConsultationsController();
    $consultation = json_decode($consultationsController->view($id, 1));

    // die(json_encode($consultation));

    $title = "Consulta";
    $section = "Consulta";
    $subsection = "Iniciar Consulta";

    require_once __DIR__.'/../layout/title.php';

    $moduleViews = [
        'observacion_inicial' => 'module_initial_observations.php',
        'exploracion_podologica' => 'module_podiatric_exploration.php',
        'procedimientos' => 'module_procedures.php',
        'diagnosticos' => 'module_diagnostic.php',
        'lesiones_ulceras' => 'module_sore.php',
        'plantillas' => 'module_insoles.php',
        'evidencia-fotografica' => 'module_evidence.php',
        'indicaciones' => 'module_indications.php',
        'proxima_cita' => 'module_schedule_appointment.php',
        // 'archivos_adjuntos' => 'module_attached_files.php',
        // 'evolucion' => 'module_evolution.php',
    ];
?>

<?php
    if(!$consultation->success) { ?>
        <div class="sm:grid sm:grid-cols-12 max-sm:flex max-sm:flex-col gap-[25px]">
            <div class="col-span-12">
                <div class="bg-white dark:bg-box-dark rounded-10 pt-[25px] px-[25px] pb-[30px] shadow-[0_5px_20px_rgba(173,181,217,0.05)] dark:shadow-none">
                    <h4 class="text-[20px] text-dark dark:text-subtitle-dark font-medium mb-[25px]">No es posible mostrar la informacion</h4>
                    <h6 class="text-[16px] text-light dark:text-subtitle-dark font-medium mb-[25px]">Seras dirigido a la sección de consultas en <span class="span-redirect-sec"></span>.</h6>
                </div>
            </div>
        </div>
        <script src="<?= asset('js/global.js'); ?>"></script>
        <script>
            callUrlTimer(10, 'span-redirect-sec', '<?= base_url('consultations'); ?>');
        </script>
    <?php
        exit();
    }
?>

<script>
    window.consultationModules = {};
</script>

<div class="sm:grid sm:grid-cols-12 max-sm:flex max-sm:flex-col gap-[25px]">
    <div class="col-span-12 lg:col-span-4">
        <div class="bg-white dark:bg-box-dark rounded-10 pt-[25px] px-[25px] pb-[10px] shadow-[0_5px_20px_rgba(173,181,217,0.05)] dark:shadow-none">
            <h4 class="text-[20px] text-dark dark:text-subtitle-dark font-medium mb-[25px]">Datos de Paciente</h4>
            <div class="grid grid-cols-12 mb-[20px]">
                <div class="col-span-12 text-center">
                    <figure class="relative mb-0 max-w-[120px] h-[120px] inline-block bg-normalBG dark:bg-box-dark-up rounded-full">
                        <img id="widget-profile-upload-image" class="max-w-[120px] min-w-[120px] h-[120px] rounded-full inline-block object-cover" src="<?= asset('images/no-picture.jpeg'); ?>" alt="author">
                        <div class="absolute rounded-md bottom-0 end-[5px]">
                            <label id="widget-profile-uploader" for="widget-profile-dropzone-file" class="flex flex-col items-center justify-center w-[40px] h-[40px] transition duration-150 ease-linear border-white border-solid border-4 rounded-full cursor-pointer xs:px-3 group bg-primary">
                                <div class="flex flex-col items-center justify-center">
                                    <div class="text-[16px] text-white">
                                        <i class="uil uil-camera"></i>
                                    </div>
                                </div>
                                <input id="widget-profile-dropzone-file" type="file" class="hidden">
                            </label>
                        </div>
                    </figure>
                    <figcaption class="mt-[28px]">
                        <h3 class=" text-[18px] mb-[6px] font-medium text-dark dark:text-title-dark leading-[23px] hover:[&>a]:text-primary">
                            <a class="text-dark dark:text-title-dark" href="#"><?= $consultation->data->patient; ?></a>
                        </h3>
                        <p class="text-[15px] text-light dark:text-subtitle-dark"><?= $consultation->data->age; ?></p>
                    </figcaption>
                </div>
                <div class="col-span-12 flex items-center flex-wrap justify-between  gap-y-[5px] mb-[10px]">
                    <div class=" px-2 py-1 text-[10px] font-medium text-primary uppercase rounded-md bg-primary/20" id="field-consultation-type">
                        <?= $consultation->data->appointment_type; ?>
                    </div>
                    <div class=" px-2 py-1 text-[10px] font-medium text-warning uppercase rounded-md bg-warning/20" id="field-consultation-status">
                        <?= $consultation->data->status; ?>
                    </div>
                </div>
                <div class="col-span-12 2xl:col-span-3 lg:col-span-4">
                    <h4 class="text-[14px] text-light dark:text-subtitle-dark mb-[8px] font-normal">Folio:</h4>
                    <div class="flex items-center mb-[15px] gap-[15px]">
                        <span class="text-[14px] font-medium text-theme-gray dark:text-subtitle-dark capitalize" id="field-consultation-folio"><?= $consultation->data->folio; ?></span>
                    </div>
                </div>
                <div class="col-span-12 2xl:col-span-6 lg:col-span-8">
                    <h4 class="text-[14px] text-light dark:text-subtitle-dark mb-[8px] font-normal">Fecha de Cita:</h4>
                    <div class="flex items-center mb-[15px] gap-[15px]">
                        <span class="text-[14px] font-medium text-theme-gray dark:text-subtitle-dark capitalize" id="field-consultation-date"><?= $consultation->data->date.' '.$consultation->data->time_start; ?></span>
                    </div>
                </div>
                <div class="col-span-12 2xl:col-span-3 lg:col-span-4">
                    <h4 class="text-[14px] text-light dark:text-subtitle-dark mb-[8px] font-normal">Duración:</h4>
                    <div class="flex items-center mb-[15px] gap-[15px]">
                        <span class="text-[14px] font-medium text-theme-gray dark:text-subtitle-dark capitalize" id="field-consultation-duration"><?= $consultation->data->duration; ?> min.</span>
                    </div>
                </div>
                <div class="col-span-12 2xl:col-span-4 lg:col-span-4">
                    <h4 class="text-[14px] text-light dark:text-subtitle-dark mb-[8px] font-normal">Sexo:</h4>
                    <div class="flex items-center mb-[15px] gap-[15px]">
                        <span class="text-[14px] font-medium text-theme-gray dark:text-subtitle-dark capitalize" id="field-consultation-gender"><?= $consultation->data->gender; ?></span>
                    </div>
                </div>
                <div class="col-span-12 2xl:col-span-4 lg:col-span-4">
                    <h4 class="text-[14px] text-light dark:text-subtitle-dark mb-[8px] font-normal">Teléfono:</h4>
                    <div class="flex items-center mb-[15px] gap-[15px]">
                        <span class="text-[14px] font-medium text-theme-gray dark:text-subtitle-dark capitalize" id="field-consultation-phone"><?= $consultation->data->phone; ?></span>
                    </div>
                </div>
                <div class="col-span-12 2xl:col-span-4 lg:col-span-4">
                    <h4 class="text-[14px] text-light dark:text-subtitle-dark mb-[8px] font-normal">Movil:</h4>
                    <div class="flex items-center mb-[15px] gap-[15px]">
                        <span class="text-[14px] font-medium text-theme-gray dark:text-subtitle-dark capitalize" id="field-consultation-phone"><?= $consultation->data->mobile; ?></span>
                    </div>
                </div>
                <div class="col-span-12 2xl:col-span-4 lg:col-span-8">
                    <h4 class="text-[14px] text-light dark:text-subtitle-dark mb-[8px] font-normal">E-Mail:</h4>
                    <div class="flex items-center mb-[15px] gap-[15px]">
                        <span class="text-[14px] font-medium text-theme-gray dark:text-subtitle-dark" id="field-consultation-email"><?= $consultation->data->email; ?></span>
                    </div>
                </div>
                <div class="col-span-12">
                    <h4 class="text-[14px] text-light dark:text-subtitle-dark mb-[8px] font-normal">Medicamentos:</h4>
                    <div class="flex items-center mb-[15px] gap-[15px]">
                        <span class="text-[14px] font-medium text-theme-gray dark:text-subtitle-dark" id="field-consultation-email"><?= $consultation->data->current_medications; ?></span>
                    </div>
                </div>
                <div class="col-span-12">
                    <h4 class="text-[14px] text-light dark:text-subtitle-dark mb-[8px] font-normal">Suplementos:</h4>
                    <div class="flex items-center mb-[15px] gap-[15px]">
                        <span class="text-[14px] font-medium text-theme-gray dark:text-subtitle-dark" id="field-consultation-email"><?= $consultation->data->supplements; ?></span>
                    </div>
                </div>
                <div class="col-span-12">
                    <h4 class="text-[14px] text-light dark:text-subtitle-dark mb-[8px] font-normal">Antecedentes Familiares:</h4>
                    <div class="flex items-center mb-[15px] gap-[15px]">
                        <span class="text-[14px] font-medium text-theme-gray dark:text-subtitle-dark" id="field-consultation-email"><?= $consultation->data->family_medical_history; ?></span>
                    </div>
                </div>
                <div class="col-span-12">
                    <h4 class="text-[14px] text-light dark:text-subtitle-dark mb-[8px] font-normal">Observaciones Generales:</h4>
                    <div class="flex items-center mb-[15px] gap-[15px]">
                        <span class="text-[14px] font-medium text-theme-gray dark:text-subtitle-dark" id="field-consultation-email"><?= $consultation->data->general_observations; ?></span>
                    </div>
                </div>

                <div class="col-span-12 flex flex-row-reverse items-center gap-[5px] mt-[14px]">
                    <button type="button" id="btn-consultation-end" class="px-[30px] h-[34px] text-white bg-primary border-regular hover:bg-primary-hbr disabled:text-neutral-600 disabled:bg-lightgray disabled:cursor-not-allowed font-medium rounded-4 text-sm w-full sm:w-auto text-center inline-flex items-center justify-center capitalize transition-all duration-300 ease-linear" data-te-ripple-init="" data-te-ripple-color="light">Finalizar Consulta</button>
                </div>
            </div>
        </div>
    </div>


    <div class="col-span-12 lg:col-span-8" style="max-height: 82vh; overflow-y: auto;">
        <div class="sm:grid sm:grid-cols-12 max-sm:flex max-sm:flex-col gap-[25px]">
            <div class="col-span-12">
                <div class="bg-white dark:bg-box-dark rounded-b-0 rounded-t-10 pt-[25px] px-[25px] pb-[30px] shadow-[0_5px_20px_rgba(173,181,217,0.05)] dark:shadow-none">
                    <h4 class="text-[20px] text-dark dark:text-subtitle-dark font-medium mb-[25px]">Datos de Consulta</h4>
                    <h4 class="text-[20px] font-semibold leading-1 text-theme-gray dark:text-subtitle-dark mb-[12px]">Procedimientos:</h4>
                    <?php
                        foreach($consultation->data->procedures as $p) {?>
                            <div><?= $p->procedure; ?></div>
                            <?php
                        }
                    ?>
                    <hr class="mb-[12px]" />
                    <h4 class="text-[20px] font-semibold leading-1 text-theme-gray dark:text-subtitle-dark mb-[12px]">Motivo de Consulta:</h4>
                    <p class="text-theme-gray dark:text-subtitle-dark text-[16px] font-normal" id="field-consultation-chief-complaint"><?= $consultation->data->chief_complaint; ?></p>
                </div>
            </div>
        </div>

    <div class="tabs">
        <nav class="sm:px-[25px] px-[15px] bg-white dark:bg-box-dark rounded-t-0 rounded-b-10 mb-[25px] overflow-x-auto">
            <ul class="m-0 flex items-center sm:gap-x-[22px] gap-x-[10px] max-sm:gap-[5px]" role="tablist" aria-label="Social form">
                <li>
                    <button type="button" role="tab" aria-selected="true" id="tabs-observacion_inicial" onclick="javascript:selectTab('observacion_inicial')" class="relative block py-[20px] px-[5px] text-light dark:text-subtitle-dark [&.active]:text-primary after:[&.active]:bg-primary after:absolute after:bottom-0 after:end-0 after:w-full after:h-[2px] after:bg-transparent after:transition-all after:duration-300 after:ease-in-out after:invisible [&.active]:after:visible font-medium active">Observación inicial</button>
                </li>
                <li>
                    <button type="button" role="tab" aria-selected="true" id="tabs-procedimientos" onclick="javascript:selectTab('procedimientos')" class="relative block py-[20px] px-[5px] text-light dark:text-subtitle-dark [&.active]:text-primary after:[&.active]:bg-primary after:absolute after:bottom-0 after:end-0 after:w-full after:h-[2px] after:bg-transparent after:transition-all after:duration-300 after:ease-in-out after:invisible [&.active]:after:visible font-medium">Procedimientos realizados</button>
                </li>
                <?php
                    foreach ($consultation->data->modules as $m) {?>
                        <li>
                            <button type="button" role="tab" aria-selected="true" id="tabs-<?= $m->code; ?>" onclick="javascript:selectTab('<?= $m->code; ?>')" class="relative block py-[20px] px-[5px] text-light dark:text-subtitle-dark [&.active]:text-primary after:[&.active]:bg-primary after:absolute after:bottom-0 after:end-0 after:w-full after:h-[2px] after:bg-transparent after:transition-all after:duration-300 after:ease-in-out after:invisible [&.active]:after:visible font-medium"><?= $m->name; ?></button>
                        </li>
                        <?php
                    }
                ?>
            </ul>
        </nav>
        
                <div class="transition-opacity duration-150 ease-linear opacity-100" role="tabpanel" aria-labelledby="tabs-observacion_inicial">
                    <div class="grid grid-cols-12 sm:gap-[25px] gap-y-[25px]">
                        <div class="col-span-12">
                            <div class="bg-white dark:bg-box-dark rounded-10 pt-[25px] px-[25px] pb-[30px] shadow-[0_5px_20px_rgba(173,181,217,0.05)] dark:shadow-none">
                                <div class="mb-[25px]">
                                    <h4 class="text-[20px] text-dark dark:text-subtitle-dark font-medium">Observaciones</h4>
                                    <span class="text-[14px] font-medium text-theme-gray dark:text-subtitle-dark">Motivo de consulta y observaciones iniciales del paciente</span>
                                </div>
                                <?php require __DIR__ . '/module_initial_observations.php'; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="transition-opacity duration-150 ease-linear opacity-100" role="tabpanel" aria-labelledby="tabs-procedimientos">
                    <div class="grid grid-cols-12 sm:gap-[25px] gap-y-[25px]">
                        <div class="col-span-12">
                            <div class="bg-white dark:bg-box-dark rounded-10 pt-[25px] px-[25px] pb-[30px] shadow-[0_5px_20px_rgba(173,181,217,0.05)] dark:shadow-none">
                                <div class="mb-[25px]">
                                    <h4 class="text-[20px] text-dark dark:text-subtitle-dark font-medium">Procedimientos realizados</h4>
                                    <span class="text-[14px] font-medium text-theme-gray dark:text-subtitle-dark">Procedimientos y tratamientos aplicados durante la consulta</span>
                                </div>
                                <?php require __DIR__ . '/module_procedures.php'; ?>
                            </div>
                        </div>
                    </div>
                </div>
        <?php
            foreach ($consultation->data->modules as $m) {
                if (!isset($moduleViews[$m->code])) {
                    continue;
                } ?>
                <div class="transition-opacity duration-150 ease-linear opacity-100" role="tabpanel" aria-labelledby="tabs-<?= $m->code; ?>">
                    <div class="grid grid-cols-12 sm:gap-[25px] gap-y-[25px]">
                        <div class="col-span-12">
                            <div class="bg-white dark:bg-box-dark rounded-10 pt-[25px] px-[25px] pb-[30px] shadow-[0_5px_20px_rgba(173,181,217,0.05)] dark:shadow-none">
                                <div class="mb-[25px]">
                                    <h4 class="text-[20px] text-dark dark:text-subtitle-dark font-medium"><?= $m->name; ?></h4>
                                    <span class="text-[14px] font-medium text-theme-gray dark:text-subtitle-dark"><?= $m->description; ?></span>
                                </div>
                <?php
                require __DIR__ . '/' . $moduleViews[$m->code]; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
        ?>
    </div>


        
    </div>
</div>

<script src="<?= asset('js/consultations/view.js'); ?>"></script>

<script>
    var currentLink = '<?= base_url('consultations'); ?>';
    var consultation_id = '<?= $id; ?>';
</script>