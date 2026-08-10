// JavaScript Document

var homeURL;
let shiftOrganizations = null;
var defaultCountry = 118;
var defaultState = 19;
var loadingCountries = loadingStates = loadingMunicipalities = loadingLocalities = false;
var selectedOrganization = '';

function InitializeValues(home) {
	homeURL = home;
	shiftOrganizations = document.getElementById('modal-organizations');
	// $('#btn-registrar-usuario').on('click', function() {
	// 	window.location.href = `${homeURL}/users/add`;
	// });
	$('#select-country').on('change', GetStates);
    $('#select-state').on('change', GetMunicipalities);
    $('#select-municipality').on('change', GetLocalities);
    $('#select-locality').on('change', (e) => {
		$('#field-zip-code').val($('#select-locality option:selected').data('zip-code'));
	});
	$('#btn-new-organization').on('click', ShowRegisterOrganizationModal);
	GetOrganizations();
	GetCountries();
	$(document).on('click', '.organization-tr', function(event) {
		event.preventDefault();

		GetOrganizationData($(this).data('id'));
	});
}

function ShowRegisterOrganizationModal() {
	const modal = new te.Modal(shiftOrganizations);
	modal.show();
}

function GetCountries() {
    loadingCountries = true;
    $.ajax({
            url: `${homeURL}/api/locations/countries`,
            type: 'get',
            dataType: "json",
            success: function(response) {
                $.each(response.data.countries, function(k, v) {
                    $('#select-country').append($('<option>', {
                        value: v.id,
                        text: v.pais
                    }));

                    $('#select-facturacion-pais').append($('<option>', {
                        value: v.id,
                        text: v.pais
                    }));
                });
                loadingCountries = false;
                $('#select-country').val(defaultCountry);
                refreshSelectOption('select-country');
                $('#select-country').trigger('change');
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 
				console.log(XMLHttpRequest.responseText);
				let response = JSON.parse(XMLHttpRequest.responseText);
				ShowToastMessage(response.message, 'error')

                loadingCountries = false;
            }  
    });
}

function GetStates(object) {
    if(!loadingCountries) {
        var country_select = object.target.id;
        $(`#select-state`).empty();
        loadingStates = true;
        $.ajax({
                url: `${homeURL}/api/locations/states`,
                type: 'get',
                data: {
                    id: $(`#${country_select}`).val()
                },
                dataType: "json",
                success: function(response) {
                    $.each(response.data.states, function(k, v) {
                        $(`#select-state`).append($('<option>', {
                            value: v.id,
                            text: v.estado
                        }));
                    });
                    loadingStates = false;
                    $(`#select-state`).val(defaultState);
                	refreshSelectOption('select-state');
                    $(`#select-state`).trigger('change');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
					console.log(XMLHttpRequest.responseText);
					let response = JSON.parse(XMLHttpRequest.responseText);
					ShowToastMessage(response.message, 'error')

                    loadingStates = false;
                }  
        });
    }
}

function GetMunicipalities(object) {
    if(!loadingStates) {
        var state_select = object.target.id;
        $(`#select-municipality`).empty();
        loadingMunicipalities = true;
        $.ajax({
                url: `${homeURL}/api/locations/municipalities`,
                type: 'get',
                data: {
                    id: $(`#select-state`).val()
                },
                dataType: "json",
                success: function(response) {
                    $.each(response.data.municipalities, function(k, v) {
                        $(`#select-municipality`).append($('<option>', {
                            value: v.id,
                            text: v.municipio
                        }));
                    });
                    loadingMunicipalities = false;
                	refreshSelectOption('select-municipality');
                    $(`#select-municipality`).trigger('change');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
					console.log(XMLHttpRequest.responseText);
					let response = JSON.parse(XMLHttpRequest.responseText);
					ShowToastMessage(response.message, 'error')

                    loadingMunicipalities = false;
                }  
        });
    }
}

function GetLocalities(object) {
    if(!loadingMunicipalities) {
        var municipality_select = object.target.id;
        $(`#select-locality`).empty();
        loadingLocalities = true;
        $.ajax({
                url: `${homeURL}/api/locations/localities`,
                type: 'get',
                data: {
                    id: $(`#select-municipality`).val()
                },
                dataType: "json",
                success: function(response) {
                    $.each(response.data.localities, function(k, v) {
                        $(`#select-locality`).append($('<option>', {
                            value: v.id,
                            text: v.colonia,
							'data-zip-code': v.cp
                        }));
                    });
                    loadingLocalities = false;
                	refreshSelectOption('select-locality');
                    $(`#select-locality`).trigger('change');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
					console.log(XMLHttpRequest.responseText);
					let response = JSON.parse(XMLHttpRequest.responseText);
					ShowToastMessage(response.message, 'error')
                    loadingLocalities = false;
                }  
        });
    }
}

function GetOrganizations() {
	$('#table-organizations tbody').empty();
	$.ajax({
        url: `${homeURL}/api/organizations`,
		type: 'get',
		data: {
			search: ''
		},
		processData: false,
		contentType: false,
		dataType: "json",
		success: function(response) {
			var rows = '';
			console.log(response);
			$.each(response.data.organizations, function(k, v) {
				rows += `<tr data-id="${v.id}" class="organization-tr transition duration-300 ease-in-out border-b hover:bg-neutral-100 dark:border-neutral-500 dark:hover:bg-neutral-600 cursor-pointer">
                            <td class="px-4 py-2.5 font-normal last:text-end text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                <span class="font-medium text-dark dark:text-title-dark text-15">${v.organization}</span>
                            </td>
                            <td class="px-4 py-2.5 font-normal last:text-end text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
								${v.address}
							</td>
                            <td class="px-4 py-2.5 font-normal last:text-end text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
								${v.phone}
							</td>
                            <td class="px-4 py-2.5 font-normal last:text-end text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                ${v.mobile}
							</td>
                            <td class="px-4 py-2.5 font-normal last:text-end text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                ${v.email}
							</td>
                            <td class="px-4 py-2.5 font-normal last:text-end text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                ${v.manager}
							</td>
                            <td class="ps-4 pe-4 py-2.5 font-normal last:text-end text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent rounded-e-[4px]">
                                <span class="${v.active == 1 ? 'bg-primary' : 'bg-danger'} font-medium inline px-[11.85px] py-[4.5px] rounded-[15px] text-[13px] text-white">${v.active == 1 ? 'Si' : 'No'}</span>
                            </td>
                        </tr>`;
			});
			$('#table-organizations').append(rows);
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) { 
			console.log(XMLHttpRequest.responseText);
			let response = JSON.parse(XMLHttpRequest.responseText);
			ShowToastMessage(response.message, 'error')
		}  
	});
}

function ClearOrganizationModal() {
	$(`#field-organization`).val('');
	$(`#field-street`).val('');
	$(`#field-ext-no`).val('');
	$(`#field-int-no`).val('');
	$(`#field-phone`).val('');
	$(`#field-mobile-phone`).val('');
	$(`#field-manager`).val('');
	$(`#field-email`).val('');
	$(`#field-password`).val('');
}

function RegisterOrganization() {
	$.ajax({
			url: `${homeURL}/api/organizations`,
			type: 'post',
			data: {
				organization: $(`#field-organization`).val(),
				street: $(`#field-street`).val(),
				ext_no: $(`#field-ext-no`).val(),
				int_no: $(`#field-int-no`).val(),
				locality: $(`#select-locality`).val(),
				zip_code: $(`#field-zip-code`).val(),
				phone: $(`#field-phone`).val(),
				mobile: $(`#field-mobile-phone`).val(),
				manager: $(`#field-manager`).val(),
				email: $(`#field-email`).val(),
				password: $(`#field-password`).val()
			},
			dataType: "json",
			success: function(response) {
				console.log(response);
				if(response.success) {
					ClearOrganizationModal();
					$('#btn-close-organizations-modal').trigger('click');
					GetOrganizations();
					ShowToastMessage(response.message, 'success');
				} else {
					ShowToastMessage(response.message, 'error');
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) { 
				console.log(XMLHttpRequest.responseText);
				let response = JSON.parse(XMLHttpRequest.responseText);
				ShowToastMessage(response.message, 'error')
			}  
	});
}

function ClearSelectedOrganization() {
	$(`#field-selected-code`).val('');
	$(`#field-selected-organization`).val('');
	$(`#field-selected-address`).val('');
	$(`#field-selected-phone`).val('');
	$(`#field-selected-mobile`).val('');
	$(`#field-selected-email`).val('');
	$(`#field-selected-manager`).val('');
	$('#table-users tbody').empty();
}

function GetOrganizationData(id) {
	selectedOrganization = id;
	ClearSelectedOrganization();
	$.ajax({
        url: `${homeURL}/api/organizations/${id}`,
		type: 'get',
		processData: false,
		contentType: false,
		dataType: "json",
		success: function(response) {
			var rows = '';
			console.log(response);
			if(response.success) {
				$(`#field-selected-code`).val(response.data.code);
				$(`#field-selected-organization`).val(response.data.organization);
				$(`#field-selected-address`).val(response.data.address);
				$(`#field-selected-phone`).val(response.data.phone);
				$(`#field-selected-mobile`).val(response.data.mobile);
				$(`#field-selected-email`).val(response.data.email);
				$(`#field-selected-manager`).val(response.data.manager);

				var rows = '';
				$.each(response.data.users, function(k, v) {
					rows += `<tr data-id="${v.id}" class="organization-tr transition duration-300 ease-in-out border-b hover:bg-neutral-100 dark:border-neutral-500 dark:hover:bg-neutral-600 cursor-pointer">
								<td class="px-4 py-2.5 font-normal last:text-end text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
									<span class="font-medium text-dark dark:text-title-dark text-15">${v.name}</span>
								</td>
								<td class="px-4 py-2.5 font-normal last:text-end text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
									${v.type}
								</td>
								<td class="ps-4 pe-4 py-2.5 font-normal last:text-end text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent rounded-e-[4px]">
									<span class="${v.active == 1 ? 'bg-primary' : 'bg-danger'} font-medium inline px-[11.85px] py-[4.5px] rounded-[15px] text-[13px] text-white">${v.active == 1 ? 'Si' : 'No'}</span>
								</td>
							</tr>`;
				});
				$('#table-users tbody').append(rows);
			} else {
				ShowToastMessage(response.message, 'error');
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) { 
			console.log(XMLHttpRequest.responseText);
			let response = JSON.parse(XMLHttpRequest.responseText);
			ShowToastMessage(response.message, 'error')
		}  
	});
}