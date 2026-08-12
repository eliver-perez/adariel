// JavaScript Document

var homeURL;

var selected_payment = '';
var loading_status = true;
let receiptModal = null;

function InitializeValues(home) {
	homeURL = home;
	receiptModal = document.getElementById('modal-receipt');
	$('#modal-receipt').on('hidden.te.modal', function () {
		$('#receipt-preview').attr('src', '');
		generated_report_id = '';
	});
	$('#btn-print-receipt').on('click', PrintReceipt);
	GetPayments();
}

function PrintReceipt() {
	const iframe = document.getElementById('receipt-preview');

	iframe.contentWindow.focus();
	iframe.contentWindow.print();
}

function GetPayments() {
	$.ajax({
        url: `${homeURL}/api/payments-branch`,
		type: 'get',
		data: {
			
		},
		dataType: "json",
		success: function(response) {
			console.log(response);
			if(response.success) {
				$.each(response.data.payments, function(k, v) {
					$('#table-payments tbody').append(`<tr class="transition duration-300 ease-in-out border-b hover:bg-neutral-100 dark:border-neutral-500 dark:hover:bg-neutral-600 cursor-pointer">
													<td onclick="SelectPayment('${v.id}');" class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
														<span class="font-medium capitalize text-dark dark:text-title-dark text-15">${v.folio}</span>
													</td>
													<td onclick="SelectPayment('${v.id}');" class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
														${v.client}
													</td>
													<td onclick="SelectPayment('${v.id}');" class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
														${v.payment_method}
													</td>
													<td onclick="SelectPayment('${v.id}');" class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
														${accounting.formatMoney(v.amount)}
													</td>
													<td onclick="SelectPayment('${v.id}');" class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
														${v.registered_by}
													</td>
													<td onclick="SelectPayment('${v.id}');" class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
														<div class="inline-flex items-center gap-[10px] text-light dark:text-subtitle-dark text-[10px] capitalize">
															<span class="${v.status == 1 ? 'bg-primary' : 'bg-danger'} rounded-[15px] py-[4px] px-[8.23px] text-[12px] font-medium leading-[13px] text-center text-white">${v.status == 1 ? 'Activo' : 'Cancelado'}</span>
														</div>
													</td>
													<td onclick="javascript:SelectPayment('${v.id}');" class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
														${v.payment_date}
													</td>
													<td class="ps-4 pe-0 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent rounded-e-[4px]">
														<div class="text-primary dark:text-subtitle-dark text-[19px] flex px-2.5 justify-start m-0 gap-[5px]">
															<button type="button" class="uil uil-eye hover:text-secondary cursor-pointer" title="Visualizar Recibo" onclick="PaymentReport('${v.id}');"></button>
														</div>
													</td>
												</tr>`);
				});
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

function ClearPaymentData() {
	$('#field-payment-registrar').html('...');
	$('#field-payment-status').removeClass('text-primary bg-primary/20 text-secondary bg-secondary/20 text-danger bg-danger/20 text-success bg-success/20');
	$('#field-payment-status').addClass('text-warning bg-warning/20');
	$('#field-payment-status').html('...');
	$('#field-payment-folio').html('...');
	$('#btn-payment-cancel').attr('disabled', true);
	$('#table-sales tbody').empty();
	$('#table-payment-details tbody').empty();
}

function SelectPayment(id) {
	if(selected_payment != id) {
		selected_payment = id;
		ClearPaymentData();
		$.ajax({
			url: `${homeURL}/api/payments/${id}`,
			type: 'get',
			dataType: "json",
			success: function(response) {
				console.log(response);
				if(response.success) {
					$('#field-payment-cash-reconciliation').html(response.data.cash_reconciliation_folio);

					$('#field-payment-status').removeClass('text-warning bg-warning/20');
					if(response.data.status == 1)
						$('#field-payment-status').addClass('text-success bg-success/20');
					else if(response.data.status == 2)
						$('#field-payment-status').addClass('text-danger bg-danger/20');
					else
						$('#field-payment-status').addClass('text-warning bg-warning/20');
					$('#field-payment-status').html(response.data.status == 1 ? 'Activo' : 'Cancelado');

					$('#field-payment-folio').html(response.data.folio);
					
					$.each(response.data.sales, function(k, v) {
						$('#table-sales tbody').append(`<tr class="transition duration-300 ease-in-out border-b hover:bg-neutral-100 dark:border-neutral-500 dark:hover:bg-neutral-600 cursor-pointer">
														<td class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
															<span class="font-medium capitalize text-dark dark:text-title-dark text-15">${v.folio}</span>
														</td>
														<td class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
															${v.client}
														</td>
														<td class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
															${v.patient}
														</td>
														<td class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
															${accounting.formatMoney(v.balance_due_before)}
														</td>
														<td class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
															${accounting.formatMoney(v.payment_amount)}
														</td>
														<td class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
															${v.sale_date}
														</td>
														<td class="ps-4 pe-0 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent rounded-e-[4px]">
															<div class="text-primary dark:text-subtitle-dark text-[19px] flex px-2.5 justify-start m-0 gap-[5px]">
																<button type="button" class="uil uil-eye hover:text-secondary cursor-pointer" title="Detalles de Venta" onclick="SaleDetails('${v.id}');"></button>
															</div>
														</td>
													</tr>`);
					});
					
					$.each(response.data.payment_details, function(k, v) {
						$('#table-payment-details tbody').append(`<tr class="transition duration-300 ease-in-out border-b hover:bg-neutral-100 dark:border-neutral-500 dark:hover:bg-neutral-600 cursor-pointer">
														<td class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
															<span class="font-medium capitalize text-dark dark:text-title-dark text-15">${v.sale_folio}</span>
														</td>
														<td class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
															${v.quantity}
														</td>
														<td class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
															${v.description}
														</td>
														<td class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
															${accounting.formatMoney(v.base_cost)}
														</td>
														<td class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
															${accounting.formatMoney(v.total)}
														</td>
														<td class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
															${accounting.formatMoney(v.balance_before_payment)}
														</td>
														<td class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
															${accounting.formatMoney(v.payment_amount)}
														</td>
													</tr>`);
					});
				}
			},
			error: function(XMLHttpRequest, textStatus, errorThrown) { 
				try {
					var response = JSON.parse(XMLHttpRequest.responseText);
					console.log(response.message);
					ShowToastMessage(response.message, 'error');
					
				} catch (e) {
					console.log(response);
					ShowToastMessage(XMLHttpRequest.responseText, 'error');
				}
			}  
		});
	}
}

var generated_report_id = '';

function PaymentReport(id) {
	const modal = new te.Modal(receiptModal);
	
	$('#receipt-preview').attr('src', `${homeURL}/receipt-modal?id=${id}`);
	generated_report_id = id;

	modal.show();
}

function ViewReport() {
	if(generated_report_id != '') {
		window.open(`${homeURL}/receipt/${generated_report_id}`, '_self');
	}
}