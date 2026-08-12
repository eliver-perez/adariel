// JavaScript Document

var homeURL;
var searchTimer = null, searchValue = '';
var selected_patient = '';

var procedures = [];
var totalTime = 0, totalCost = 0;
var showSearch = false, showProcedures = false;

var appointmentDatePicker = null;

var available_slots = [];
var selected_slot = null;

function InitializeValues(home) {
	homeURL = home;
	$('.sector-seleccion-paciente').hide();
	$('.sector-agregar-servicio').hide();
	$('#field-paciente-parent').on('click', ShowSearch);
	$('#btn-mostrar-busqueda').on('click', ShowSearch);
	$('#btn-nuevo-servicio').on('click', ShowProcedures);
	$('#btn-nuevo-paciente').on('click', AddPatient);
	$('#btn-agregar-servicio').on('click', AgregarServicio);
	ClearSelectedAppointmentDate();
    // appointmentDatePicker = initDatePicker('field-fecha-cita');
	initDatePicker('field-fecha-cita', function(date, formattedDate) {
		GetAvailableSlots(formattedDate);
	});
	$('.available-appointments').hide();
	$('.not-available-appointments').show();
	$('#field-busqueda-paciente').on('keyup', function(e) {
		if($('#field-busqueda-paciente').val() != searchValue) {
			searchValue = $('#field-busqueda-paciente').val();
			clearTimeout(searchTimer);
			searchTimer = setTimeout(function () {
				SearchPatients();
			}, 500);
		}
	});
	$('#select-servicio').on('change', GetProceduresStaff);
	$('#btn-mostrar-horarios').on('click', ShowAvailableAppointments);
	GetBookingChannels();
	GetAppointmentsType();
	GetProcedures();
	SearchPatients();
}

class AvailableSlots {
	constructor(id, date, start, end, duration) {
		this.id = id;
		this.date = date;
		this.start = start;
		this.end = end;
		this.duration = duration;
		this.procedures = [];
	}
}

class ProceduresSlot {
	constructor(staff_id, staff, procedure_id, procedure, start, end, cost) {
		this.staff_id = staff_id;
		this.staff = staff;
		this.procedure_id = procedure_id;
		this.procedure = procedure;
		this.start = start;
		this.end = end;
		this.cost = cost;
	}
}

class Procedures {
  constructor(id, procedureId, procedure, staffId, staff, cost, duration) {
    this.id = id;
    this.procedureId = procedureId;
	this.procedure = procedure;
    this.staffId = staffId;
    this.staff = staff;
    this.cost = cost;
    this.duration = duration;
  }
}

function ShowAvailableAppointments() {
	if($('.sector-horarios-disponibles').hasClass('hidden')) {
		$('#btn-mostrar-horarios').addClass('text-white bg-dark hover:bg-dark-hbr');
		$('.sector-horarios-disponibles').removeClass('hidden');
		$('#btn-mostrar-horarios').html('<svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 256 256" height="1em" width="1.2em" xmlns="http://www.w3.org/2000/svg"><path d="M184,216a8,8,0,0,1-8,8H80a8,8,0,0,1,0-16h96A8,8,0,0,1,184,216Zm45.66-101.66-96-96a8,8,0,0,0-11.32,0l-96,96A8,8,0,0,0,32,128H72v56a8,8,0,0,0,8,8h96a8,8,0,0,0,8-8V128h40a8,8,0,0,0,5.66-13.66Z"></path></svg> Ocultar Horarios Disponibles')
	} else {
		$('#btn-mostrar-horarios').removeClass('text-white bg-dark hover:bg-dark-hbr');
		$('.sector-horarios-disponibles').addClass('hidden');
		$('#btn-mostrar-horarios').html('<svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 256 256" class="me-1.5 h-5 w-5" height="1em" width="1em" xmlns="http://www.w3.org/2000/svg"><path d="M72,40a8,8,0,0,1,8-8h96a8,8,0,0,1,0,16H80A8,8,0,0,1,72,40Zm159.39,92.94A8,8,0,0,0,224,128H184V72a8,8,0,0,0-8-8H80a8,8,0,0,0-8,8v56H32a8,8,0,0,0-5.66,13.66l96,96a8,8,0,0,0,11.32,0l96-96A8,8,0,0,0,231.39,132.94Z"></path></svg> Mostrar Horarios Disponibles')
	}
}

function ShowSearch() {
	if(!showSearch) {
		showSearch = true;
		$('#btn-mostrar-busqueda').addClass('text-white bg-dark hover:bg-dark-hbr');
		$('#btn-mostrar-busqueda').html('<svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 256 256" height="1em" width="1.2em" xmlns="http://www.w3.org/2000/svg"><path d="M184,216a8,8,0,0,1-8,8H80a8,8,0,0,1,0-16h96A8,8,0,0,1,184,216Zm45.66-101.66-96-96a8,8,0,0,0-11.32,0l-96,96A8,8,0,0,0,32,128H72v56a8,8,0,0,0,8,8h96a8,8,0,0,0,8-8V128h40a8,8,0,0,0,5.66-13.66Z"></path></svg>');
		$('.sector-seleccion-paciente').slideDown();
	} else {
		showSearch = false;
		$('#btn-mostrar-busqueda').removeClass('text-white bg-dark hover:bg-dark-hbr');
		$('#btn-mostrar-busqueda').html('<i class="uil uil-search text-[18px]"></i>');
		$('.sector-seleccion-paciente').slideUp();
	}
}

function ShowProcedures() {
	if(!showProcedures) {
		showProcedures = true;
		$('#btn-nuevo-servicio').addClass('text-white bg-dark hover:bg-dark-hbr');
		$('#btn-nuevo-servicio').html('<svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 256 256" height="1em" width="1.2em" xmlns="http://www.w3.org/2000/svg"><path d="M184,216a8,8,0,0,1-8,8H80a8,8,0,0,1,0-16h96A8,8,0,0,1,184,216Zm45.66-101.66-96-96a8,8,0,0,0-11.32,0l-96,96A8,8,0,0,0,32,128H72v56a8,8,0,0,0,8,8h96a8,8,0,0,0,8-8V128h40a8,8,0,0,0,5.66-13.66Z"></path></svg> Ocultar');
		$('.sector-agregar-servicio').slideDown();
	} else {
		showProcedures = false;
		$('#btn-nuevo-servicio').removeClass('text-white bg-dark hover:bg-dark-hbr');
		$('#btn-nuevo-servicio').html('<i class="uil uil-plus text-[18px]"></i> Nuevo Servicio');
		$('.sector-agregar-servicio').slideUp();
	}
}

function AddPatient() {
	window.location.href = `${homeURL}/patients/add?callback=schedule`
}

function GetAppointmentsType() {
	$('#select-tipo-cita').empty();
	$.ajax({
        url: `${homeURL}/api/appointments-types`,
		type: 'get',
		data: {
			search: ''
		},
		processData: false,
		contentType: false,
		dataType: "json",
		success: function(response) {
			$.each(response.data.appointments_types, function(k, v) {
				$('#select-tipo-cita').append($('<option>', {
					value: v.id,
					text: v.asunto
				}));
			});
        	refreshSelectOption('select-tipo-cita');
        	$('#select-tipo-cita').trigger('change');
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) { 
			console.log('STATUS:', textStatus);
			console.log('ERROR:', errorThrown);
			console.log('RESPONSE TEXT:', XMLHttpRequest.responseText);

			alert(XMLHttpRequest.responseText);
		}  
	});
}

function GetBookingChannels() {
	$('#select-como-agendo').empty();
	$.ajax({
        url: `${homeURL}/api/booking-channels`,
		type: 'get',
		data: {
			search: ''
		},
		processData: false,
		contentType: false,
		dataType: "json",
		success: function(response) {
			var selectId = 1;
			$.each(response.data.booking_types, function(k, v) {
				$('#select-como-agendo').append($('<option>', {
					value: v.id,
					text: v.forma
				}));
				if(v.codigo == 'presencial')
					selectId = v.id;
			});
			$('#select-como-agendo').val(selectId);
        	refreshSelectOption('select-como-agendo');
        	$('#select-como-agendo').trigger('change');
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) { 
			console.log('STATUS:', textStatus);
			console.log('ERROR:', errorThrown);
			console.log('RESPONSE TEXT:', XMLHttpRequest.responseText);

			alert(XMLHttpRequest.responseText);
		}  
	});
}

function GetProcedures() {
	$('#select-servicio').empty();
	$.ajax({
        url: `${homeURL}/api/procedures`,
		type: 'get',
		dataType: "json",
		success: function(response) {
			console.log(response);
			$.each(response.data.procedures, function(k, v) {
				$('#select-servicio').append($('<option>', {
					value: v.id,
					text: v.procedure
				}));
			});
        	refreshSelectOption('select-servicio');
        	$('#select-servicio').trigger('change');
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) { 
			console.log('STATUS:', textStatus);
			console.log('ERROR:', errorThrown);
			console.log('RESPONSE TEXT:', XMLHttpRequest.responseText);

			alert(XMLHttpRequest.responseText);
		}  
	});
}

function GetProceduresStaff() {
	var procedure = $('#select-servicio').val();
	if(procedure != null) {
		$('#select-servicio-atiende').empty();
		$.ajax({
			url: `${homeURL}/api/procedures/${procedure}/staff`,
			type: 'get',
			data: {
				procedure: procedure
			},
			processData: false,
			contentType: false,
			dataType: "json",
			success: function(response) {
				console.log(response);
				$.each(response.data.staff, function(k, v) {
					$('#select-servicio-atiende').append($('<option>', {
						value: v.id,
						text: `${v.name} (${accounting.formatMoney(v.cost)})`
					}));
				});
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) { 
				console.log('STATUS:', textStatus);
				console.log('ERROR:', errorThrown);
				console.log('RESPONSE TEXT:', XMLHttpRequest.responseText);

				alert(XMLHttpRequest.responseText);
			}  
		});
	}
}

function SearchPatients() {
	$('#table-patients').find('tbody').empty();
	$.ajax({
        url: `${homeURL}/api/patients`,
		type: 'get',
		data: {
			search: $('#field-busqueda-paciente').val(),
			limit: 5,
			offset: 0
		},
		contentType: false,
		dataType: "json",
		success: function(response) {
			var rows = '';
			$.each(response.data.patients, function(k, v) {
				rows += `<tr class="transition duration-300 ease-in-out border-b hover:bg-neutral-100 dark:border-neutral-500 dark:hover:bg-neutral-600 cursor-pointer" onclick="javascript:SelectPatient('${v.id}', '${escapeHTML(v.code)}', '${escapeHTML(v.name)}');">
                            <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                <span class="font-medium capitalize text-dark dark:text-title-dark text-15">${v.code}</span>
                            </td>
                            <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
								${v.name}
							</td>
                            <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
								${v.dob}
							</td>
                            <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                ${v.gender}
							</td>
                            <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                ${v.phone}
							</td>
                            <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                ${v.mobile}
							</td>
                            <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                ${v.last_visit_date}
							</td>
                        </tr>`;
			});
			$('#table-patients').find('tbody').append(rows);
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) { 
			console.log('STATUS:', textStatus);
			console.log('ERROR:', errorThrown);
			console.log('RESPONSE TEXT:', XMLHttpRequest.responseText);

			alert(XMLHttpRequest.responseText);
		}  
	});
}

function SelectPatient(id, code, patient) {
	selected_patient = id;
	$('#field-selected-paciente').val(`${code} - ${patient}`);
	ShowSearch();
}

function AgregarServicio() {
	var procedure = $('#select-servicio').val();
	var staff = $('#select-servicio-atiende').val();
	if(procedure != null && staff != null) {
		var duplicate = false;
		$.each(procedures, function(k, v) {
			if(v.procedureId == procedure) {
				ShowToastMessage('El servicio seleccionado ya se encuentra en la lista', 'error');
				duplicate = true;
				return;
			}
		});
		if(duplicate)
			return;
		$.ajax({
			url: `${homeURL}/api/procedures/${procedure}/staff/${staff}`,
			type: 'get',
			data: {
				procedure: procedure,
				staff: staff
			},
			contentType: false,
			dataType: "json",
			success: function(response) {
				procedures.push(new Procedures(response.data.id,
												response.data.procedimiento_id,
												response.data.procedimiento,
												response.data.personal_id,
												response.data.nombre,
												response.data.costo,
												response.data.duracion
				));
				$('.procedimientos-agregados').append(`<li id="procedimiento-agregado-${response.data.id}" class="p-2 border border-primary rounded-10 last:mb-0 mb-[20px] flex items-center justify-between">
														<div class="flex items-center gap-[15px]">
															<div>
																<label class="text-theme-gray text-[15px] dark:text-subtitle-dark hover:text-primary font-medium leading-[19px]">
																${response.data.procedimiento} (${response.data.duracion} min.) </label>
																<div class="before:-translate-y-1/2 before:absolute before:bg-secondary before:content-[''] before:h-[6px] before:rounded-full before:top-[50%] before:transform before:w-[6px] dark:text-subtitle-dark leading-1 before:start-0 ps-[12px] mt-[-4px] relative text-[12px] text-theme-gray">
																${response.data.nombre} - ${accounting.formatMoney(response.data.costo)} </div>
																<span class="before:bg-success hidden"></span>
															</div>
														</div>
														<button onclick="javascript:RemoveService(${response.data.id})" type="button" class=" toggle-active text-danger bg-danger/10 border-danger/10 text-[13px] font-semibold px-[12px] hover:text-white hover:bg-danger transition-all duration-300 border-none rounded-3 [&.active]:bg-danger [&.active]:text-white h-[32px] rounded-4 group">
															Remover
														</button>
													</li>`);
				RefreshMetrics();
				if(showProcedures)
					ShowProcedures();
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) { 
				console.log('STATUS:', textStatus);
				console.log('ERROR:', errorThrown);
				console.log('RESPONSE TEXT:', XMLHttpRequest.responseText);

				alert(XMLHttpRequest.responseText);
			}  
		});
	}
}

function RemoveService(id) {
	var found = false;
	for(var i = 0; i < procedures.length; i++) {
		if(parseInt(procedures[i].id) == parseInt(id)) {
			found = true;
			procedures.splice(i, 1);
			$(`#procedimiento-agregado-${id}`).remove();
		}
	}
	RefreshMetrics();
}

function RefreshMetrics() {
	totalTime = 0;
	totalCost = 0;
	$.each(procedures, function(k, v) {
		totalTime += v.duration;
		totalCost += parseFloat(v.cost);
	});
	GetAvailableSlots();
	$('#field-procedimientos-tiempo').html(totalTime);
	$('#field-procedimientos-costo').html(accounting.formatMoney(totalCost));
}

function ClearAvailableSlots() {
	$('.sector-no-horarios').show();
	$('#btn-mostrar-horarios').hide();
	$('.sector-horarios-disponibles').html('');
	available_slots = [];
}

function GetAvailableSlots() {
	ClearAvailableSlots();
	ClearSelectedAppointmentDate();
	if($('#field-fecha-cita').val() != '' && procedures.length > 0) {
		$.ajax({
			url: `${homeURL}/api/appointments/available-slots`,
			type: 'post',
			data: {
				date: $('#field-fecha-cita').val(),
				procedures: JSON.stringify(procedures)
			},
			contentType: false,
			dataType: "json",
			success: function(response) {
				console.log(response);
				if(response.data.slots.length > 0) {
					$('.sector-no-horarios').hide();
					$('#btn-mostrar-horarios').show();
					if($('.sector-horarios-disponibles').hasClass('hidden'))
						$('#btn-mostrar-horarios').trigger('click');
				}
				var slots = '<div class="grid grid-cols-12 gap-[25px] items-start">';
				var index = 0;
				$.each(response.data.slots, function(k, v) {
					slots += `<div class="col-span-6">
                                            <button
                                                type="button"
												onclick="SelectAppointmentDate(${index})"
                                                class="h-[42px] w-full px-[18px] border hover:bg-dark hover:text-white hover:border-[#000] rounded-4 inline-flex items-center justify-center gap-2 whitespace-nowrap focus:border-black focus:ring-1 focus:ring-black transition-all duration-200"
                                            >
                                                <i class="uil uil-clock text-[18px]"></i>
                                                ${formatTimeTo12h(v.start)} - ${formatTimeTo12h(v.end)}
                                            </button>
                                        </div>`;
					var slot = new AvailableSlots(index++, response.data.date, v.start, v.end, v.duration);
					$.each(v.procedures, function(l, m) {
						slot.procedures.push(new ProceduresSlot(m.staff_id, m.staff_name, m.procedure_id, m.procedure, m.start, m.end, m.cost));
					});
					available_slots.push(slot);
				});
				slots += `</div>`;
				$('.sector-horarios-disponibles').html(slots);
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) { 
				console.log('STATUS:', textStatus);
				console.log('ERROR:', errorThrown);
				console.log('RESPONSE TEXT:', XMLHttpRequest.responseText);

				alert(XMLHttpRequest.responseText);
			}  
		});
	}
}

function ClearSelectedAppointmentDate() {
	if(!$('.sector-horario-seleccionado').hasClass('hidden'))
		$('.sector-horario-seleccionado').addClass('hidden');
	$('#field-horario-seleccionado').html('');
	$('#field-horario-seleccionado-detalles').html('');
	selected_slot = null;
}

function SelectAppointmentDate(index) {
	if(!$('.sector-horarios-disponibles').hasClass('hidden'))
		$('#btn-mostrar-horarios').trigger('click');
	if($('.sector-horario-seleccionado').hasClass('hidden'))
		$('.sector-horario-seleccionado').removeClass('hidden');
	selected_slot = available_slots[index];
	console.log(selected_slot);
	$('#field-horario-seleccionado').html(`<i class="uil uil-clock text-[18px]"></i> ${formatTimeTo12h(selected_slot.start)} - ${formatTimeTo12h(selected_slot.end)}<br />
                                                ${selected_slot.duration} ${selected_slot.duration > 1 ? 'minutos' : 'minuto'}`);
	var details = '';
	$.each(selected_slot.procedures, function(k, v) {
		details += `<div class="flex items-center gap-[15px]">
						<div class="bg-white border shadow flex flex-col font-medium h-[60px] items-center justify-center min-w-[60px] px-[10px] rounded-6 text-15 text-dark">
							<span class="text-danger">MAR</span><span>19</span>
						</div>
						<article>
							<h4 class="event-title text-15 font-semibold mb-[2px] text-dark dark:text-title-dark duration-300 ease-in-out transition">
								${v.procedure} (${v.staff})</h4>
							<p class="text-14 text-light dark:text-subtitle-dark">
								<i class="uil uil-clock text-[18px]"></i> ${formatTimeTo12h(v.start)} - ${formatTimeTo12h(v.end)}</p>
						</article>
					</div>`;
	});
	$('#field-horario-seleccionado-detalles').html(details);
}

function RegisterAppointment() {
	if(selected_patient == '') {
		ShowToastMessage('Selecciona un paciente para continuar', 'error');
		return;
	}
	if(selected_slot == null) {
		ShowToastMessage('Selecciona un horario para continuar', 'error');
		return;
	}
    var formData = new FormData();
	formData.append('booking_mode', 'slots');
	formData.append('appointment_type', $('#select-tipo-cita').val());
	formData.append('booking_channel', $('#select-como-agendo').val());
	formData.append('patient', selected_patient);
	formData.append('appointment', JSON.stringify(selected_slot));
	formData.append('chief_complaint', $('#field-motivo-consulta').val());
	$.ajax({
		url: `${homeURL}/api/appointments`,
		type: 'post',
		data: formData,
		processData: false,
		contentType: false,
		dataType: "json",
		success: function(response) {
			if(response.success) {
				window.location.href = `${homeURL}/appointments?action=schedule-success?appointment=${response.data.id}`
			} else {
				ShowToastMessage(response.message, 'error');
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