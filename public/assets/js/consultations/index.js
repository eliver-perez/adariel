// JavaScript Document

var homeURL;
var new_procedure = modify_procedure = registering_procedure = modifying_procedure = false;
var selected_consultation = null;

var loading_status = true;

function InitializeValues(home) {
	homeURL = home;
	GetAppointmentsStatus();
	$('#select-filtro-consulta-estatus').on('change', function() { if(!loading_status) GetConsultations(); });
	$('#btn-consultation-start').on('click', () => { StartConsultation() });
}

function GetAppointmentsStatus() {
	$('#select-filtro-consulta-estatus').empty();
	$.ajax({
        url: `${homeURL}/api/appointments/status`,
		type: 'get',
		dataType: "json",
		success: function(response) {
			console.log(response);
			$.each(response.data.appointments_status, function(k, v) {
				$('#select-filtro-consulta-estatus').append($('<option>', {
					value: v.codigo,
					text: v.estatus
				}));
			});
			$('#select-filtro-consulta-estatus').val('en_espera');
			loading_status = false;
			$('#select-filtro-consulta-estatus').trigger('change');
        	refreshSelectOption('select-filtro-consulta-estatus');
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) { 
			try {
				var response = JSON.parse(XMLHttpRequest.responseText);
				ShowToastMessage(response.message, 'error');
				
			} catch (e) {
				ShowToastMessage(XMLHttpRequest.responseText, 'error');
			}
		}  
	});
}

function GetConsultations() {
	$.ajax({
        url: `${homeURL}/api/consultations`,
		type: 'get',
		data: {
			appointment_status: $('#select-filtro-consulta-estatus').val(),
			search: ''
		},
		dataType: "json",
		success: function(response) {
			console.log(response);
			if(response.success) {
				var status_badge_bg;
				$.each(response.data.consultations, function(k, v) {
					if(v.status_id == 'finalizada')
						status_badge_bg = 'bg-success';
					else if(v.status_id == 'en_espera' || v.status_id == 'en_proceso')
						status_badge_bg = 'bg-primary';
					else if(v.status_id == 'agendada')
						status_badge_bg = 'bg-secondary';
					else
						status_badge_bg = 'bg-danger';
					$('#table-procedures').append(`<tr class="transition duration-300 ease-in-out border-b hover:bg-neutral-100 dark:border-neutral-500 dark:hover:bg-neutral-600 cursor-pointer">
														<td onclick="SelectConsultation('${v.id}')" class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
															<span class="font-medium capitalize text-dark dark:text-title-dark text-15">${v.patient}</span>
														</td>
														<td onclick="SelectConsultation('${v.id}')" class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
															${v.age}
														</td>
														<td onclick="SelectConsultation('${v.id}')" class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
															${v.appointment_type}
														</td>
														<td onclick="SelectConsultation('${v.id}')" class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
															${v.date} ${v.time_start}
														</td>
														<td onclick="SelectConsultation('${v.id}')" class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
															${v.duration} min.
														</td>
														<td onclick="SelectConsultation('${v.id}')" class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
															<div class="inline-flex items-center gap-[10px] text-light dark:text-subtitle-dark text-[10px] capitalize">
																<span class="${status_badge_bg} block w-[6px] h-[6px] rounded-full"></span>
																<span>${v.status}</span>
															</div>
														</td>
														<td class="ps-4 pe-0 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent rounded-e-[4px]">
															<div class="text-primary dark:text-subtitle-dark text-[19px] flex justify-start p-0 m-0 gap-[5px]">
																<button type="button" class="uil uil-file-medical-alt hover:text-secondary cursor-pointer" title="Iniciar Consulta" onclick="StartConsultation('${v.id}');"></button>
																<button type="button" class="uil uil-cancel hover:text-danger cursor-pointer" title="Cancelar Consulta"></button>
															</div>
														</td>
													</tr>`);
				});
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) { 
			try {
				var response = JSON.parse(XMLHttpRequest.responseText);
				ShowToastMessage(response.message, 'error');
				
			} catch (e) {
				ShowToastMessage(XMLHttpRequest.responseText, 'error');
			}
		}  
	});
}

function ClearConsultationData() {
	$('#field-consultation-type').html('...');
	$('#field-consultation-status').removeClass('text-primary bg-primary/20 text-secondary bg-secondary/20 text-danger bg-danger/20 text-success bg-success/20');
	$('#field-consultation-status').addClass('text-warning bg-warning/20');
	$('#field-consultation-status').html('...');
	$('#field-consultation-folio').html('...');
	$('#field-consultation-patient').html('...');
	$('#field-consultation-age').html('...');
	$('#field-consultation-date').html('...');
	$('#field-consultation-duration').html('...');
	$('#field-consultation-chief-complaint').html('...');
	$('#btn-consultation-start').attr('disabled', true);
}

function SelectConsultation(id) {
	if(selected_consultation != id) {
		selected_consultation = id;
		ClearConsultationData();
		$.ajax({
			url: `${homeURL}/api/consultations/${id}`,
			type: 'get',
			dataType: "json",
			success: function(response) {
				console.log(response);
				if(response.success) {
					$('#field-consultation-type').html(response.data.appointment_type);

					$('#field-consultation-status').removeClass('text-warning bg-warning/20');
					if(response.data.status_id == 'finalizada')
						$('#field-consultation-status').addClass('text-success bg-success/20');
					else if(response.data.status_id == 'no_presento' || response.data.status_id == 'cancelada' || response.data.status_id == 'rechazada')
						$('#field-consultation-status').addClass('text-danger bg-danger/20');
					else if(response.data.status_id == 'en_proceso')
						$('#field-consultation-status').addClass('text-primary bg-primary/20');
					else if(response.data.status_id == 'en_espera')
						$('#field-consultation-status').addClass('text-secondary bg-secondary/20');
					else
						$('#field-consultation-status').addClass('text-warning bg-warning/20');
					$('#field-consultation-status').html(response.data.status);

					$('#field-consultation-folio').html(response.data.folio);
					$('#field-consultation-patient').html(response.data.patient);
					$('#field-consultation-age').html(response.data.age);
					$('#field-consultation-date').html(`${response.data.date} ${response.data.time_start}`);
					$('#field-consultation-duration').html(`${response.data.duration} min.`);
					$('#field-consultation-chief-complaint').html(response.data.chief_complaint);

					if(response.data.status_id == 'en_espera' || response.data.status_id == 'agendada' || response.data.status_id == 'en_proceso')
						$('#btn-consultation-start').attr('disabled', false);
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) { 
				try {
					var response = JSON.parse(XMLHttpRequest.responseText);
					ShowToastMessage(response.message, 'error');
					
				} catch (e) {
					ShowToastMessage(XMLHttpRequest.responseText, 'error');
				}
			}  
		});
	}
}

function StartConsultation(id = null) {
	var consultation_id = id != null ? id : selected_consultation;
	if(consultation_id != null) {
		window.open(`${homeURL}/consultations/${consultation_id}`, '_self');
	}
}