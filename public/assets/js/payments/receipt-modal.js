let receiptModal = null;
let sending_receipt_mail = false;

function InitializeReceiptModal() {
    receiptModal = document.getElementById('modal-receipt');
	$('#modal-receipt').on('hidden.te.modal', function () {
		$('#receipt-preview').attr('src', '');
		generated_report_id = '';
	});
	$('#btn-print-receipt').on('click', PrintReceipt);
	$('#btn-send-receipt').on('click', SendReceiptMail);
}

function ViewReport() {
	if(generated_report_id != '') {
		window.open(`${homeURL}/receipt/${generated_report_id}`, '_self');
	}
}

function PrintReceipt() {
	const iframe = document.getElementById('receipt-preview');

	iframe.contentWindow.focus();
	iframe.contentWindow.print();
}

function SendReceiptMail() {
	if(!sending_receipt_mail) {
		if(selected_payment_receipt != '') {
			if($('#field-send-receipt-email').val() != '') {
				sending_receipt_mail = true;
				$('#btn-send-receipt').attr('disabled', true);
				$.ajax({
					url: `${homeURL}/api/payments/${selected_payment_receipt}/send`,
					type: 'post',
					data: {
						method: 'email',
						recipient: $('#field-send-receipt-email').val()
					},
					dataType: "json",
					success: function(response) {
						sending_receipt_mail = false;
						$('#btn-send-receipt').attr('disabled', false);
						console.log(response);
						if(response.success) {
							ShowToastMessage(
								'El comprobante fue enviado por correo electrónico.',
								'success'
							);

							$('#field-send-receipt-email').val('');
						} else {
							ShowToastMessage(
								response.message || 'No fue posible enviar el comprobante.',
								'error'
							);
						}
					},
					error: function(XMLHttpRequest, textStatus, errorThrown) { 
						sending_receipt_mail = false;
						$('#btn-send-receipt').attr('disabled', false);
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
		} else {
			ShowToastMessage('Es necesario seleccionar un pago.', 'error');
		}
	} else {
		ShowToastMessage("Hay un proceso de envio pendiente.", "error");
	}
}

var generated_report_id = '';

function PaymentReport(id) {
	selected_payment_receipt = id;
	const modal = new te.Modal(receiptModal);
	
	$('#receipt-preview').attr('src', `${homeURL}/receipt-modal?id=${id}`);
	generated_report_id = id;

	modal.show();
}