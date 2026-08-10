// JavaScript Document

var homeURL;

let consultationsProcedures = [];
let consultationsDiagnostics = [];
let consultationsSores = [];

let addingConsultationDiagnostic = false;
let addingConsultationSore = false;
let soreListIndex = 0;

let add_sore = false;

let pain_scale = 0;

let podiatricExplorationTimer = null;
let loadingPodiatricExploration = true;
let savePodiatricExplorationTimeoutSec = 10000;

let savingConsultationObservations = false;
let savingConsultationPodiatricExploration = false;
let savingConsultationProcedures = false;
let savingConsultationDiagnostics = false;
let savingConsultationSores = false;
let savingConsultationIndications = false;
let savingConsultationFinish = false;

let selectedTab = '';

let evidenceBeforeInput = null;
let evidenceBeforeDropzone = null;
let evidenceBeforePreviewContainer = null;
let evidenceAfterInput = null;
let evidenceAfterDropzone = null;
let evidenceAfterPreviewContainer = null;

function InitializeValues(home) {
	homeURL = home;

	if(window.consultationModules?.initialObservations) {
		$('#field-observaciones-iniciales').on('change', () => { saveConsultationObservations(); });
	}
	if(window.consultationModules?.podiatricExploration) {
		loadingPodiatricExploration = true;
		GetPodiatricExplorationCatalog();
		$('#select-tipo-pie').on('change', setPodiaticExplorationSaveTimer);
		$('#select-formula-metatarsal').on('change', setPodiaticExplorationSaveTimer);
		$('#field-alteraciones-marcha').on('change', setPodiaticExplorationSaveTimer);
		$('#select-pulso-pedio-izquierdo').on('change', setPodiaticExplorationSaveTimer);
		$('#select-pulso-pedio-derecho').on('change', setPodiaticExplorationSaveTimer);
		$('#select-sensibilidad-izquierdo').on('change', setPodiaticExplorationSaveTimer);
		$('#select-sensibilidad-derecho').on('change', setPodiaticExplorationSaveTimer);
		$('#select-temperatura-pies').on('change', setPodiaticExplorationSaveTimer);
		$('#select-coloracion-pies').on('change', setPodiaticExplorationSaveTimer);
		$('#field-exploracion-podologica-observaciones').on('change', setPodiaticExplorationSaveTimer);
		$('#field-exploracion-podologica-recomendaciones').on('change', setPodiaticExplorationSaveTimer);
	}
	if(window.consultationModules?.procedures) {
		GetProcedures();
		GetConsultationProcedures();
		$('#btn-add-procedure').on('click', addConsultationProcedure);
		$('#btn-save-procedures').on('click', () => { saveConsultationProcedures(); });
	}
	if(window.consultationModules?.diagnostic) {
		GetDiagnostics();
		GetDiagnosticTypes();
		GetConsultationDiagnostics();
		$('#btn-agregar-diagnostico').on('click', addConsultationDiagnostic);
		$('#btn-guardar-diagnostico').on('click', () => { saveConsultationDiagnostics(); });
	}
	if(window.consultationModules?.sore) {
		GetPodiatricSoresCatalog();
		GetConsultationSores();
		$('#btn-agregar-lesion').on('click', enableAddSore);
		$('#btn-agregar-captura-lesion').on('click', addConsultationSore);
		$('#btn-cancelar-captura-lesion').on('click', cancelAddSore);
		$('.nivel-dolor').on('click', function() {
			pain_scale = $(this).data('value');
			
			let activeClasses = 'bg-primary hover:bg-primary-hbr border-solid border-1 border-primary text-white';

			$('.nivel-dolor').each(function() {
				let current_value = $(this).data('value');

				if (current_value <= pain_scale) {
					$(this).addClass(activeClasses);
				} else {
					$(this).removeClass(activeClasses);
				}
			});
		});
		$('#btn-guardar-lesion').on('click', saveConsultationSores);
	}
	if(window.consultationModules?.evidence) {
		startEvidenceDropzone('#pic-evidencia-antes', '#pic-evidencia-antes-dropzone', 'antes');
		startEvidenceDropzone('#pic-evidencia-despues', '#pic-evidencia-despues-dropzone', 'despues');
	}
	if(window.consultationModules?.indications) {
		$('#field-indicaciones').on('change', () => { saveConsultationIndications(); });
	}
	if(window.consultationModules?.schedule) {
		initDatePicker('field-fecha-cita', function(date, formattedDate) {
			// GetAvailableSlots(formattedDate);
		});
	}
	$('#btn-consultation-end').on('click', endConsultation);
}

class ConsultationDiagnosticsClass {
	constructor(id, diagnostic_id, diagnostic_code, diagnostic, type_id, type_code, type, observations, action) {
		this.id = id;
		this.diagnostic_id = diagnostic_id;
		this.diagnostic_code = diagnostic_code;
		this.diagnostic = diagnostic;
		this.type_id = type_id;
		this.type_code = type_code;
		this.type = type;
		this.observations = observations;
		this.action = action;
	}
}

class ConsultationProceduresClass {
	constructor(id, procedure_id, procedure_code, procedure, quantity, unit_price, bonus, total, chargeable, origin, observations, action) {
		this.id = id;
		this.procedure_id = procedure_id;
		this.procedure_code = procedure_code;
		this.procedure = procedure;
		this.quantity = quantity;
		this.unit_price = unit_price;
		this.bonus = bonus;
		this.total = total;
		this.chargeable = chargeable;
		this.origin = origin;
		this.observations = observations;
		this.action = action;
	}
}

class ConsultationSoresClass {
	constructor(id, index, sore_type_id, sore_type_code, sore_type, laterality_id, laterality, location, length, width, depth, wagner_scale_id, wagner_scale, 
		tissue_id, tissue, evolution_id, evolution, exudate_id, exudate, exudate_color_id, exudate_color, infection_signs, pain_scale, observations, action) {
		this.id = id;
		this.index = index;
		this.sore_type_id = sore_type_id;
		this.sore_type_code = sore_type_code;
		this.sore_type = sore_type;
		this.laterality_id = laterality_id;
		this.laterality = laterality;
		this.location = location;
		this.length = length;
		this.width = width;
		this.depth = depth;
		this.wagner_scale_id = wagner_scale_id;
		this.wagner_scale = wagner_scale;
		this.tissue_id = tissue_id;
		this.tissue = tissue;
		this.evolution_id = evolution_id;
		this.evolution = evolution;
		this.exudate_id = exudate_id;
		this.exudate = exudate;
		this.exudate_color_id = exudate_color_id;
		this.exudate_color = exudate_color;
		this.infection_signs = infection_signs;
		this.pain_scale = pain_scale;
		this.observations = observations;
		this.action = action;
	}
}

function startEvidenceDropzone(input, dropzone, type) {
	$(document).on('change', input, function () {
		handleEvidencePhotos(this.files, type);
		$(this).val('');
	});

	$(document).on('dragover', dropzone, function (e) {
		e.preventDefault();
		e.stopPropagation();

		$(this).addClass('drag-active');
	});

	$(document).on('dragleave', dropzone, function (e) {
		e.preventDefault();
		e.stopPropagation();

		$(this).removeClass('drag-active');
	});

	$(document).on('drop', dropzone, function (e) {
		e.preventDefault();
		e.stopPropagation();

		$(this).removeClass('drag-active');

		const files = e.originalEvent.dataTransfer.files;

		handleEvidencePhotos(files, type);
	});
}

function handleEvidencePhotos(files, type) {
    $.each(files, function (_, file) {
        if (!file.type.startsWith('image/')) {
			ShowToastMessage('Solo se permiten imagenes.', 'error');
            return true;
        }

        uploadEvidencePhoto(file, type);
    });
}

function uploadEvidencePhoto(file, type) {
    const tempId = 'photo_' + Date.now() + '_' + Math.floor(Math.random() * 10000);
    const previewUrl = URL.createObjectURL(file);

	let preview_sector_id = '';
	if(type == 'antes')
		preview_sector_id = '.sector-imagenes-antes';
	else
		preview_sector_id = '.sector-imagenes-despues';

    $(preview_sector_id).append(`
		<div id="${tempId}" class="col-span-12">
			<div class="flex items-center gap-x-[20px] gap-y-[10px]">
				<figure class="min-w-[80px] w-[80px] h-[80px] flex items-center justify-center bg-regular dark:bg-box-dark-up rounded-4 p-[5px]">
					<img class="object-cover placeholder-image" src="${previewUrl}" alt="">
				</figure>
				<figcaption class="flex items-center justify-between w-full gap-x-[15px] gap-y-[5px]">
					<h6 class="capitalize leading-[21px] text-dark dark:text-title-dark text-[15px] font-medium"></h6>
					<button type="button" class="w-[38px] h-[38px] flex items-center text-light-extra hover:bg-danger/10 justify-center text-[18px] cursor-pointer remove-event-wrapper uil uil-trash-alt hover:text-danger rounded-full">
					</button>
				</figcaption>
			</div>
		</div>
    `);

    const formData = new FormData();
    formData.append('evidence', file);
    formData.append('type', type);

    $.ajax({
        url: `${homeURL}/api/consultations/${consultation_id}/evidence-upload`,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json',

        success: function (response) {
			console.log(response);
            if (!response.success) {
                showUploadError(tempId, response.message || 'Error al subir');
                return;
            }

            $('#' + tempId).html(`
				<div class="flex items-center gap-x-[20px] gap-y-[10px]">
					<figure class="min-w-[80px] w-[80px] h-[80px] flex items-center justify-center bg-regular dark:bg-box-dark-up rounded-4 p-[5px]">
						<img class="object-cover placeholder-image" src="${response.data.thumbnail_url}" alt="">
					</figure>
					<figcaption class="flex items-center justify-between w-full gap-x-[15px] gap-y-[5px]">
						<h6 class="capitalize leading-[21px] text-dark dark:text-title-dark text-[15px] font-medium"></h6>
						<button type="button" data-photo-uuid="${response.data.photo_uuid}" class="w-[38px] h-[38px] flex items-center text-light-extra hover:bg-danger/10 justify-center text-[18px] cursor-pointer remove-event-wrapper uil uil-trash-alt hover:text-danger rounded-full">
						</button>
					</figcaption>
				</div>
            `);
        },

        error: function(XMLHttpRequest, textStatus, errorThrown) { 
			try {
				let response = JSON.parse(XMLHttpRequest.responseText);
				ShowToastMessage(response.message, 'error')
			} catch(e) {
				ShowToastMessage(XMLHttpRequest.responseText, 'error');
			}

            showUploadError(tempId, message);
        },

        complete: function () {
            URL.revokeObjectURL(previewUrl);
        }
    });
}

function showUploadError(tempId, message) {
    $('#' + tempId).html(`
			<div class="flex items-center gap-x-[20px] gap-y-[10px]">
				<figure class="min-w-[80px] w-[80px] h-[80px] flex items-center justify-center bg-regular dark:bg-box-dark-up rounded-4 p-[5px]">
					<img class="object-cover placeholder-image" src="" alt="">
				</figure>
				<figcaption class="flex items-center justify-between w-full gap-x-[15px] gap-y-[5px]">
					<h6 class="capitalize leading-[21px] text-dark dark:text-title-dark text-[15px] font-medium">Error al Subir Imagen</h6>
				</figcaption>
			</div>
    `);
}

function setPodiaticExplorationSaveTimer() {
	if(!loadingPodiatricExploration) {
		clearTimeout(podiatricExplorationTimer);

		podiatricExplorationTimer = setTimeout(() => {
			saveConsultationPodriaticExploration();
			podiatricExplorationTimer = null;
		}, savePodiatricExplorationTimeoutSec);
	}
}

function selectTab(tab) {
	if(selectedTab == 'exploracion_podologica') {
		saveConsultationPodriaticExploration();
	}
	selectedTab = tab;
}

function enableAddSore() {
	if(!add_sore) {
		add_sore = true;
		if($('#fs-agregar-lesion').hasClass('hidden'))
			$('#fs-agregar-lesion').removeClass('hidden');
	}
}

function cancelAddSore() {
	if(add_sore) {
		add_sore = false;
		if(!$('#fs-agregar-lesion').hasClass('hidden'))
			$('#fs-agregar-lesion').addClass('hidden');
	}
}

function GetConsultationProcedures() {
	$('#table-consultation-procedures tbody').empty();
	consultationsProcedures = [];
	$.ajax({
        url: `${homeURL}/api/consultations/${consultation_id}/procedures`,
		type: 'get',
		dataType: "json",
		success: function(response) {
			// console.log(response);
			if(response.success) {
				$.each(response.data.procedures, function(k, v) {
					consultationsProcedures.push(new ConsultationProceduresClass(
						v.id,
						v.procedure_id,
						v.procedure_code,
						v.procedure,
						v.quantity,
						v.unit_price,
						v.bonus,
						v.total,
						v.chargeable,
						v.origin,
						v.observations,
						'no_changes',
					));
					appendProcedureRow(v.id,
									v.procedure_id,
									v.procedure_code,
									v.procedure,
									v.quantity,
									v.unit_price,
									v.bonus,
									v.total,
									v.chargeable,
									v.origin,
									v.observations);
				});
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) { 
			try {
				let response = JSON.parse(XMLHttpRequest.responseText);
				ShowToastMessage(response.message, 'error')
			} catch(e) {
				ShowToastMessage(XMLHttpRequest.responseText, 'error');
			}
		}  
	});
}

function GetConsultationDiagnostics() {
	$('#table-diagnosticos tbody').empty();
	$('#field-diagnostico-observaciones').val('');
	consultationsDiagnostics = [];
	$.ajax({
        url: `${homeURL}/api/consultations/${consultation_id}/diagnostics`,
		type: 'get',
		dataType: "json",
		success: function(response) {
			// console.log(response);
			if(response.success) {
				$('#field-diagnostico-observaciones').val(response.data.diagnostic_summary);
				$.each(response.data.consultation_diagnostics, function(k, v) {
					consultationsDiagnostics.push(new ConsultationDiagnosticsClass(
						v.id,
						v.diagnostic_id,
						v.diagnostic_code,
						v.diagnostic,
						v.type_id,
						v.type_code,
						v.type,
						v.observations,
						'no_changes',
					));
					appendDiagnosticRow(v.id,
									v.diagnostic_id,
									v.diagnostic_code,
									v.diagnostic,
									v.type_id,
									v.type_code,
									v.type,
									v.observations);
				});
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) { 
			try {
				let response = JSON.parse(XMLHttpRequest.responseText);
				ShowToastMessage(response.message, 'error')
			} catch(e) {
				ShowToastMessage(XMLHttpRequest.responseText, 'error');
			}
		}  
	});
}

function GetConsultationSores() {
	$('#table-lesiones tbody').empty();
	consultationsSores = [];
	$.ajax({
        url: `${homeURL}/api/consultations/${consultation_id}/sores`,
		type: 'get',
		dataType: "json",
		success: function(response) {
			console.log(response);
			if(response.success) {
				$.each(response.data.sores, function(k, v) {
					consultationsSores.push(new ConsultationSoresClass(
						v.id,
						v.id,
						v.sore_id,
						v.sore_code,
						v.sore,
						v.laterality_id,
						v.laterality,
						v.location,
						v.length,
						v.width,
						v.depth,
						v.wagner_scale_id,
						v.wagner_scale,
						v.tissue_id,
						v.tissue,
						v.evolution_id,
						v.evolution,
						v.exudate_id,
						v.exudate,
						v.exudate_color_id,
						v.exudate_color,
						v.infection_signs,
						v.pain_scale,
						v.observations,
						'no_changes'
					));
					console.log(consultationsSores);
					appendSoreRow(v.id,
									v.sore,
									v.laterality,
									v.location,
									v.length,
									v.width,
									v.depth,
									v.wagner_scale != '' ? v.wagner_scale : 'N/A',
									v.tissue != '' ? v.tissue : 'N/A',
									v.evolution != '' ? v.evolution : 'N/A',
									v.exudate != '' ? v.exudate : 'N/A',
									v.exudate_color != '' ? v.exudate_color : 'N/A',
									v.infection_signs,
									v.pain_scale,
									v.observations);
				});
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) { 
			try {
				let response = JSON.parse(XMLHttpRequest.responseText);
				ShowToastMessage(response.message, 'error')
			} catch(e) {
				ShowToastMessage(XMLHttpRequest.responseText, 'error');
			}
		}  
	});
}

function changeProcedureQuantity(id) {
	consultationsProcedures.forEach((v, i) => {
		if(id == v.procedure_id) {
			if(v.action != 'add')
				v.action = 'modify';
			v.quantity = parseInt($(`#field-procedure-quantity-${v.procedure_id}`).val());
			v.total = v.unit_price * v.quantity;
			if(v.chargeable)
				$(`#td-total-${v.procedure_id}`).html(accounting.formatMoney(v.total));
		}
	});
}

function setChargeable(id) {
	consultationsProcedures.forEach((v, i) => {
		if(id == v.procedure_id) {
			if(v.action != 'add')
				v.action = 'modify';
			v.chargeable = $(`#cbx-chargeable-${v.procedure_id}`).prop('checked') ? 1 : 0;
			if(v.chargeable == 1) {
				$(`#td-total-${v.procedure_id}`).html(accounting.formatMoney(v.total));
			} else {
				$(`#td-total-${v.procedure_id}`).html(accounting.formatMoney(0));
			}
		}
	});
}

function addConsultationDiagnostic() {
	if(!addingConsultationDiagnostic) {
		let added = false;
		let diagnostic_id = $('#select-diagnostico').val();
		consultationsDiagnostics.forEach((v, i) => {
			if(diagnostic_id == v.diagnostic_id) {
				if(v.action == 'remove') {
					if(v.id == null)
						v.action = 'add';
					else
						v.action = 'modify';
					appendDiagnosticRow(v.id,
									v.diagnostic_id,
									v.diagnostic_code,
									v.diagnostic,
									v.type_id,
									v.type_code,
									v.type,
									v.observations);
				}
				added = true;
			}
		});
		if(!added) {
			let selected_diagnostic_type_id = $('#select-diagnostico-tipo option:selected').val();
			let selected_diagnostic_type_code = $('#select-diagnostico-tipo option:selected').data('code');
			let selected_diagnostic_type_text = $('#select-diagnostico-tipo option:selected').text();
			$.ajax({
				url: `${homeURL}/api/consultations/${consultation_id}/diagnostics/${diagnostic_id}/resolve`,
				type: 'get',
				dataType: "json",
				success: function(response) {
					addingConsultationDiagnostic = false;
					// console.log(response);
					if(response.success) {
						consultationsDiagnostics.push(new ConsultationDiagnosticsClass(
							null,
							response.data.id,
							response.data.code,
							response.data.diagnostic,
							selected_diagnostic_type_id,
							selected_diagnostic_type_code,
							selected_diagnostic_type_text,
							'',
							'add',
						));
						appendDiagnosticRow(null,
							response.data.id,
							response.data.code,
							response.data.diagnostic,
							selected_diagnostic_type_id,
							selected_diagnostic_type_code,
							selected_diagnostic_type_text,
							''
						)
					}
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					addingConsultationDiagnostic = false;
					try {
						let response = JSON.parse(XMLHttpRequest.responseText);
						ShowToastMessage(response.message, 'error')
					} catch(e) {
						ShowToastMessage(XMLHttpRequest.responseText, 'error');
					}
				}  
			});
		} else {
			addingConsultationDiagnostic = false;
		}
	}
}

function changeDiagnosticObservation(id) {
	consultationsDiagnostics.forEach((v, i) => {
		if(id == v.diagnostic_id) {
			v.observations = $(`#field-diagnostic-observation-${id}`).html();
			return;
		}
	});
}

function appendDiagnosticRow(id, diagnostic_id, diagnostic_code, diagnostic, type_id, type_code, type_text, observations) {
	$('#table-diagnosticos tbody').append(`
        							<tr id="tr-diagnostic-${diagnostic_id}" class="group">
										<td class="ps-[25px] pe-4 py-2.5 text-start last:text-end group-hover:bg-transparent border-none before:hidden rounded-s-[4px]">
                                            <div class="flex flex-col gap-x-[20px] gap-y-[0px]">
                                                    <h6 class="capitalize mb-[8px] leading-[21px] text-dark dark:text-title-dark text-[15px] font-medium">${diagnostic}</h6>
                                            </div>
										</td>
										<td id="td-unit-price-${diagnostic_id}" class="px-4 py-2.5 font-normal text-center capitalize text-[14px] text-light dark:text-subtitle-dark border-none group-hover:bg-transparent">
										    ${type_text}
										</td>
										<td id="td-quantity-${diagnostic_id}" class="px-4 py-2.5 font-normal text-center capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
											<div id="field-diagnostic-observation-${diagnostic_id}" contenteditable="true" onblur="javascript:changeDiagnosticObservation('${diagnostic_id}')" class="span-editable text-body dark:text-subtitle-dark text-[13px]">${observations ?? ''}</span>
										</td>
										<td class="px-4 py-2.5 font-normal text-center capitalize border-none group-hover:bg-transparent">
                                            <div class="flex items-center justify-center">
                                                <button type="button" onclick="removeConsultationDiagnostic('${diagnostic_id}');" class="w-[38px] h-[38px] flex items-center text-light-extra hover:bg-danger/10 justify-center text-[18px] cursor-pointer remove-event-wrapper uil uil-trash-alt hover:text-danger rounded-full">
                                                </button>
                                            </div>
										</td>
									</tr>
					`);
}

function removeConsultationDiagnostic(id) {
	consultationsDiagnostics.forEach((v, i) => {
		if(id == v.diagnostic_id && v.action != 'remove') {
			v.action = 'remove';
			$(`#tr-diagnostic-${id}`).remove();
			if(v.id == null) {
				console.log(consultationsDiagnostics);
				consultationsDiagnostics.splice(i, 1);
				console.log(consultationsDiagnostics);
			}
			return;
		}
	});
}

function addConsultationProcedure() {
	let added = false;
	let procedure_id = $('#select-search-procedure').val();
	consultationsProcedures.forEach((v, i) => {
		if(procedure_id == v.procedure_id) {
			if(v.action == 'remove') {
				if(v.id == null)
					v.action = 'add';
				else
					v.action = 'modify';
				appendProcedureRow(v.id,
								v.procedure_id,
								v.procedure_code,
								v.procedure,
								v.quantity,
								v.unit_price,
								v.bonus,
								v.total,
								v.chargeable,
								v.origin,
								v.observations);
			}
			added = true;
		}
	});
	if(!added) {
		$.ajax({
			url: `${homeURL}/api/consultations/${consultation_id}/procedures/${procedure_id}/resolve`,
			type: 'get',
			dataType: "json",
			success: function(response) {
				// console.log(response);
				if(response.success) {
					consultationsProcedures.push(new ConsultationProceduresClass(
						null,
						response.data.id,
						response.data.code,
						response.data.procedure,
						1,
						parseFloat(response.data.cost),
						0,
						parseFloat(response.data.cost),
						1,
						'manual',
						'',
						'add',
					));
					appendProcedureRow(null,
						response.data.id,
						response.data.code,
						response.data.procedure,
						1,
						parseFloat(response.data.cost),
						0,
						parseFloat(response.data.cost),
						1,
						'manual',
						''
					)
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) { 
				try {
					let response = JSON.parse(XMLHttpRequest.responseText);
					ShowToastMessage(response.message, 'error')
				} catch(e) {
					ShowToastMessage(XMLHttpRequest.responseText, 'error');
				}
			}  
		});
	}
}

function appendProcedureRow(id, procedure_id, procedure_code, procedure, quantity, unit_price, bonus, total, chargeable, origin, observations) {
	let remove_button = '';
	if(origin != 'agendado')
		remove_button = `<div class="flex items-center justify-center">
							<button type="button" onclick="removeConsultationProcedure('${procedure_id}');" class="w-[38px] h-[38px] flex items-center text-light-extra hover:bg-danger/10 justify-center text-[18px] cursor-pointer remove-event-wrapper uil uil-trash-alt hover:text-danger rounded-full">
							</button>
						</div>`;
	$('#table-consultation-procedures tbody').append(`
        							<tr id="tr-procedure-${procedure_id}" class="group">
										<td class="ps-[25px] pe-4 py-2.5 text-start last:text-end group-hover:bg-transparent border-none before:hidden rounded-s-[4px]">
                                            <div class="flex flex-col gap-x-[20px] gap-y-[0px]">
                                                    <h6 class="capitalize mb-[8px] leading-[21px] text-dark dark:text-title-dark text-[15px] font-medium">${procedure}</h6>
                                                    <div>
                                                            <span class="text-dark dark:text-title-dark me-[5px] text-[14px] font-medium">Observaciones:</span>
                                                            <div contenteditable="true"  class="span-editable text-body dark:text-subtitle-dark text-[13px]">${observations ?? ''}</span>
                                                    </div>
                                            </div>
										</td>
										<td id="td-unit-price-${procedure_id}" class="px-4 py-2.5 font-normal text-center capitalize text-[14px] text-light dark:text-subtitle-dark border-none group-hover:bg-transparent">
										    ${accounting.formatMoney(unit_price)}
										</td>
										<td id="td-quantity-${procedure_id}" class="px-4 py-2.5 font-normal text-center capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                            <input type="number"
													min="1"
													value="${quantity}"
													id="field-procedure-quantity-${procedure_id}"
													onchange="javascript:changeProcedureQuantity('${procedure_id}');"
													class="productItemsNumber bg-transparent 2xl:ps-[12px] w-[50px] text-[14px] font-medium text-dark dark:text-title-dark placeholder:text-dark dark:placeholder:text-title-dark border-none shadow-none appearance-none outline-none text-center">
										</td>
										<td id="td-total-${procedure_id}" class="ps-4 pe-[25px] py-2.5 font-normal text-center capitalize text-[14px] text-primary border-none group-hover:bg-transparent rounded-e-[4px]">
										    ${chargeable == 1 ? accounting.formatMoney(total) : accounting.formatMoney(0)}
										</td>
										<td class="ps-4 pe-[25px] py-2.5 font-normal text-center capitalize text-[14px] text-primary border-none group-hover:bg-transparent rounded-e-[4px]">
											<input 
												class="relative text-center h-[1.125rem] w-[1.125rem] appearance-none rounded-[0.25rem] border-1 border-solid border-normal outline-none before:pointer-events-none before:absolute before:h-[10px] before:w-[0.5px] before:scale-0 before:rounded-full before:bg-transparent before:opacity-0 before:content-[''] checked:border-primary checked:bg-primary checked:before:opacity-[0.16] checked:after:absolute checked:after:mt-0 checked:after:ms-[5px] checked:after:block checked:after:h-[10px] checked:after:w-[5px] checked:after:rotate-45 checked:after:border-[0.125rem] checked:after:border-l-0 checked:after:border-t-0 checked:after:border-solid checked:after:border-white checked:after:bg-transparent checked:after:content-[''] hover:cursor-pointer hover:before:opacity-[0.04] dark:border-white/10 dark:checked:border-primary dark:checked:bg-primary after:top-[2px]"
												type="checkbox"
												value=""
												id="cbx-chargeable-${procedure_id}"
												onchange="javascript:setChargeable('${procedure_id}');"
												${chargeable == 1 ? 'checked' : ''} />
										</td>
										<td class="px-4 py-2.5 font-normal text-center capitalize border-none group-hover:bg-transparent">
                                            ${remove_button}
										</td>
									</tr>
					`);
}

function removeConsultationProcedure(id) {
	consultationsProcedures.forEach((v, i) => {
		if(id == v.procedure_id && v.action != 'remove') {
			v.action = 'remove';
			$(`#tr-procedure-${id}`).remove();
			if(v.id == null) {
				console.log(consultationsProcedures);
				consultationsProcedures.splice(i, 1);
				console.log(consultationsProcedures);
			}
			return;
		}
	});
}

function addConsultationSore() {
	let csc = new ConsultationSoresClass(
		null,
		soreListIndex++,
		$('#select-tipo-lesion').val(),
		$('#select-tipo-lesion option:selected').data('code'),
		$('#select-tipo-lesion option:selected').text(),
		$('#select-lesion-pie').val(),
		$('#select-lesion-pie option:selected').text(),
		$('#field-lesion-ubicacion').val(),
		$('#field-lesion-largo').val(),
		$('#field-lesion-ancho').val(),
		$('#field-lesion-profundidad').val(),
		$('#select-lesion-grado-wagner').val(),
		$('#select-lesion-grado-wagner option:selected').text(),
		$('#select-lesion-tipo-tejido').val(),
		$('#select-lesion-tipo-tejido option:selected').text(),
		$('#select-lesion-evolucion').val(),
		$('#select-lesion-evolucion option:selected').text(),
		$('#select-lesion-exudado').val(),
		$('#select-lesion-exudado option:selected').text(),
		$('#select-lesion-color-exudado').val(),
		$('#select-lesion-color-exudado option:selected').text(),
		$('#chk-lesion-signos-infeccion').is(':checked') ? 1 : 0,
		pain_scale,
		$('#field-lesion-observaciones').val(),
		'add'
	);
	consultationsSores.push(csc);
	appendSoreRow(csc.index,
					csc.sore_type,
					csc.laterality,
					csc.location,
					csc.length,
					csc.width,
					csc.depth,
					csc.wagner_scale,
					csc.tissue,
					csc.evolution,
					csc.exudate,
					csc.exudate_color,
					csc.infection_signs,
					csc.pain_scale,
					csc.observations);
	cancelAddSore();
}

function appendSoreRow(index, sore_type, laterality, location, length, width, depth, wagner_scale, tissue, evolution, exudate, exudate_color, infection_signs, pain_scale, observations) {
	$('#table-lesiones tbody').append(`
        							<tr id="tr-sore-${index}" class="group">
										<td class="ps-[25px] pe-4 py-2.5 text-start last:text-end group-hover:bg-transparent border-none before:hidden rounded-s-[4px]">
                                            <div class="flex flex-col gap-x-[20px] gap-y-[0px]">
                                                    <h6 class="capitalize mb-[8px] leading-[21px] text-dark dark:text-title-dark text-[15px] font-medium">${sore_type}</h6>
													<div>
														<span class="text-dark dark:text-title-dark me-[5px] text-[14px] font-medium">Nivel Dolor:</span>
														<span class="text-body dark:text-subtitle-dark text-[13px]">${pain_scale}</span>
													</div>
													${infection_signs != 0 ? '<div><span class="text-danger dark:text-title-dark me-[5px] text-[14px] font-medium">Presenta signos de infección</span></div>' : ''}
                                            </div>
										</td>
										<td class="px-4 py-2.5 font-normal text-center capitalize text-[14px] text-light dark:text-subtitle-dark border-none group-hover:bg-transparent">
										    ${laterality}
											${tissue != 'N/A' ? '<div><span class="text-dark dark:text-title-dark me-[5px] text-[14px] font-medium">Tejido:</span><div class="text-body dark:text-subtitle-dark text-[13px]">' + tissue + '</div></div>' : ''}
											${wagner_scale != 'N/A' ? '<div><span class="text-dark dark:text-title-dark me-[5px] text-[14px] font-medium">Grado Wagner:</span><div class="text-body dark:text-subtitle-dark text-[13px]">' + wagner_scale + '</div></div>' : ''}
										</td>
										<td class="px-4 py-2.5 font-normal text-center capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                            <div id="field-sore-location-${index}" contenteditable="true" onblur="javascript:changeSoreLocation('${index}')" class="span-editable text-body dark:text-subtitle-dark text-[13px]">${location ?? ''}</div>
											${evolution != 'N/A' ? '<div><span class="text-dark dark:text-title-dark me-[5px] text-[14px] font-medium">Evolución:</span><div class="text-body dark:text-subtitle-dark text-[13px]">' + evolution + '</div></div>' : ''}
										</td>
										<td id="td-total-${index}" class="ps-4 pe-[25px] py-2.5 font-normal text-center capitalize text-[14px] text-primary border-none group-hover:bg-transparent rounded-e-[4px]">
										    ${length ? 'l: ' + length + 'cm' : ''} ${width ? 'a: ' + width + 'cm' : ''} ${depth ? 'p: ' + depth + 'cm' : ''}
											${exudate != 'N/A' ? '<div><span class="text-dark dark:text-title-dark me-[5px] text-[14px] font-medium">Exudado:</span><div class="text-body dark:text-subtitle-dark text-[13px]">' + exudate + '</div></div>' : ''}
											${exudate_color != 'N/A' ? '<div><span class="text-dark dark:text-title-dark me-[5px] text-[14px] font-medium">Color Exudado:</span><div class="text-body dark:text-subtitle-dark text-[13px]">' + exudate_color + '</div></div>' : ''}
										</td>
										<td class="ps-4 pe-[25px] py-2.5 font-normal text-center capitalize text-[14px] text-primary border-none group-hover:bg-transparent rounded-e-[4px]">
											<div id="field-sore-observation-${index}" contenteditable="true" onblur="javascript:changeSoreObservation('${index}')" class="span-editable text-body dark:text-subtitle-dark text-[13px]">${observations ?? ''}</span>
										</td>
										<td class="px-4 py-2.5 font-normal text-center capitalize border-none group-hover:bg-transparent">
                                            <div class="flex items-center justify-center">
                                                <button type="button" onclick="removeConsultationSore('${index}');" class="w-[38px] h-[38px] flex items-center text-light-extra hover:bg-danger/10 justify-center text-[18px] cursor-pointer remove-event-wrapper uil uil-trash-alt hover:text-danger rounded-full">
                                                </button>
                                            </div>
										</td>
									</tr>
					`);
}

function changeSoreLocation(id) {
	consultationsSores.forEach((v, i) => {
		if(id == v.index) {
			if(v.action != 'add')
				v.action = 'modify';
			v.location = $(`#field-sore-location-${id}`).html();
			return;
		}
	});
}

function changeSoreObservation(id) {
	consultationsSores.forEach((v, i) => {
		if(id == v.index) {
			if(v.action != 'add')
				v.action = 'modify';
			v.observations = $(`#field-sore-observation-${id}`).html();
			return;
		}
	});
}

function removeConsultationSore(id) {
	consultationsSores.forEach((v, i) => {
		if(id == v.index && v.action != 'remove') {
			v.action = 'remove';
			$(`#tr-sore-${id}`).remove();
			if(v.id == null) {
				consultationsSores.splice(i, 1);
			}
			return;
		}
	});
}

function GetPodiatricSoresCatalog() {
	$.ajax({
        url: `${homeURL}/api/catalog/podiatry/podiatric-sores`,
		type: 'get',
		dataType: "json",
		success: function(response) {
			// console.log(response);
			if(response.success) {
				$.each(response.data.sore_types, function(k, v) {
					$('#select-tipo-lesion').append($('<option>', {
						value: v.id,
						text: v.tipo,
						'data-code': v.codigo
					}));
				});
        		refreshSelectOption('select-tipo-lesion');
				$('#select-tipo-lesion').trigger('change');
				
				$.each(response.data.lateralities, function(k, v) {
					$('#select-lesion-pie').append($('<option>', {
						value: v.id,
						text: v.lateralidad,
						'data-code': v.codigo
					}));
				});
        		refreshSelectOption('select-lesion-pie');
				$('#select-lesion-pie').trigger('change');
				
				$('#select-lesion-grado-wagner').append($('<option>', { value: 0, text: 'N/A', 'data-code': 'n/a' }));
				$.each(response.data.wagner_scale, function(k, v) {
					$('#select-lesion-grado-wagner').append($('<option>', {
						value: v.id,
						text: v.grado,
						'data-code': v.codigo
					}));
				});
        		refreshSelectOption('select-lesion-grado-wagner');
				$('#select-lesion-grado-wagner').trigger('change');
				
				$('#select-lesion-tipo-tejido').append($('<option>', { value: 0, text: 'N/A', 'data-code': 'n/a' }));
				$.each(response.data.tissue_types, function(k, v) {
					$('#select-lesion-tipo-tejido').append($('<option>', {
						value: v.id,
						text: v.tipo,
						'data-code': v.codigo
					}));
				});
        		refreshSelectOption('select-lesion-tipo-tejido');
				$('#select-lesion-tipo-tejido').trigger('change');
				
				$('#select-lesion-evolucion').append($('<option>', { value: 0, text: 'N/A', 'data-code': 'n/a' }));
				$.each(response.data.evolution_types, function(k, v) {
					$('#select-lesion-evolucion').append($('<option>', {
						value: v.id,
						text: v.tipo,
						'data-code': v.codigo
					}));
				});
        		refreshSelectOption('select-lesion-evolucion');
				$('#select-lesion-evolucion').trigger('change');
				
				$('#select-lesion-exudado').append($('<option>', { value: 0, text: 'N/A', 'data-code': 'n/a' }));
				$.each(response.data.exudate_types, function(k, v) {
					$('#select-lesion-exudado').append($('<option>', {
						value: v.id,
						text: v.tipo,
						'data-code': v.codigo
					}));
				});
        		refreshSelectOption('select-lesion-exudado');
				$('#select-lesion-exudado').trigger('change');
				
				$('#select-lesion-color-exudado').append($('<option>', { value: 0, text: 'N/A', 'data-code': 'n/a' }));
				$.each(response.data.exudate_colors, function(k, v) {
					$('#select-lesion-color-exudado').append($('<option>', {
						value: v.id,
						text: v.color,
						'data-code': v.codigo
					}));
				});
        		refreshSelectOption('select-lesion-color-exudado');
				$('#select-lesion-color-exudado').trigger('change');
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) { 
			try {
				let response = JSON.parse(XMLHttpRequest.responseText);
				ShowToastMessage(response.message, 'error')
			} catch(e) {
				ShowToastMessage(XMLHttpRequest.responseText, 'error');
			}
		}  
	});
}

function GetPodiatricExplorationCatalog() {
	$.ajax({
        url: `${homeURL}/api/catalog/podiatry/podiatric-exploration`,
		type: 'get',
		dataType: "json",
		success: function(response) {
			if(response.success) {
				$('#select-tipo-pie').empty();
				$('#select-tipo-pie').append($('<option>', { value: 0, text: 'N/A' }));
				$.each(response.data.foot_types, function(k, v) {
					$('#select-tipo-pie').append($('<option>', {
						value: v.id,
						text: v.tipo
					}));
				});
        		refreshSelectOption('select-tipo-pie');
				$('#select-tipo-pie').trigger('change');
				
				$('#select-pulso-pedio-izquierdo').append($('<option>', { value: 0, text: 'N/A' }));
				$('#select-pulso-pedio-derecho').append($('<option>', { value: 0, text: 'N/A' }));
				$.each(response.data.pulse_types, function(k, v) {
					$('#select-pulso-pedio-izquierdo').append($('<option>', {
						value: v.id,
						text: v.tipo
					}));
					$('#select-pulso-pedio-derecho').append($('<option>', {
						value: v.id,
						text: v.tipo
					}));
				});
        		refreshSelectOption('select-pulso-pedio-izquierdo');
        		refreshSelectOption('select-pulso-pedio-derecho');
				$('#select-pulso-pedio-izquierdo').trigger('change');
				$('#select-pulso-pedio-derecho').trigger('change');

				
				$('#select-sensibilidad-izquierdo').append($('<option>', { value: 0, text: 'N/A' }));
				$('#select-sensibilidad-derecho').append($('<option>', { value: 0, text: 'N/A' }));
				$.each(response.data.sensitivity_types, function(k, v) {
					$('#select-sensibilidad-izquierdo').append($('<option>', {
						value: v.id,
						text: v.tipo
					}));
					$('#select-sensibilidad-derecho').append($('<option>', {
						value: v.id,
						text: v.tipo
					}));
				});
        		refreshSelectOption('select-sensibilidad-izquierdo');
        		refreshSelectOption('select-sensibilidad-derecho');
				$('#select-sensibilidad-izquierdo').trigger('change');
				$('#select-sensibilidad-derecho').trigger('change');
				
				$('#select-temperatura-pies').append($('<option>', { value: 0, text: 'N/A' }));
				$.each(response.data.temperature_types, function(k, v) {
					$('#select-temperatura-pies').append($('<option>', {
						value: v.id,
						text: v.tipo
					}));
				});
        		refreshSelectOption('select-temperatura-pies');
				$('#select-temperatura-pies').trigger('change');
				
				$('#select-coloracion-pies').append($('<option>', { value: 0, text: 'N/A' }));
				$.each(response.data.foot_color_types, function(k, v) {
					$('#select-coloracion-pies').append($('<option>', {
						value: v.id,
						text: v.tipo
					}));
				});
        		refreshSelectOption('select-coloracion-pies');
				$('#select-coloracion-pies').trigger('change');
				
				$('#select-formula-metatarsal').append($('<option>', { value: 0, text: 'N/A' }));
				$.each(response.data.metatarsal_formulas, function(k, v) {
					$('#select-formula-metatarsal').append($('<option>', {
						value: v.id,
						text: v.formula
					}));
				});
        		refreshSelectOption('select-formula-metatarsal');
				$('#select-formula-metatarsal').trigger('change');

				clearTimeout(podiatricExplorationTimer);
				podiatricExplorationTimer = null;
				GetPodiatricExplorationData();
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) { 
			try {
				let response = JSON.parse(XMLHttpRequest.responseText);
				ShowToastMessage(response.message, 'error')
			} catch(e) {
				ShowToastMessage(XMLHttpRequest.responseText, 'error');
			}
		}  
	});
}

function refreshSelect() {

}

function GetPodiatricExplorationData() {
	$.ajax({
        url: `${homeURL}/api/consultations/${consultation_id}/podiatric-exploration`,
		type: 'get',
		dataType: "json",
		success: function(response) {
			console.log(response);
			if(response.success) {
				// alert($('#select-tipo-pie option[value="' + response.data.foot_type + '"]').length);
				$('#select-tipo-pie').val(response.data.foot_type);
				refreshSelectOption('select-tipo-pie');
				$('#select-formula-metatarsal').val(response.data.metatarsal_formula);
				refreshSelectOption('select-formula-metatarsal');
				$('#field-alteraciones-marcha').val(response.data.gait_disorder);
				$('#select-pulso-pedio-izquierdo').val(response.data.left_pulse_type);
				refreshSelectOption('select-pulso-pedio-izquierdo');
				$('#select-pulso-pedio-derecho').val(response.data.right_pulse_type);
				refreshSelectOption('select-pulso-pedio-derecho');
				$('#select-sensibilidad-izquierdo').val(response.data.left_sensitivity_type);
				refreshSelectOption('select-sensibilidad-izquierdo');
				$('#select-sensibilidad-derecho').val(response.data.right_sensitivity_type);
				refreshSelectOption('select-sensibilidad-derecho');
				$('#select-temperatura-pies').val(response.data.temperature_type);
				refreshSelectOption('select-temperatura-pies');
				$('#select-coloracion-pies').val(response.data.foot_color_type);
				refreshSelectOption('select-coloracion-pies');
				$('#field-exploracion-podologica-observaciones').val(response.data.observations);
				$('#field-exploracion-podologica-recomendaciones').val(response.data.advice);
				loadingPodiatricExploration = false;
			} else {
				ShowToastMessage(response.message, 'error');
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) { 
			try {
				let response = JSON.parse(XMLHttpRequest.responseText);
				ShowToastMessage(response.message, 'error')
			} catch(e) {
				ShowToastMessage(XMLHttpRequest.responseText, 'error');
			}
		}  
	});
}

function GetProcedures() {
	$.ajax({
        url: `${homeURL}/api/procedures`,
		type: 'get',
		dataType: "json",
		success: function(response) {
			// console.log(response);
			$.each(response.data.procedures, function(k, v) {
				$('#select-search-procedure').append($('<option>', {
					value: v.id,
					text: v.procedure
				}));
			});
        	refreshSelectOption('select-search-procedure');
			$('#select-search-procedure').trigger('change');
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) { 
			try {
				let response = JSON.parse(XMLHttpRequest.responseText);
				ShowToastMessage(response.message, 'error')
			} catch(e) {
				ShowToastMessage(XMLHttpRequest.responseText, 'error');
			}
		}  
	});
}

function GetDiagnostics() {
	$('#select-diagnostico').empty();
	$.ajax({
        url: `${homeURL}/api/catalog/podiatry/diagnostics`,
		type: 'get',
		dataType: "json",
		success: function(response) {
			// console.log(response);
			if(response.success) {
				$.each(response.data.diagnostics, function(k, v) {
					$('#select-diagnostico').append($('<option>', {
						value: v.id,
						text: v.diagnostic,
						'data-code': v.code
					}));
				});
        		refreshSelectOption('select-diagnostico');
				$('#select-diagnostico').trigger('change');
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) { 
			try {
				let response = JSON.parse(XMLHttpRequest.responseText);
				ShowToastMessage(response.message, 'error')
			} catch(e) {
				ShowToastMessage(XMLHttpRequest.responseText, 'error');
			}
		}  
	});
}

function GetDiagnosticTypes() {
	$('#select-diagnostico-tipo').empty();
	$.ajax({
        url: `${homeURL}/api/catalog/podiatry/diagnostic-types`,
		type: 'get',
		dataType: "json",
		success: function(response) {
			// console.log(response);
			if(response.success) {
				$.each(response.data.diagnostic_types, function(k, v) {
					$('#select-diagnostico-tipo').append($('<option>', {
						value: v.id,
						text: v.type,
						'data-code': v.code
					}));
				});
        		refreshSelectOption('select-diagnostico-tipo');
				$('#select-diagnostico-tipo').trigger('change');
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) { 
			try {
				let response = JSON.parse(XMLHttpRequest.responseText);
				ShowToastMessage(response.message, 'error')
			} catch(e) {
				ShowToastMessage(XMLHttpRequest.responseText, 'error');
			}
		}  
	});
}

function GetPodiatryFootTypes() {
	$.ajax({
        url: `${homeURL}/api/catalog/podiatry/foot-types`,
		type: 'get',
		dataType: "json",
		success: function(response) {
			// console.log(response);
			if(response.success) {
				$.each(response.data.foot_types, function(k, v) {
					$('#select-tipo-pie').append($('<option>', {
						value: v.id,
						text: v.tipo
					}));
				});
        		refreshSelectOption('select-tipo-pie');
				$('#select-tipo-pie').trigger('change');
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) { 
			try {
				let response = JSON.parse(XMLHttpRequest.responseText);
				ShowToastMessage(response.message, 'error')
			} catch(e) {
				ShowToastMessage(XMLHttpRequest.responseText, 'error');
			}
		}  
	});
}

function GetPodiatryPulseTypes() {
	$.ajax({
        url: `${homeURL}/api/catalog/podiatry/pulse-types`,
		type: 'get',
		dataType: "json",
		success: function(response) {
			// console.log(response);
			if(response.success) {
				$.each(response.data.pulse_types, function(k, v) {
					$('#select-pulso-pedio-izquierdo').append($('<option>', {
						value: v.id,
						text: v.tipo
					}));
					$('#select-pulso-pedio-derecho').append($('<option>', {
						value: v.id,
						text: v.tipo
					}));
				});
        		refreshSelectOption('select-pulso-pedio-izquierdo');
        		refreshSelectOption('select-pulso-pedio-derecho');
				$('#select-pulso-pedio-izquierdo').trigger('change');
				$('#select-pulso-pedio-derecho').trigger('change');
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) { 
			try {
				let response = JSON.parse(XMLHttpRequest.responseText);
				ShowToastMessage(response.message, 'error')
			} catch(e) {
				ShowToastMessage(XMLHttpRequest.responseText, 'error');
			}
		}  
	});
}

function GetPodiatrySensitivityTypes() {
	$.ajax({
        url: `${homeURL}/api/catalog/podiatry/sensitivity-types`,
		type: 'get',
		dataType: "json",
		success: function(response) {
			// console.log(response);
			if(response.success) {
				$.each(response.data.sensitivity_types, function(k, v) {
					$('#select-sensibilidad-izquierdo').append($('<option>', {
						value: v.id,
						text: v.tipo
					}));
					$('#select-sensibilidad-derecho').append($('<option>', {
						value: v.id,
						text: v.tipo
					}));
				});
        		refreshSelectOption('select-sensibilidad-izquierdo');
        		refreshSelectOption('select-sensibilidad-derecho');
				$('#select-sensibilidad-izquierdo').trigger('change');
				$('#select-sensibilidad-derecho').trigger('change');
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) { 
			try {
				let response = JSON.parse(XMLHttpRequest.responseText);
				ShowToastMessage(response.message, 'error')
			} catch(e) {
				ShowToastMessage(XMLHttpRequest.responseText, 'error');
			}
		}  
	});
}

function GetPodiatryFootTemperatureTypes() {
	$.ajax({
        url: `${homeURL}/api/catalog/podiatry/temperature-types`,
		type: 'get',
		dataType: "json",
		success: function(response) {
			// console.log(response);
			if(response.success) {
				$.each(response.data.temperature_types, function(k, v) {
					$('#select-temperatura-pies').append($('<option>', {
						value: v.id,
						text: v.tipo
					}));
				});
        		refreshSelectOption('select-temperatura-pies');
				$('#select-temperatura-pies').trigger('change');
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) { 
			try {
				let response = JSON.parse(XMLHttpRequest.responseText);
				ShowToastMessage(response.message, 'error')
			} catch(e) {
				ShowToastMessage(XMLHttpRequest.responseText, 'error');
			}
		}  
	});
}

function GetPodiatryFootColorTypes() {
	$.ajax({
        url: `${homeURL}/api/catalog/podiatry/foot-color-types`,
		type: 'get',
		dataType: "json",
		success: function(response) {
			// console.log(response);
			if(response.success) {
				$.each(response.data.foot_color_types, function(k, v) {
					$('#select-coloracion-pies').append($('<option>', {
						value: v.id,
						text: v.tipo
					}));
				});
        		refreshSelectOption('select-coloracion-pies');
				$('#select-coloracion-pies').trigger('change');
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) { 
			try {
				let response = JSON.parse(XMLHttpRequest.responseText);
				ShowToastMessage(response.message, 'error')
			} catch(e) {
				ShowToastMessage(XMLHttpRequest.responseText, 'error');
			}
		}  
	});
}

function GetPodiatryMetatarsalFormulas() {
	$.ajax({
        url: `${homeURL}/api/catalog/podiatry/metatarsal-formulas`,
		type: 'get',
		dataType: "json",
		success: function(response) {
			// console.log(response);
			if(response.success) {
				$.each(response.data.metatarsal_formulas, function(k, v) {
					$('#select-formula-metatarsal').append($('<option>', {
						value: v.id,
						text: v.formula
					}));
				});
        		refreshSelectOption('select-formula-metatarsal');
				$('#select-formula-metatarsal').trigger('change');
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) { 
			try {
				let response = JSON.parse(XMLHttpRequest.responseText);
				ShowToastMessage(response.message, 'error')
			} catch(e) {
				ShowToastMessage(XMLHttpRequest.responseText, 'error');
			}
		}  
	});
}

function countActiveDiagnostics() {
	let active = 0;
	consultationsDiagnostics.forEach((v, i) => {
		if(v.action != 'remove')
			active += 1;
	});
	return active;
}

function countActiveProcedures() {
	let active = 0;
	consultationsProcedures.forEach((v, i) => {
		if(v.action != 'remove')
			active += 1;
	});
	return active;
}

function saveConsultationProcedures(auto = false) {
	if(!savingConsultationProcedures && !savingConsultationFinish) {
		let activeProcedures = countActiveProcedures();
		if(activeProcedures > 0) {
			savingConsultationProcedures = true;
			$.ajax({
				url: `${homeURL}/api/consultations/${consultation_id}/procedures`,
				type: 'put',
				data: {
					procedures: JSON.stringify(consultationsProcedures)
				},
				dataType: "json",
				success: function(response) {
					savingConsultationProcedures = false;
					// console.log(response);
					if(response.success) {
						if(!auto)
							ShowToastMessage('Cambios almacenados con exito.', 'success');
						GetConsultationProcedures();
					}
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					savingConsultationProcedures = false;
					try {
						let response = JSON.parse(XMLHttpRequest.responseText);
						ShowToastMessage(response.message, 'error')
					} catch(e) {
						ShowToastMessage(XMLHttpRequest.responseText, 'error');
					}
				}  
			});
		} else {
			ShowToastMessage('Es necesario tener por lo menos un procedimiento en la lista.', 'error');
		}
	} else {
		ShowToastMessage('Hay un proceso de actualización sin finalizar.', 'error');
	}
}

function saveConsultationDiagnostics(auto = false) {
	if(!savingConsultationDiagnostics && !savingConsultationFinish) {
		savingConsultationDiagnostics = true;
		$.ajax({
			url: `${homeURL}/api/consultations/${consultation_id}/diagnostics`,
			type: 'put',
			data: {
				diagnostics: JSON.stringify(consultationsDiagnostics),
				observations: $('#field-diagnostico-observaciones').val()
			},
			dataType: "json",
			success: function(response) {
				savingConsultationDiagnostics = false;
				// console.log(response);
				if(response.success) {
					if(!auto)
						ShowToastMessage('Cambios almacenados con exito.', 'success');
					GetConsultationDiagnostics();
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				savingConsultationDiagnostics = false;
				try {
					let response = JSON.parse(XMLHttpRequest.responseText);
					ShowToastMessage(response.message, 'error')
				} catch(e) {
					ShowToastMessage(XMLHttpRequest.responseText, 'error');
				}
			}  
		});
	} else {
		ShowToastMessage('Hay un proceso de actualización sin finalizar.', 'error');
	}
}

function saveConsultationSores(auto = false) {
	if(!savingConsultationSores && !savingConsultationFinish) {
		savingConsultationSores = true;
		$.ajax({
			url: `${homeURL}/api/consultations/${consultation_id}/sores`,
			type: 'put',
			data: {
				sores: JSON.stringify(consultationsSores)
			},
			dataType: "json",
			success: function(response) {
				savingConsultationSores = false;
				// console.log(response);
				if(response.success) {
					if(!auto)
						ShowToastMessage('Cambios almacenados con exito.', 'success');
					GetConsultationSores();
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				savingConsultationSores = false;
				try {
					let response = JSON.parse(XMLHttpRequest.responseText);
					ShowToastMessage(response.message, 'error')
				} catch(e) {
					ShowToastMessage(XMLHttpRequest.responseText, 'error');
				}
			}  
		});
	} else {
		ShowToastMessage('Hay un proceso de actualización sin finalizar.', 'error');
	}
}

function saveConsultationObservations(auto = false) {
	if(!savingConsultationObservations && !savingConsultationFinish) {
		savingConsultationObservations = true;
		$.ajax({
			url: `${homeURL}/api/consultations/${consultation_id}/observations`,
			type: 'put',
			data: {
				observations: $('#field-observaciones-iniciales').val()
			},
			dataType: "json",
			success: function(response) {
				savingConsultationObservations = false;
				console.log(response);
				if(response.success) {
					if(!auto)
						ShowToastMessage('Cambios almacenados con exito.', 'success');
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				savingConsultationObservations = false;
				try {
					let response = JSON.parse(XMLHttpRequest.responseText);
					ShowToastMessage(response.message, 'error')
				} catch(e) {
					ShowToastMessage(XMLHttpRequest.responseText, 'error');
				}
			}  
		});
	} else {
		ShowToastMessage('Hay un proceso de actualización sin finalizar.', 'error');
	}
}

function saveConsultationPodriaticExploration(auto = false) {
	if(!savingConsultationPodiatricExploration && !savingConsultationFinish) {
		savingConsultationPodiatricExploration = true;
		clearTimeout(podiatricExplorationTimer);
		$.ajax({
			url: `${homeURL}/api/consultations/${consultation_id}/podiatric-exploration`,
			type: 'put',
			data: {
				foot_type: $('#select-tipo-pie').val(),
				metatarsal_formula: $('#select-formula-metatarsal').val(),
				gait_disorder: $('#field-alteraciones-marcha').val(),
				left_pulse_type: $('#select-pulso-pedio-izquierdo').val(),
				right_pulse_type: $('#select-pulso-pedio-derecho').val(),
				left_sensitivity_type: $('#select-sensibilidad-izquierdo').val(),
				right_sensitivity_type: $('#select-sensibilidad-derecho').val(),
				temperature_type: $('#select-temperatura-pies').val(),
				foot_color_type: $('#select-coloracion-pies').val(),
				observations: $('#field-exploracion-podologica-observaciones').val(),
				advice: $('#field-exploracion-podologica-recomendaciones').val()
			},
			dataType: "json",
			success: function(response) {
				savingConsultationPodiatricExploration = false;
				console.log(response);
				if(response.success) {
					if(!auto)
						ShowToastMessage('Cambios almacenados con exito.', 'success');
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				savingConsultationPodiatricExploration = false;
				try {
					let response = JSON.parse(XMLHttpRequest.responseText);
					ShowToastMessage(response.message, 'error')
				} catch(e) {
					ShowToastMessage(XMLHttpRequest.responseText, 'error');
				}
			}  
		});
	} else {
		ShowToastMessage('Hay un proceso de actualización sin finalizar.', 'error');
	}
}

function saveConsultationIndications(auto = false) {
	if(!savingConsultationIndications && !savingConsultationFinish) {
		savingConsultationIndications = true;
		$.ajax({
			url: `${homeURL}/api/consultations/${consultation_id}/indications`,
			type: 'put',
			data: {
				indications: $('#field-indicaciones').val()
			},
			dataType: "json",
			success: function(response) {
				savingConsultationIndications = false;
				console.log(response);
				if(response.success) {
					if(!auto)
						ShowToastMessage('Cambios almacenados con exito.', 'success');
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				savingConsultationIndications = false;
				try {
					let response = JSON.parse(XMLHttpRequest.responseText);
					ShowToastMessage(response.message, 'error')
				} catch(e) {
					ShowToastMessage(XMLHttpRequest.responseText, 'error');
				}
			}  
		});
	} else {
		ShowToastMessage('Hay un proceso de actualización sin finalizar.', 'error');
	}
}

function endConsultation() {
	if(selectedTab == 'exploracion_podologica') {
		saveConsultationPodriaticExploration();
	}
	ShowSweetAlertConfirmCancelCallback('info',
										'Finalizar Consulta',
										'Confirma si deseas proceder a finalizar la consulta.',
										'Finalizar',
										'Cancelar',
										(result) => {
											if(result.isConfirmed)
												saveEndConsultation();
										})
}

function saveEndConsultation() {
	if(podiatricExplorationTimer != null ||
		savingConsultationObservations ||
		savingConsultationPodiatricExploration ||
		savingConsultationProcedures ||
		savingConsultationDiagnostics ||
		savingConsultationSores ||
		savingConsultationIndications
	) {
		ShowToastMessage('Hay un proceso de actualización pendiente.', 'error');
		return;
	}
	if(!savingConsultationFinish) {
		savingConsultationFinish = true;
		$.ajax({
			url: `${homeURL}/api/consultations/${consultation_id}/finish`,
			type: 'post',
			dataType: "json",
			success: function(response) {
				savingConsultationFinish = false;
				console.log(response);
				if(response.success) {
					ShowToastMessage('Cita finalizada con exito.', 'success');
					ShowSweetAlertConfirmCallback('success',
													'Cita Finalizada',
													'La cita ha sido finalizada exitosamente',
													'Entendido',
													(result) => {
														window.open(`${homeURL}/consultations/`, '_self');
													});
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) {
				savingConsultationFinish = false;
				try {
					let response = JSON.parse(XMLHttpRequest.responseText);
					ShowToastMessage(response.message, 'error')
				} catch(e) {
					ShowToastMessage(XMLHttpRequest.responseText, 'error');
				}
			}  
		});
	} else {
		ShowToastMessage('Ya hay un proceso de cierre de consulta activo.', 'warning');
	}
}