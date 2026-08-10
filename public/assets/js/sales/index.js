// JavaScript Document

var homeURL;

var selected_sale = '';
var loading_status = true;
var searchTimer = null, searchValue = '';

function InitializeValues(home) {
	homeURL = home;
	$('#modal-receipt').on('hidden.te.modal', function () {
		$('#receipt-preview').attr('src', '');
		generated_report_id = '';
	});
	$('#select-filter-sales-status').on('change', GetBranchSales);
	$('#field-filter-sales-search').on('keyup', function(e) {
		if($('#field-filter-sales-search').val() != searchValue) {
			searchValue = $('#field-filter-sales-search').val();
			clearTimeout(searchTimer);
			searchTimer = setTimeout(function () {
				GetBranchSales();
			}, 500);
		}
	});
	GetSalesStatus();
}

function GetSalesStatus() {
	$.ajax({
        url: `${homeURL}/api/sales/status`,
		type: 'get',
		data: {
			search: $("#field-filter-sales-search").val(),
			status: $('#select-filter-sales-status').val()
		},
		dataType: "json",
		success: function(response) {
			console.log(response);
			$.each(response.data.sales_status, function(k, v) {
				$('#select-filter-sales-status').append($('<option>', {
					value: v.codigo,
					text: v.estatus
				}));
			});
			$('#select-filter-sales-status').val(0);
			loading_status = false;
			$('#select-filter-sales-status').trigger('change');
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

function GetBranchSales() {
	$('#table-sales tbody').empty();
	$.ajax({
        url: `${homeURL}/api/sales-branch`,
		type: 'get',
		data: {
			search: $('#field-filter-sales-search').val(),
			status: $('#select-filter-sales-status').val()
		},
		dataType: "json",
		success: function(response) {
			console.log(response);
			if(response.success) {
				var status_color = 'primary';
				$.each(response.data.sales, function(k, v) {
					if(v.status_code == 'pendiente') {
						status_color = 'bg-warning';
					} else if(v.status_code == 'pagado') {
						status_color = 'bg-primary';
					} else if(v.status_code == 'cancelado') {
						status_color = 'bg-danger';
					}
					$('#table-sales tbody').append(`<tr class="transition duration-300 ease-in-out border-b hover:bg-neutral-100 dark:border-neutral-500 dark:hover:bg-neutral-600 cursor-pointer">
													<td onclick="SelectSale('${v.id}');" class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
														<span class="font-medium capitalize text-dark dark:text-title-dark text-15">${v.folio}</span>
													</td>
													<td onclick="SelectSale('${v.id}');" class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
														${v.name}
													</td>
													<td onclick="SelectSale('${v.id}');" class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
														${accounting.formatMoney(v.total)}
													</td>
													<td onclick="SelectSale('${v.id}');" class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
														${accounting.formatMoney(v.discount)}
													</td>
													<td onclick="SelectSale('${v.id}');" class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
														${accounting.formatMoney(v.paid)}
													</td>
													<td onclick="SelectSale('${v.id}');" class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
														${accounting.formatMoney(v.balance_due)}
													</td>
													<td onclick="SelectSale('${v.id}');" class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
														${v.registered_by}
													</td>
													<td onclick="SelectSale('${v.id}');" class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
														<div class="inline-flex items-center gap-[10px] text-light dark:text-subtitle-dark text-[10px] capitalize">
															<span class="${status_color} rounded-[15px] py-[4px] px-[8.23px] text-[12px] font-medium leading-[13px] text-center text-white">${v.status}</span>
														</div>
													</td>
													<td onclick="javascript:SelectSale('${v.id}');" class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
														${v.sale_date}
													</td>
													<td class="ps-4 pe-0 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent rounded-e-[4px]">
														<div class="text-primary dark:text-subtitle-dark text-[19px] flex px-2.5 justify-start m-0 gap-[5px]">
															<button type="button" class="uil uil-eye hover:text-secondary cursor-pointer" title="Visualizar Recibo" onclick="SaleReport('${v.id}');"></button>
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

function ClearSaleData() {
	$('#field-sale-client-tag').html('...');
	$('#field-sale-status').removeClass('text-primary bg-primary/20 text-secondary bg-secondary/20 text-danger bg-danger/20 text-success bg-success/20');
	$('#field-sale-status').addClass('text-warning bg-warning/20');
	$('#field-sale-status').html('...');
	$('#field-sale-folio').html('...');
	$('#field-sale-client').html('...');
	$('#field-sale-patient').html('...');
	$('#field-sale-date').html('...');
	$('#field-sale-subtotal').html('...');
	$('#field-sale-taxes').html('...');
	$('#field-sale-total').html('...');
	$('#field-sale-discount').html('...');
	$('#field-sale-paid').html('...');
	$('#field-sale-balance-due').html('...');
	$('#field-sale-observations').html('...');
	$('#btn-sale-cancel').attr('disabled', true);
	$('#table-sale-details tbody').empty();
}

function SelectSale(id) {
	if(selected_sale != id) {
		selected_sale = id;
		ClearSaleData();
		$.ajax({
			url: `${homeURL}/api/sales/${id}`,
			type: 'get',
			dataType: "json",
			success: function(response) {
				console.log(response);
				if(response.success) {
					$('#field-sale-client-tag').html(response.data.client ?? '');
					
					$('#field-sale-status').removeClass('text-warning bg-warning/20');
					if(response.data.status_code == 'pendiente') {
						$('#field-sale-status').addClass('text-warning bg-warning/20');
					} else if(response.data.status_code == 'pagado') {
						$('#field-sale-status').addClass('text-primary bg-primary/20');
					} else if(response.data.status_code == 'cancelado') {
						$('#field-sale-status').addClass('text-danger bg-danger/20');
					}
					$('#field-sale-status').html(response.data.status);

					$('#field-sale-folio').html(response.data.folio);

					$('#field-sale-client').html(response.data.client ?? '');
					$('#field-sale-patient').html(response.data.patient ?? '');
					$('#field-sale-date').html(response.data.sale_date ?? '');
					$('#field-sale-subtotal').html(accounting.formatMoney(response.data.subtotal ?? 0));
					$('#field-sale-taxes').html(accounting.formatMoney(response.data.taxes ?? 0));
					$('#field-sale-total').html(accounting.formatMoney(response.data.total ?? 0));
					$('#field-sale-discount').html(accounting.formatMoney(response.data.discount ?? 0));
					$('#field-sale-paid').html(accounting.formatMoney(response.data.paid ?? 0));
					$('#field-sale-balance-due').html(accounting.formatMoney(response.data.balance_due ?? 0));
					$('#field-sale-observations').html(response.data.observations ?? '');
					
					$.each(response.data.details, function(k, v) {
						$('#table-sale-details tbody').append(`<tr class="transition duration-300 ease-in-out border-b hover:bg-neutral-100 dark:border-neutral-500 dark:hover:bg-neutral-600 cursor-pointer">
														<td class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent hidden">
															<span class="font-medium capitalize text-dark dark:text-title-dark text-15">${v.sale_folio}</span>
														</td>
														<td class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
															${parseInt(v.quantity)}
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

function ViewReport() {
	if(generated_report_id != '') {
		window.open(`${homeURL}/receipt/${generated_report_id}`, '_self');
	}
}