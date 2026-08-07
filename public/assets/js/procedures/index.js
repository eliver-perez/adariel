// JavaScript Document

var homeURL;
var new_procedure = modify_procedure = registering_procedure = modifying_procedure = false;
var selected_procedure = '';

function InitializeValues(home) {
	homeURL = home;
	$('#btn-nuevo-servicio').on('click', NewProcedure);
	$('#btn-cancelar-servicio').on('click', CancelProcedure);
	GetProcedures();
}

function NewProcedure() {
	if(!new_procedure && !modify_procedure && !registering_procedure && !modifying_procedure) {
		EnableProcedure(false);
		new_procedure = true;
		$('#btn-nuevo-servicio').prop('disabled', true);
		$('#btn-modificar-servicio').prop('disabled', true);
		$('#btn-registrar-servicio').prop('disabled', false);
		$('#btn-cancelar-servicio').prop('disabled', false);
		$('#btn-nuevo-servicio').addClass('!visible hidden');
		$('#btn-modificar-servicio').addClass('!visible hidden');
		$('#btn-registrar-servicio').removeClass('!visible hidden');
		$('#btn-cancelar-servicio').removeClass('!visible hidden');
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
		$('#btn-nuevo-servicio').prop('disabled', false);
		if(selected_procedure != '') {
			$('#btn-modificar-servicio').prop('disabled', false);
			$('#btn-modificar-servicio').removeClass('!visible hidden');
		}
		$('#btn-registrar-servicio').prop('disabled', true);
		$('#btn-cancelar-servicio').prop('disabled', true);
		$('#btn-nuevo-servicio').removeClass('!visible hidden');
		$('#btn-registrar-servicio').addClass('!visible hidden');
		$('#btn-cancelar-servicio').addClass('!visible hidden');
	}
}

function GetProcedures() {
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
	alert(id);
}

function ClearProcedure() {
	$('#field-procedure').val('');
	$('#field-description').val('');
	$('#field-duration').val('');
	$('#field-base-cost').val('');
	$('#chk-requires-material').prop('checked', false);
	$('#chk-is-procedure').prop('checked', false);
	$('#chk-is-active').prop('checked', false);
}

function RegisterProcedure() {
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
				ClearProcedure();
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
}