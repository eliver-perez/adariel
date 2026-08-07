// JavaScript Document

var homeURL;

var selected_payment = '';
var loading_status = true;
let receiptModal = null;

let testingMetaCloudAPIConnection = false;

function InitializeValues(home) {
	homeURL = home;
	$('#btn-meta-integration-test').on('click', TestMetaCloudAPIConnection);
	$('#btn-meta-integration-test-message').on('click', SendWhatsAppTestMessage);
	LoadIntegrationData();
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
				if(response.data.provider == 'meta') {
					$('#field-meta-phone-number-id').val(response.data.settings.phone_number_id);
					$('#field-meta-business-account-id').val(response.data.settings.whatsapp_business_account_id);
					$('#field-meta-access-token').val('');
					$('#chk-meta-active').prop('checked', response.data.active == 1 ? true : false);
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