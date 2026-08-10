// JavaScript Document

var homeURL;
let proceduresStaffModal = null;
var new_procedure = modify_procedure = registering_procedure = modifying_procedure = false;
var selected_procedure_id = '', selected_procedure = '', selected_cost = 0;

function InitializeValues(home) {
	homeURL = home;
	proceduresStaffModal = document.getElementById('modal-procedures-staff');
	$('#btn-new-procedure').on('click', NewProcedure);
	$('#btn-cancel-procedure').on('click', CancelProcedure);
	$('#btn-modify-procedure').on('click', StartModifyProcedure);
	$('#btn-procedure-staff').on('click', ShowProceduresStaffModal);
	GetProcedures();
	GetStaff();
}

function ShowProceduresStaffModal() {
	if(selected_procedure_id != '') {
		$('#field-procedure-staff-procedure').val(selected_procedure);
		$('#field-procedure-staff-cost').val(accounting.formatMoney(selected_cost, ''));
		const modal = new te.Modal(proceduresStaffModal);
		modal.show();
	}
}

function GetStaff() {
	$.ajax({
        url: `${homeURL}/api/staff`,
		type: 'get',
		dataType: "json",
		success: function(response) {
			var rows = '';
			console.log(response);
			if(response.success) {
                $.each(response.data.staff, function(k, v) {
                    $('#select-procedure-staff').append($('<option>', {
                        value: v.id,
                        text: v.name
                    }));
                });
			} else {
				ShowToastMessage(response.message, 'error');
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) { 
			console.log('STATUS:', textStatus);
			console.log('ERROR:', errorThrown);
			console.log('RESPONSE TEXT:', XMLHttpRequest.responseText);

			alert(XMLHttpRequest.responseText);
		}  
	});
}

function NewProcedure() {
	if(!new_procedure && !modify_procedure && !registering_procedure && !modifying_procedure) {
		EnableProcedure(false);
		new_procedure = true;
		$('#btn-new-procedure').prop('disabled', true);
		$('#btn-modify-procedure').prop('disabled', true);
		$('#btn-register-procedure').prop('disabled', false);
		$('#btn-cancel-procedure').prop('disabled', false);
		$('#btn-new-procedure').addClass('!visible hidden');
		$('#btn-modify-procedure').addClass('!visible hidden');
		$('#btn-register-procedure').removeClass('!visible hidden');
		$('#btn-cancel-procedure').removeClass('!visible hidden');
		$('#btn-register-procedure').html('Registrar');
	}
}

function StartModifyProcedure() {
	if(!new_procedure && !modify_procedure && !registering_procedure && !modifying_procedure) {
		EnableProcedure(false);
		modify_procedure = true;
		$('#btn-new-procedure').prop('disabled', true);
		$('#btn-modify-procedure').prop('disabled', true);
		$('#btn-register-procedure').prop('disabled', false);
		$('#btn-cancel-procedure').prop('disabled', false);
		$('#btn-new-procedure').addClass('!visible hidden');
		$('#btn-modify-procedure').addClass('!visible hidden');
		$('#btn-register-procedure').removeClass('!visible hidden');
		$('#btn-cancel-procedure').removeClass('!visible hidden');
		$('#btn-register-procedure').html('Modificar');
	}
}

function EnableProcedure(v) {
	$('#field-procedure').prop('disabled', v);
	$('#field-description').prop('disabled', v);
	$('#field-duration').prop('disabled', v);
	$('#field-base-cost').prop('disabled', v);
	$('#chk-requires-material').prop('disabled', v);
	$('#chk-is-procedure').prop('disabled', v);
	$('#chk-is-active').prop('disabled', v);
	$('#chk-is-active').prop('checked', true);
}

function CancelProcedure() {
	if(registering_procedure || modifying_procedure)
		return;
	if(new_procedure || modify_procedure) {
		EnableProcedure(true);
		ClearProcedure();
		new_procedure = false;
		modify_procedure = false;
		$('#btn-new-procedure').prop('disabled', false);
		if(selected_procedure_id != '') {
			$('#btn-modify-procedure').prop('disabled', false);
			$('#btn-modify-procedure').removeClass('!visible hidden');
		}
		$('#btn-register-procedure').prop('disabled', true);
		$('#btn-cancel-procedure').prop('disabled', true);
		$('#btn-new-procedure').removeClass('!visible hidden');
		$('#btn-register-procedure').addClass('!visible hidden');
		$('#btn-cancel-procedure').addClass('!visible hidden');
		if(selected_procedure_id != '')
			SelectProcedure(selected_procedure_id);
	}
}

function GetProcedures() {
	$('#table-procedures tbody').empty();
	$.ajax({
        url: `${homeURL}/api/procedures`,
		type: 'get',
		data: {
			search: ''
		},
		processData: false,
		contentType: false,
		dataType: "json",
		success: function(response) {
			console.log(response);
			var rows = '';
			$.each(response.data.procedures, function(k, v) {
				rows += `<tr onclick="SelectProcedure('${v.id}')" class="transition duration-300 ease-in-out border-b hover:bg-neutral-100 dark:border-neutral-500 dark:hover:bg-neutral-600 cursor-pointer">
                            <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                <span class="font-medium capitalize text-dark dark:text-title-dark text-15">${v.procedure}</span>
                            </td>
                            <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
								${v.duration} min
							</td>
                            <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
								${accounting.formatMoney(v.base_cost)}
							</td>
                            <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                ${v.material_required == 1 ? 'Si' : 'No'}
							</td>
                            <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                ${v.is_procedure == 1 ? 'Si' : 'No'}
							</td>
                            <td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                ${v.active == 1 ? 'Activo' : 'Inactivo'}
							</td>
                        </tr>`;
			});
			$('#table-procedures').append(rows);
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) { 
			try {
				var response = JSON.parse(XMLHttpRequest.responseText);
				console.log(response.message);
				ShowToastMessage(response.message, 'error');
				
			} catch (e) {
				ShowToastMessage(XMLHttpRequest.responseText, 'error');
			}
		}  
	});
}

function SelectProcedure(id) {
	if(id != null && !new_procedure && !modify_procedure && !registering_procedure && !modifying_procedure) {
		ClearProcedure();
		selected_procedure_id = id;
		selected_procedure = '';
		selected_cost = 0;
		$.ajax({
			url: `${homeURL}/api/procedures/${id}`,
			type: 'get',
			dataType: "json",
			success: function(response) {
				console.log(response);
				if(response.success) {
					selected_procedure = response.data.procedure;
					selected_cost = response.data.base_cost;
					$('#field-procedure').val(response.data.procedure);
					$('#field-description').val(response.data.description);
					$('#field-duration').val(response.data.duration);
					$('#field-base-cost').val(accounting.formatMoney(response.data.base_cost, ''));
					$('#chk-requires-material').prop('checked', response.data.requires_material);
					$('#chk-is-procedure').prop('checked', response.data.is_procedure);
					$('#chk-is-active').prop('checked', response.data.is_active);

					$('#btn-modify-procedure').removeClass('!visible hidden');
					$('#btn-modify-procedure').prop('disabled', false);

					var staffRows = '';
					$.each(response.data.staff, function(k, v) {
						staffRows += `<tr class="transition duration-300 ease-in-out border-b hover:bg-neutral-100 dark:border-neutral-500 dark:hover:bg-neutral-600 cursor-pointer">
									<td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
										<span class="font-medium capitalize text-dark dark:text-title-dark text-15">${v.name}</span>
									</td>
									<td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
										${accounting.formatMoney(v.cost)}
									</td>
									<td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
										
									</td>
								</tr>`;
					});
					$('#table-procedure-staff tbody').append(staffRows);
				} else {
					ShowToastMessage(response.message, 'error');
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) { 
				try {
					var response = JSON.parse(XMLHttpRequest.responseText);
					console.log(response.message);
					ShowToastMessage(response.message, 'error');
					
				} catch (e) {
					ShowToastMessage(XMLHttpRequest.responseText, 'error');
				}
			}  
		});
	}
}

function ClearProcedure() {
	$('#field-procedure').val('');
	$('#field-description').val('');
	$('#field-duration').val('');
	$('#field-base-cost').val('');
	$('#chk-requires-material').prop('checked', false);
	$('#chk-is-procedure').prop('checked', false);
	$('#chk-is-active').prop('checked', false);
	$('#table-procedure-staff tbody').empty();
	$('#table-procedure-instructions tbody').empty();
	$('#table-procedure-inventory tbody').empty();
	$('#table-procedure-consent tbody').empty();
	$('#table-procedure-consent-items tbody').empty();
}

function RegisterProcedure() {
	if(!registering_procedure && !modifying_procedure) {
		if(new_procedure) {
			$.ajax({
				url: `${homeURL}/api/procedures`,
				type: 'post',
				data: {
					procedure: $('#field-procedure').val(),
					description: $('#field-description').val(),
					duration: $('#field-duration').val(),
					base_cost: $('#field-base-cost').val(),
					requires_material: $('#chk-requires-material').is(':checked') ? 1 : 0,
					is_procedure: $('#chk-is-procedure').is(':checked') ? 1 : 0,
					is_active: $('#chk-is-active').is(':checked') ? 1 : 0,
				},
				dataType: "json",
				success: function(response) {
					console.log(response);
					if(response.success) {
						CancelProcedure();
						ShowToastMessage(response.message, 'success');
						GetProcedures();
					} else {
						ShowToastMessage(response.message, 'error');
					}
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) { 
					try {
						var response = JSON.parse(XMLHttpRequest.responseText);
						console.log(response.message);
						ShowToastMessage(response.message, 'error');
						
					} catch (e) {
						ShowToastMessage(XMLHttpRequest.responseText, 'error');
					}
				}  
			});
		} else {
			if(selected_procedure_id != '') {
				$.ajax({
					url: `${homeURL}/api/procedures/${selected_procedure_id}`,
					type: 'put',
					data: {
						procedure: $('#field-procedure').val(),
						description: $('#field-description').val(),
						duration: $('#field-duration').val(),
						base_cost: $('#field-base-cost').val(),
						requires_material: $('#chk-requires-material').is(':checked') ? 1 : 0,
						is_procedure: $('#chk-is-procedure').is(':checked') ? 1 : 0,
						is_active: $('#chk-is-active').is(':checked') ? 1 : 0,
					},
					dataType: "json",
					success: function(response) {
						console.log(response);
						if(response.success) {
							CancelProcedure();
							ShowToastMessage(response.message, 'success');
							GetProcedures();
						} else {
							ShowToastMessage(response.message, 'error');
						}
					},
					error: function(XMLHttpRequest, textStatus, errorThrown) { 
						try {
							var response = JSON.parse(XMLHttpRequest.responseText);
							console.log(response.message);
							ShowToastMessage(response.message, 'error');
							
						} catch (e) {
							ShowToastMessage(XMLHttpRequest.responseText, 'error');
						}
					}  
				});
			} else {
				ShowToastMessage('No hay un servicio/procedimiento seleccionado.', 'error');
			}
		}
	}
}

function RegisterProcedureStaff() {
	if(selected_procedure_id != '' && $('#select-procedure-staff').val() != null) {
		$.ajax({
			url: `${homeURL}/api/procedures/${selected_procedure_id}/staff/${$('#select-procedure-staff').val()}`,
			type: 'post',
			data: {
				cost: $('#field-procedure-staff-cost').val(),
			},
			dataType: "json",
			success: function(response) {
				console.log(response);
				if(response.success) {
					$('#btn-close-procedures-staff-modal').trigger('click');
					SelectProcedure(selected_procedure_id);
					ShowToastMessage(response.message, 'success');
				} else {
					ShowToastMessage(response.message, 'error');
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) { 
				try {
					var response = JSON.parse(XMLHttpRequest.responseText);
					console.log(response.message);
					ShowToastMessage(response.message, 'error');
					
				} catch (e) {
					ShowToastMessage(XMLHttpRequest.responseText, 'error');
				}
			}  
		});
	} else {
		ShowToastMessage('No hay un servicio/procedimiento seleccionado.', 'error');
	}
}