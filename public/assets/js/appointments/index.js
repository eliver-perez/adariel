// JavaScript Document

var homeURL;

var calendar = null;
var searchTimer = null, searchValue = '';
var selected_appointment = null;
var selected_patient = '';

var showSearch = false, showNewPatient = false;
var registeringPatient = false;

let modalElement = null;
let calendarInstance = null;
let appointmentData = null;
let modalScheduleAppointment = null;

let dobDatePicker = null;
let scheduleAppointmentDatePicker = null;

function InitializeValues(home) {
	homeURL = home;
	if(action != '') {
		callBackActions();
	}
	modalScheduleAppointment = document.getElementById('modal-schedule-appointment');
	$('#btn-new-appointment').on('click', ShowScheduleAppointmentModal);
	$('.sector-schedule-select-patient').hide();
	$('.sector-schedule-new-patient').hide();
	$('#btn-schedule-new-patient').on('click', ShowNewPatient);
	$('#btn-schedule-cancel-patient').on('click', CancelNewPatient);
	// $('#field-schedule-patient-parent').on('click', ShowSearchOrNew);
	$('#btn-schedule-show-search-patient').on('click', ShowSearch);
	$('#btn-appointment-check-in').on('click', CheckInAppointment);
	$('#btn-appointment-cancel').on('click', CancelAppointment);
	$('#btn-schedule-appointment').on('click', ScheduleAppointment);
    dobDatePicker = initDatePicker('field-schedule-register-patient-dob');
    scheduleAppointmentDatePicker = initDatePicker('field-schedule-register-date', function(date, formattedDate) {

	}, true);
	document.addEventListener('click', function (e) {
		if (e.target.closest('.e-info-close')) {
			closeEventInfoModal();
		}

		if (e.target.id === 'e-info-modal') {
			closeEventInfoModal();
		}
	});
	$('#field-busqueda-paciente').on('keyup', function(e) {
		if($('#field-busqueda-paciente').val() != searchValue) {
			searchValue = $('#field-busqueda-paciente').val();
			clearTimeout(searchTimer);
			searchTimer = setTimeout(function () {
				SearchPatients();
			}, 500);
		}
	});
	initDatePicker('mini-calendario');
	GetAppointmentsStatus();
	GetAppointmentsType();
	GetBookingChannels();
	GetGenders();
	GetProcedures();
	GetStaff();
	SearchPatients();
	SetCalendar();
}

function ShowSearchOrNew() {
	if(showNewPatient)
		ShowNewPatient();
	else
		ShowSearch();
}

function CancelNewPatient() {
	ClearRegisterPatient();
	ShowNewPatient();
}

function ClearScheduleAppointment() {
	selected_patient = '';
	$('#field-schedule-patient').val('');
	$('#field-schedule-register-chief-complaint').val('');
	if(showNewPatient)
		ShowNewPatient();
	else if(showSearch)
		ShowSearch();

	const now = new Date();

	const interval = 5;
    const minutes = Math.ceil(now.getMinutes() / interval) * interval;

    now.setMinutes(minutes, 0, 0);

    // Hora local HH:mm
    const hour = String(now.getHours()).padStart(2, '0');
    const minute = String(now.getMinutes()).padStart(2, '0');

	dobDatePicker.setDate();
	dobDatePicker.setDate(new Date(now.getFullYear, now.getMonth, now.getDate()));
	document.getElementById('field-schedule-register-time').value =
        `${hour}:${minute}`;
}

function ShowScheduleAppointmentModal() {
	ClearScheduleAppointment();
	const modal = new te.Modal(modalScheduleAppointment);
	modal.show();
	$('#modal-schedule-appointment').trigger('click');
}

function GetAppointmentsType() {
	$('#select-schedule-type').empty();
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
				$('#select-schedule-type').append($('<option>', {
					value: v.id,
					text: v.asunto
				}));
			});
        	refreshSelectOption('select-schedule-type');
        	$('#select-schedule-type').trigger('change');
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

function GetBookingChannels() {
	$('#select-schedule-booking-type').empty();
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
				$('#select-schedule-booking-type').append($('<option>', {
					value: v.id,
					text: v.forma
				}));
				if(v.codigo == 'walk_in')
					selectId = v.id;
			});
			$('#select-schedule-booking-type').val(selectId);
        	refreshSelectOption('select-schedule-booking-type');
        	$('#select-schedule-booking-type').trigger('change');
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

function GetGenders() {
	try {
		$.ajax({
				url: `${homeURL}/api/genders`,
				type: 'get',
                dataType: "json",
				success: function(response) {
					$.each(response.data.genders, function(k, v) {
                        $('#select-schedule-gender').append($('<option>', {
                            value: v.id,
                            text: v.genero
                        }));
                    });
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
	} catch(E) {
		alert(E.message);
	}
}

function GetProcedures() {
	$('#select-schedule-procedure').empty();
	$.ajax({
        url: `${homeURL}/api/procedures`,
		type: 'get',
		dataType: "json",
		success: function(response) {
			$.each(response.data.procedures, function(k, v) {
				$('#select-schedule-procedure').append($('<option>', {
					value: v.id,
					text: v.procedure
				}));
			});
        	refreshSelectOption('select-schedule-procedure');
        	$('#select-schedule-procedure').trigger('change');
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

function GetStaff() {
	var procedure = $('#select-schedule-staff').val();
	$.ajax({
        url: `${homeURL}/api/staff`,
		type: 'get',
		dataType: "json",
		success: function(response) {
			$.each(response.data.staff, function(k, v) {
				$('#select-schedule-staff').append($('<option>', {
					value: v.id,
					text: v.name
				}));
			});
        	refreshSelectOption('select-schedule-staff');
        	$('#select-schedule-staff').trigger('change');
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

function SearchPatients() {
	$('#table-schedule-patients').find('tbody').empty();
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
			$('#table-schedule-patients').find('tbody').append(rows);
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

function SelectPatient(id, code, patient) {
	selected_patient = id;
	$('#field-schedule-patient').val(`${code} - ${patient}`);
	ShowSearch();
}

function ShowSearch() {
	if(!showSearch) {
		if(showNewPatient)
			ShowNewPatient();
		showSearch = true;
		$('#btn-schedule-show-search-patient').addClass('text-white bg-dark hover:bg-dark-hbr');
		$('#btn-schedule-show-search-patient').html('<svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 256 256" height="1em" width="1.2em" xmlns="http://www.w3.org/2000/svg"><path d="M184,216a8,8,0,0,1-8,8H80a8,8,0,0,1,0-16h96A8,8,0,0,1,184,216Zm45.66-101.66-96-96a8,8,0,0,0-11.32,0l-96,96A8,8,0,0,0,32,128H72v56a8,8,0,0,0,8,8h96a8,8,0,0,0,8-8V128h40a8,8,0,0,0,5.66-13.66Z"></path></svg>');
		$('.sector-schedule-select-patient').slideDown();
	} else {
		showSearch = false;
		$('#btn-schedule-show-search-patient').removeClass('text-white bg-dark hover:bg-dark-hbr');
		$('#btn-schedule-show-search-patient').html('<i class="uil uil-search text-[18px]"></i>');
		$('.sector-schedule-select-patient').slideUp();
	}
}

function ShowNewPatient() {
	if(!showNewPatient) {
		if(showSearch)
			ShowSearch();
		showNewPatient = true;
		$('#btn-schedule-new-patient').addClass('text-white bg-dark hover:bg-dark-hbr');
		$('#btn-schedule-new-patient').html('<svg stroke="currentColor" fill="currentColor" stroke-width="0" viewBox="0 0 256 256" height="1em" width="1.2em" xmlns="http://www.w3.org/2000/svg"><path d="M184,216a8,8,0,0,1-8,8H80a8,8,0,0,1,0-16h96A8,8,0,0,1,184,216Zm45.66-101.66-96-96a8,8,0,0,0-11.32,0l-96,96A8,8,0,0,0,32,128H72v56a8,8,0,0,0,8,8h96a8,8,0,0,0,8-8V128h40a8,8,0,0,0,5.66-13.66Z"></path></svg>');
		$('.sector-schedule-new-patient').slideDown();
	} else {
		showNewPatient = false;
		$('#btn-schedule-new-patient').removeClass('text-white bg-dark hover:bg-dark-hbr');
		$('#btn-schedule-new-patient').html('<i class="uil uil-plus text-[18px]"></i>');
		$('.sector-schedule-new-patient').slideUp();
	}
}

function callBackActions() {
	switch(action) {
		case 'schedule-success':
			ShowToastMessage('Cita agendada con exito.', 'success');
			break;
	}
}

function SetCalendar() {
    calendarEl = document.getElementById("calendario-agenda");
    if (calendarEl) {
      calendarInstance = new FullCalendar.Calendar(calendarEl, {
        headerToolbar: {
          left: "today,prev,title,next",
          right: "timeGridDay,timeGridWeek,dayGridMonth,listMonth",
        },
        views: {
          listMonth: {
            buttonText: "Agenda",
            titleFormat: { month: "short", weekday: "short" },
          }
        },
		eventMinHeight: 50,
		buttonText: {
			today: 'Hoy',
			month: 'Mes',
			week: 'Semana',
			day: 'Día',
			list: 'Agenda'
		},
		noEventsText: 'No hay citas para mostrar',
		titleFormat: function(date) {
			const formatter = new Intl.DateTimeFormat('es-MX', {
				month: 'long',
				year: 'numeric'
			});

			const text = formatter.format(date.date.marker);
			return text.charAt(0).toUpperCase() + text.slice(1);
		},
		eventTimeFormat: {
			hour: 'numeric',
			minute: '2-digit',
			meridiem: 'short',
			hour12: true
		},
        listDayFormat: true,
        allDaySlot: false,
        editable: false,
		lazyFetching: false,
        eventSources: [ ],
        contentHeight: 800,
        initialView: "timeGridWeek",
		slotMinTime: '08:00:00',
    	slotMaxTime: '20:00:00',
		locale: 'es',
        eventDidMount: function (view) {
          document.querySelectorAll(".fc-list-day").forEach(function (item) {});
        },
        eventClick: function (infoEvent) {
			const event = infoEvent.event;
			appointmentData = event;
			if($('#btn-appointment-start-consultation').length)
				$('#btn-appointment-start-consultation').remove();

			selected_appointment = event.id;
			CheckAppointmentAssignment(selected_appointment);

          	modalElement = document.getElementById('e-info-modal');

			modalElement.querySelector('.e-info-title').textContent =
				(event.extendedProps.patient + ' - ' + event.extendedProps.appointment_type) || '';

			modalElement.querySelector('.e-info-date').textContent =
				event.start
					? event.start.toLocaleDateString('es-MX', {
						year: 'numeric',
						month: 'long',
						day: 'numeric'
					})
					: '';

			if(event.extendedProps.status == 'agendada' && modalElement.querySelector('.sec-check-in').classList.contains('hidden'))
				modalElement.querySelector('.sec-check-in').classList.remove('hidden');
			else if(event.extendedProps.status != 'agendada')
				modalElement.querySelector('.sec-check-in').classList.add('hidden');

			modalElement.querySelector('.e-info-time').textContent =
				formatEventTime(event);

			modalElement.querySelector('.e-info-age').textContent =
				event.extendedProps.age;

			modalElement.querySelector('.e-info-dob').textContent =
				event.extendedProps.dob;

			modalElement.querySelector('.e-info-patient-code').textContent =
				event.extendedProps.patient_code;

			modalElement.querySelector('.e-info-email').textContent =
				event.extendedProps.email;

			modalElement.querySelector('.e-info-phone').textContent =
				event.extendedProps.phone;

			modalElement.querySelector('.e-info-description').textContent =
				event.extendedProps.description || 'Sin descripción';

			const modal = new te.Modal(modalElement);
			modal.show();
        },
		events: function(info, successCallback, failureCallback) {
			$.ajax({
				url: `${homeURL}/api/appointments/calendar`,
				method: 'GET',
				dataType: 'json',
				data: {
					start: info.startStr,
					end: info.endStr
				},
				success: function(response) {
					successCallback(response.data.appointments);
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) { 
					try {
						var response = JSON.parse(XMLHttpRequest.responseText);
						ShowToastMessage(response.message, 'error');
						
					} catch (e) {
						ShowToastMessage(XMLHttpRequest.responseText, 'error');
					}

					failureCallback();
				}
			});
		}
      });

      calendarInstance.render();
      const listMonthButton = document.querySelector(".fc-button-group .fc-listMonth-button");
      if (listMonthButton) {
        const icon = document.createElement("i");
        icon.className = "uil uil-list-ul";
        listMonthButton.insertBefore(icon, listMonthButton.firstChild);
      }
    }
	if(modalElement != null) {
		modalElement.addEventListener('hidden.te.modal', function () {
			selected_appointment = null;
		});
	}
}

function CheckAppointmentAssignment(id) {
	$.ajax({
		url: `${homeURL}/api/appointments/${id}/assignment`,
		type: 'get',
		dataType: "json",
		success: function(response) {
			console.log(response);
			if(response.success) {
				if(response.data.uuid == selected_appointment) {
					if(response.data.assigned) {
						$('#sec-appointment-bottom-right-side').append(response.data.start);
					}
				}
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

function CheckInAppointment() {
	if(selected_appointment != null) {
		$.ajax({
			url: `${homeURL}/api/appointments/check-in`,
			type: 'post',
			data: {
				appointment: selected_appointment
			},
			dataType: "json",
			success: function(response) {
				if(response.success) {
					ShowToastMessage(response.message, 'success');
					if(response.data.appointment == selected_appointment)
						modalElement.querySelector('.sec-check-in').classList.add('hidden');
					modalElement.querySelector('.appointment-modal-close').click();
					calendarInstance.refetchEvents();
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
}

function CancelAppointment() {
	if(selected_appointment != null) {
		const startDateObj = new Date(appointmentData.start);
		const endDateObj = new Date(appointmentData.end);

		const appointmentDate = startDateObj.toLocaleDateString('es-MX'); 

		const appointmentStart = startDateObj.toLocaleTimeString('es-MX', { hour: '2-digit', minute: '2-digit' });

		const appointmentEnd = endDateObj.toLocaleTimeString('es-MX', { hour: '2-digit', minute: '2-digit' });

		ShowSweetAlertConfirmCancelCallback('warning',
											'Cancelar Cita',
											`¿Deseas cancelar la cita de ${appointmentData.extendedProps.patient} 
											del día ${appointmentDate} en el horario ${appointmentStart} a ${appointmentEnd}?`,
											'Si',
											'No',
											(result) => {
			if(result.isConfirmed) {
				$.ajax({
					url: `${homeURL}/api/appointments/cancel`,
					type: 'post',
					data: {
						appointment: selected_appointment
					},
					dataType: "json",
					success: function(response) {
						if(response.success) {
							ShowToastMessage(response.message, 'success');
							modalElement.querySelector('.appointment-modal-close').click();
							calendarInstance.refetchEvents();
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
		})
	}
}

function GetAppointmentsStatus() {
	$('#sector-estatus').html('');
	$.ajax({
        url: `${homeURL}/api/appointments/status`,
		type: 'get',
		data: {
			search: ''
		},
		processData: false,
		contentType: false,
		dataType: "json",
		success: function(response) {
			var options = '';
			$.each(response.data.appointments_status, function(k, v) {
				options += `<li class="flex items-center mb-[10px]">
                                    <span 
                                        class="appointment-li-status-item text-sm capitalize"
                                        style="--dot-color: ${v.text_color};">
                                        ${v.estatus}
                                    </span>
                            </li>`;
			});
			$('#sector-estatus').html(options);
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

function GetAppointments() {
	$('#table-appointments').find('tbody').empty();
	$.ajax({
        url: `${homeURL}/api/appointments`,
		type: 'get',
		data: {
			search: ''
		},
		processData: false,
		contentType: false,
		dataType: "json",
		success: function(response) {
			var rows = '';
			$.each(response.data.appointments, function(k, v) {
				rows += `<tr class="transition duration-300 ease-in-out border-b hover:bg-neutral-100 dark:border-neutral-500 dark:hover:bg-neutral-600 cursor-pointer">
                            <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                <span class="font-medium capitalize text-dark dark:text-title-dark text-15">${v.clave}</span>
                            </td>
                            <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
								${v.nombre}
							</td>
                            <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
								${v.f_nacimiento}
							</td>
                            <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                ${v.genero}
							</td>
                            <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                ${v.telefono}
							</td>
                            <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                ${v.movil}
							</td>
                            <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                ${v.f_ultima_visita}
							</td>
                        </tr>`;
			});
			$('#table-appointments').find('tbody').append(rows);
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

function formatEventTime(event) {
    if (!event.start) return '';

    const start = event.start.toLocaleTimeString('es-MX', {
        hour: 'numeric',
        minute: '2-digit',
        hour12: true
    });

    if (!event.end) return start;

    const end = event.end.toLocaleTimeString('es-MX', {
        hour: 'numeric',
        minute: '2-digit',
        hour12: true
    });

    return `${start} – ${end}`;
}

function ClearRegisterPatient() {
	$('#field-schedule-register-patient-name').val('');
	$('#field-schedule-register-patient-lastname').val('');
	$('#field-schedule-register-patient-lastname2').val('');
	clearDatePicker(dobDatePicker);
	$('#field-schedule-register-patient-phone').val('');
	$('#field-schedule-register-patient-email').val('');
	// dobDatePicker.setDate(new Date(2026, 7, 11)); // Recuerda: los meses en JavaScript van de 0 a 11 (7 es agosto)
	// dobDatePicker.setDate('setDate', '08/11/2026');
}

function RegisterPatient() {
	if(!registeringPatient) {
		registeringPatient = true;
		$('#btn-schedule-register-patient').attr('disabled', true);
		$('#btn-schedule-cancel-patient').attr('disabled', true);
		var formElement = $('#form-register-patient')[0]; 
		var formData = new FormData(formElement);

		$.ajax({
			url: `${homeURL}/api/patients`,
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			dataType: "json",
			success: function(response) {
				$('#btn-schedule-register-patient').attr('disabled', false);
				$('#btn-schedule-cancel-patient').attr('disabled', false);
				if(response.success) {
					registeringPatient = false;
					ShowToastMessage('Paciente registrado.', 'success');
					ClearRegisterPatient();
					SearchPatients();
					selected_patient = response.data.id;
					$('#field-schedule-patient').val(`${response.data.code} - ${response.data.name}`);
					ShowNewPatient();
				} else {
					ShowToastMessage(response.message, 'error');
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) { 
				$('#btn-schedule-register-patient').attr('disabled', false);
				$('#btn-schedule-cancel-patient').attr('disabled', false);
				registeringPatient = false;
				try {
					var response = JSON.parse(XMLHttpRequest.responseText);
					ShowToastMessage(response.message, 'error');
					
				} catch (e) {
					ShowToastMessage(XMLHttpRequest.responseText, 'error');
				}
			} 
		});
	} else {
		ShowToastMessage('Hay un proceso de registro ejecutandose...', 'warning');
	}
}

function ScheduleAppointment() {
	if(selected_patient == null || selected_patient == '') {
		ShowToastMessage('Es necesario seleccionar un paciente', 'error');
		return;
	}
	if($('#select-schedule-type').val() == null) {
		ShowToastMessage('Es necesario seleccionar el tipo de consulta', 'error');
		return;
	}
	if($('#select-schedule-booking-type').val() == null) {
		ShowToastMessage('Es necesario seleccionar como se agendo la cita', 'error');
		return;
	}
	if($('#select-schedule-staff').val() == null) {
		ShowToastMessage('Es necesario seleccionar quien atiende la cita', 'error');
		return;
	}
	if($('#select-schedule-procedure').val() == null) {
		ShowToastMessage('Es necesario seleccionar el procedimiento', 'error');
		return;
	}
	let date = datePickerFormattedDate(scheduleAppointmentDatePicker);
	if(date == null || date == '') {
		ShowToastMessage('Es necesario seleccionar la fecha de la cita', 'error');
		return;
	}
	let time = $('#field-schedule-register-time').val()
	if(time == null || time == '') {
		ShowToastMessage('Es necesario seleccionar la hora de la cita', 'error');
		return;
	}

    var formData = new FormData();
	formData.append('booking_mode', 'quick');
	formData.append('appointment_type', $('#select-schedule-type').val());
	formData.append('booking_channel', $('#select-schedule-booking-type').val());
	formData.append('patient', selected_patient);
	formData.append('staff', $('#select-schedule-staff').val());
	formData.append('procedure', $('#select-schedule-procedure').val());
	formData.append('date', date);
	formData.append('time', time);
	formData.append('chief_complaint', $('#field-schedule-register-chief-complaint').val());
	$.ajax({
		url: `${homeURL}/api/appointments`,
		type: 'post',
		data: formData,
		processData: false,
		contentType: false,
		dataType: "json",
		success: function(response) {
			if(response.success) {
				ShowToastMessage(response.message, 'success');
				$('#btn-close-schedule-modal').trigger('click');
				calendarInstance.refetchEvents();
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