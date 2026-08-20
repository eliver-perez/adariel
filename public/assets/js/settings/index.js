// JavaScript Document

var homeURL;

var selected_payment = '';
var loading_status = true;
let receiptModal = null;

let loadingOrganizationData = false;
let organizationCountry = null;
let organizationState = null;
let organizationMunicipality = null;
let organizationLocality = null;
let organizationZipCode = '';

let testingMetaCloudAPIConnection = false;

let uploadingOrganizationLogo = false;

const inputLogo = document.getElementById('file-organization-logo');
const fileLogoName = document.getElementById('file-logo-name');
const previewLogo = document.getElementById('logo-preview');

let previewUrl = null;

function InitializeValues(home) {
	homeURL = home;
	$('#btn-meta-integration-test').on('click', TestMetaCloudAPIConnection);
	$('#btn-meta-integration-test-message').on('click', SendWhatsAppTestMessage);
	LoadIntegrationData();
	$('#select-country').on('change', GetStates);
    $('#select-state').on('change', GetMunicipalities);
    $('#select-municipality').on('change', GetLocalities);
    $('#select-locality').on('change', (e) => {
		if(!loadingOrganizationData)
			$('#field-zip-code').val($('#select-locality option:selected').data('zip-code'));
		else
			$('#field-zip-code').val(organizationZipCode);
		loadingOrganizationData = false;
	});
	GetCountries();
	GetOrganizationData(organization);
	inputLogo.addEventListener('change', function () {
		const file = this.files[0];

		if (!file) {
			return;
		}

		const maxSize = 2000 * 1024;

		if (file.size > maxSize) {
			ShowToastMessage('El logotipo no puede pesar más de 2 MB.', 'error');
			this.value = '';
			return;
		}

		fileLogoName.textContent = file.name;

		if (previewUrl) {
			URL.revokeObjectURL(previewUrl);
		}
		previewUrl = URL.createObjectURL(file);
		previewLogo.src = previewUrl;
	});
	document.getElementById('btn-upload-logo').addEventListener('click', UploadOrganizationLogo);
}

function UploadOrganizationLogo() {
	if(!uploadingOrganizationLogo) {
		uploadingOrganizationLogo = true;
		$('#btn-upload-logo').attr('disabled', true);
		const file = inputLogo.files[0];

		if (!file) {
			ShowToastMessage('Selecciona un logotipo.', 'error');
			return;
		}

		const formData = new FormData();
		formData.append('logo', file);
		formData.append('type', 'organization');
		
		$.ajax({
			url: `${homeURL}/api/organizations/${organization}/logo`,
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false,
			dataType: "json",
			success: function(response) {
				uploadingOrganizationLogo = false;
				$('#btn-upload-logo').attr('disabled', false);
				console.log(response);
				if(response.success) {
					ShowToastMessage(response.message, 'success');
				} else {
					ShowToastMessage(response.message, 'error');
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) { 
				uploadingOrganizationLogo = false;
				$('#btn-upload-logo').attr('disabled', false);
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
                });
                loadingCountries = false;
				if(organizationCountry != null && loadingOrganizationData) {
					$('#select-country').val(organizationCountry);
					refreshSelectOption('select-country');
					$('#select-country').trigger('change');
				}
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 
                loadingCountries = false;
				try {
					let response = JSON.parse(XMLHttpRequest.responseText);
					ShowToastMessage(response.message, 'error')
				} catch(e) {
					ShowToastMessage(XMLHttpRequest.responseText, 'error');
				}
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
					if(organizationState != null && loadingOrganizationData) {
						$(`#select-state`).val(organizationState);
						refreshSelectOption('select-state');
						$(`#select-state`).trigger('change');
					}
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    loadingStates = false;
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
					if(organizationMunicipality != null && loadingOrganizationData) {
						$(`#select-municipality`).val(organizationMunicipality);
						refreshSelectOption('select-municipality');
						$(`#select-municipality`).trigger('change');
					}
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    loadingMunicipalities = false;
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
					if(organizationLocality != null && loadingOrganizationData) {
						$(`#select-locality`).val(organizationLocality);
						refreshSelectOption('select-locality');
						$(`#select-locality`).trigger('change');
					}
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    loadingLocalities = false;
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

function GetOrganizationData(id) {
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
				loadingOrganizationData = true;
				$(`#field-organization`).val(response.data.organization);
				$(`#field-street`).val(response.data.street);
				$(`#field-ext-no`).val(response.data.no_ext);
				$(`#field-int-no`).val(response.data.no_int);
				if(response.data.logo != null) {
					console.log(`${homeURL}/media/${response.data.logo}`);
					$('#logo-preview').attr('src', `${homeURL}/media/${response.data.logo}`);
				}
				organizationCountry = response.data.country_id;
				organizationState = response.data.state_id;
				organizationMunicipality = response.data.municipality_id;
				organizationLocality = response.data.locality_id;
				organizationZipCode = response.data.zip_code;
				$(`#field-phone`).val(response.data.phone);
				$(`#field-mobile-phone`).val(response.data.mobile);
				$(`#field-email`).val(response.data.email);
				$(`#field-manager`).val(response.data.manager);

                $('#select-country').val(organizationCountry);
                refreshSelectOption('select-country');
                $('#select-country').trigger('change');
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

function LoadIntegrationData() {
	$.ajax({
		url: `${homeURL}/api/whatsapp-integration`,
		type: 'GET',

		contentType: 'application/json; charset=utf-8',
		dataType: 'json',
		processData: false,

		success: function(response) {
			console.log(response);
			if (response.success) {
				if(response.data != null) {
					if(response.data.provider == 'meta') {
						$('#field-meta-phone-number-id').val(response.data.settings.phone_number_id);
						$('#field-meta-business-account-id').val(response.data.settings.whatsapp_business_account_id);
						$('#field-meta-access-token').val('');
						$('#chk-meta-active').prop('checked', response.data.active == 1 ? true : false);
					}
				}
			}
		},

		error: function(xhr) {
			console.log(xhr);
			try {
				const response = JSON.parse(xhr.responseText);

				ShowToastMessage(
					response.message || 'No fue posible consultar la informacion de las integraciones.',
					'error'
				);
			} catch (error) {
				ShowToastMessage(
					xhr.responseText || 'Ocurrió un error inesperado.',
					'error'
				);
			}
		}
	});
}

function RegisterMetaCloudAPI() {
	const payload = {
		provider: 'meta',
		configuration: {
			phone_number_id:
				$('#field-meta-phone-number-id').val().trim(),

			business_account_id:
				$('#field-meta-business-account-id').val().trim()
		},
		credentials: {
			access_token:
				$('#field-meta-access-token').val().trim()
		},
		active: $('#chk-meta-active').is(':checked')
	};
	
	$.ajax({
		url: `${homeURL}/api/whatsapp-integration`,
		type: 'POST',

		contentType: 'application/json; charset=utf-8',
		dataType: 'json',
		processData: false,

		data: JSON.stringify(payload),

		success: function(response) {
			if (response.success) {
				ShowToastMessage(response.message, 'success');
			}
		},

		error: function(xhr) {
			try {
				const response = JSON.parse(xhr.responseText);

				ShowToastMessage(
					response.message || 'No fue posible guardar la integración.',
					'error'
				);
			} catch (error) {
				ShowToastMessage(
					xhr.responseText || 'Ocurrió un error inesperado.',
					'error'
				);
			}
		}
	});
}

function TestMetaCloudAPIConnection() {
	if(!testingMetaCloudAPIConnection) {
		testingMetaCloudAPIConnection = true;
		const button = $('#btn-meta-integration-test');

		button.prop('disabled', true);

		$.ajax({
			url: `${homeURL}/api/whatsapp-integration/test-connection`,
			type: 'POST',
			dataType: 'json',

			success: function(response) {
				if (!response.success) {
					ShowToastMessage(response.message, 'error');
					return;
				}

				const phone = response.data?.phone_number;
				const name = response.data?.verified_name;

				let message = response.message;

				if (phone) {
					message += ` Número: ${phone}.`;
				}

				if (name) {
					message += ` Nombre: ${name}.`;
				}

				ShowToastMessage(message, 'success');
			},

			error: function(xhr) {
				const message =
					xhr.responseJSON?.message ||
					'No fue posible probar la conexión.';

				ShowToastMessage(message, 'error');
			},

			complete: function() {
				testingMetaCloudAPIConnection = false;
				button.prop('disabled', false);
			}
		});
	}
}

function SendWhatsAppTestMessage() {
	const recipient = $('#field-meta-test-recipient')
		.val()
		.trim();

	if (!recipient) {
		ShowToastMessage(
			'Debes capturar el número destinatario.',
			'error'
		);

		return;
	}

	const button = $('#btn-meta-integration-test-message');

	button.prop('disabled', true);

	$.ajax({
		url: `${homeURL}/api/whatsapp-messages/test`,
		type: 'POST',

		contentType: 'application/json; charset=utf-8',
		dataType: 'json',
		processData: false,

		data: JSON.stringify({
			recipient: recipient,
			template: 'hello_world',
			language: 'en_US'
		}),

		success: function(response) {
			ShowToastMessage(
				response.message,
				'success'
			);
		},

		error: function(xhr) {
			console.log(xhr);
			const message =
				xhr.responseJSON?.message ||
				'No fue posible enviar el mensaje de prueba.';

			ShowToastMessage(message, 'error');
		},

		complete: function() {
			button.prop('disabled', false);
		}
	});
}