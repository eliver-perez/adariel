// JavaScript Document

var homeURL;
var new_procedure = modify_procedure = registering_procedure = modifying_procedure = false;
var selected_cash_reconciliation = null;

var loading_status = true;

function InitializeValues(home) {
	homeURL = home;
	GetCashReconciliations();
}

function GetCashReconciliations() {
	$.ajax({
        url: `${homeURL}/api/cash-reconciliation-branch`,
		type: 'get',
		data: {
			
		},
		dataType: "json",
		success: function(response) {
			console.log(response);
			if(response.success) {
				var status_badge_bg;
				$.each(response.data.cash_reconciliations, function(k, v) {
					$('#table-cash-reconciliations').append(`<tr class="transition duration-300 ease-in-out border-b hover:bg-neutral-100 dark:border-neutral-500 dark:hover:bg-neutral-600 cursor-pointer">
														<td onclick="SelectCashReconciliation('${v.id}')" class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
															<span class="font-medium capitalize text-dark dark:text-title-dark text-15">${v.folio}</span>
														</td>
														<td onclick="SelectCashReconciliation('${v.id}')" class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
															${v.opened_by}
														</td>
														<td onclick="SelectCashReconciliation('${v.id}')" class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
															${v.opened_date}
														</td>
														<td onclick="SelectCashReconciliation('${v.id}')" class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent hidden">
															${v.closed_date != '' ? v.closed_date : '<div class="inline-flex items-center gap-[10px] text-light dark:text-subtitle-dark text-[10px] capitalize"><span class="bg-danger block w-[6px] h-[6px] rounded-full"></span><span>Abierto</span></div>'}
														</td>
														<td onclick="SelectCashReconciliation('${v.id}')" class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
															${accounting.formatMoney(v.total)}
														</td>
														<td class="ps-4 pe-0 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent rounded-e-[4px]">
															<div class="text-primary dark:text-subtitle-dark text-[19px] flex justify-start p-0 m-0 gap-[5px]">
																<button type="button" class="uil uil-file-medical-alt hover:text-secondary cursor-pointer" title="Iniciar Consulta" onclick="CashReconciliationReport('${v.id}');"></button>
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

function ClearCashReconciliationData() {
	$('#field-cash-reconciliation-registrar').html('...');
	$('#field-cash-reconciliation-status').removeClass('text-primary bg-primary/20 text-secondary bg-secondary/20 text-danger bg-danger/20 text-success bg-success/20');
	$('#field-cash-reconciliation-status').addClass('text-warning bg-warning/20');
	$('#field-cash-reconciliation-status').html('...');
	$('#field-cash-reconciliation-folio').html('...');
	$('#field-cash-reconciliation-opened-by').html('...');
	$('#field-cash-reconciliation-opened-date').html('...');
	$('#field-cash-reconciliation-closed-date').html('...');
	$('#field-cash-reconciliation-closed-by').html('...');
	$('#field-cash-reconciliation-open-amount').html('...');
	$('#field-cash-reconciliation-cash-amount').html('...');
	$('#field-cash-reconciliation-other-amount').html('...');
	$('#field-cash-reconciliation-total').html('...');
	$('#field-cash-reconciliation-cancelled').html('...');
	$('#field-cash-reconciliation-deposits').html('...');
	$('#field-cash-reconciliation-withdrawals').html('...');
	$('#field-cash-reconciliation-expected-cash').html('...');
	$('#field-cash-reconciliation-closed-amount').html('...');
	$('#field-cash-reconciliation-difference').html('...');
	$('#table-payments tbody').empty();
	$('#btn-cash-reconciliation-end').attr('disabled', true);
}

function SelectCashReconciliation(id) {
	if(selected_cash_reconciliation != id) {
		selected_cash_reconciliation = id;
		ClearCashReconciliationData();
		$.ajax({
			url: `${homeURL}/api/cash-reconciliation/${id}`,
			type: 'get',
			dataType: "json",
			success: function(response) {
				console.log(response);
				if(response.success) {
					$('#field-cash-reconciliation-registrar').html(response.data.registrar);

					$('#field-cash-reconciliation-status').removeClass('text-warning bg-warning/20');
					if(response.data.status_id == 'closed')
						$('#field-cash-reconciliation-status').addClass('text-success bg-success/20');
					else if(response.data.status_id == 'busy')
						$('#field-cash-reconciliation-status').addClass('text-danger bg-danger/20');
					else if(response.data.status_id == 'open')
						$('#field-cash-reconciliation-status').addClass('text-primary bg-primary/20');
					else if(response.data.status_id == 'waiting')
						$('#field-cash-reconciliation-status').addClass('text-secondary bg-secondary/20');
					else
						$('#field-cash-reconciliation-status').addClass('text-warning bg-warning/20');
					$('#field-cash-reconciliation-status').html(response.data.status);

					$('#field-cash-reconciliation-folio').html(response.data.folio);
					$('#field-cash-reconciliation-opened-by').html(response.data.opened_by_name);
					$('#field-cash-reconciliation-opened-date').html(response.data.opened_date);
					$('#field-cash-reconciliation-closed-date').html(response.data.closed_date);
					$('#field-cash-reconciliation-closed-by').html(response.data.closed_by_name);
					$('#field-cash-reconciliation-open-amount').html(accounting.formatMoney(response.data.opened_amount));
					$('#field-cash-reconciliation-cash-amount').html(accounting.formatMoney(response.data.cash));
					$('#field-cash-reconciliation-other-amount').html(accounting.formatMoney(response.data.other_payment_methods));
					$('#field-cash-reconciliation-total').html(accounting.formatMoney(response.data.total_sale));
					$('#field-cash-reconciliation-cancelled').html(accounting.formatMoney(response.data.cancelled));
					$('#field-cash-reconciliation-deposits').html(accounting.formatMoney(response.data.cash_deposits));
					$('#field-cash-reconciliation-withdrawals').html(accounting.formatMoney(response.data.cash_withdrawals));
					$('#field-cash-reconciliation-expected-cash').html(accounting.formatMoney(response.data.expected_cash));
					$('#field-cash-reconciliation-closed-amount').html(accounting.formatMoney(response.data.closed_amount));
					$('#field-cash-reconciliation-difference').html(accounting.formatMoney(response.data.cash_difference));
					$('#field-cash-reconciliation-observations').html(response.data.observations);

					$.each(response.data.payments, function(k, v) {
						$('#table-payments tbody').append(`<tr class="transition duration-300 ease-in-out border-b hover:bg-neutral-100 dark:border-neutral-500 dark:hover:bg-neutral-600 cursor-pointer">
														<td class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
															<span class="font-medium capitalize text-dark dark:text-title-dark text-15">${v.folio}</span>
														</td>
														<td class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
															${v.client}
														</td>
														<td class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
															${v.payment_method}
														</td>
														<td class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
															${accounting.formatMoney(v.amount)}
														</td>
														<td class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
															${v.registered_by}
														</td>
														<td onclick="SelectCashReconciliation('${v.id}')" class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
															<div class="inline-flex items-center gap-[10px] text-light dark:text-subtitle-dark text-[10px] capitalize">
																<span class="${v.status == 1 ? 'bg-primary' : 'bg-danger'} rounded-[15px] py-[4px] px-[8.23px] text-[12px] font-medium leading-[13px] text-center text-white">${v.status == 1 ? 'Activo' : 'Cancelado'}</span>
															</div>
														</td>
														<td class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
															${v.payment_date}
														</td>
														<td class="ps-4 pe-0 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent rounded-e-[4px]">
															<div class="text-primary dark:text-subtitle-dark text-[19px] flex justify-start p-0 m-0 gap-[5px]">
																<button type="button" class="uil uil-eye hover:text-secondary cursor-pointer" title="Iniciar Consulta" onclick="PaymentReport('${v.id}');"></button>
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
					console.log(response);
					ShowToastMessage(XMLHttpRequest.responseText, 'error');
				}
			}  
		});
	}
}

function StartConsultation(id = null) {
	var consultation_id = id != null ? id : selected_cash_reconciliation;
	if(consultation_id != null) {
		window.open(`${homeURL}/consultations/${consultation_id}`, '_self');
	}
}