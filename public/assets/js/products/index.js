// JavaScript Document

var homeURL;
var new_product = modify_product = registering_product = modifying_product = false;
var selected_product = '';
var base_cost = 0;
var tax_rate = 0;
var taxes = 0;
var total_cost = 0;

function InitializeValues(home) {
	homeURL = home;
	$('#btn-nuevo-producto').on('click', NewProduct);
	$('#btn-cancelar-producto').on('click', CancelProduct);
	GetProducts();
	GetCategories();
	GetUnitsMeasure();
	$('#field-producto-precio-base').on('keyup', () => {
		if($('#field-producto-precio-base').val() != '')
			base_cost = parseFloat($('#field-producto-precio-base').val());
		else
			base_cost = 0;
		CalculateTotal();
	});
	$('#field-producto-porcentaje-impuesto').on('keyup', () => {
		if($('#field-producto-porcentaje-impuesto').val() != '')
			tax_rate = parseFloat($('#field-producto-porcentaje-impuesto').val());
		else
			tax_rate = 0;
		CalculateTotal();
	});
}

function CalculateTotal() {
	taxes = parseFloat(base_cost * (tax_rate / 100)).toFixed(2)
	total_cost = parseFloat(base_cost) + parseFloat(taxes);
	$('#field-producto-precio').val(accounting.formatMoney(total_cost));
}

function NewProduct() {
	if(!new_product && !modify_product && !registering_product && !modifying_product) {
		EnableProduct(false);
		new_product = true;
		$('#btn-nuevo-producto').prop('disabled', true);
		$('#btn-modificar-producto').prop('disabled', true);
		$('#btn-registrar-producto').prop('disabled', false);
		$('#btn-cancelar-producto').prop('disabled', false);
		$('#btn-nuevo-producto').addClass('!visible hidden');
		$('#btn-modificar-producto').addClass('!visible hidden');
		$('#btn-registrar-producto').removeClass('!visible hidden');
		$('#btn-cancelar-producto').removeClass('!visible hidden');
	}
}

function EnableProduct(v) {
	$('#field-producto-clave').prop('disabled', v);
	$('#field-producto-codigo-barras').prop('disabled', v);
	$('#select-producto-categoria').prop('disabled', v);
	$('#field-producto').prop('disabled', v);
	$('#field-producto-descripcion').prop('disabled', v);
	$('#select-producto-unidad').prop('disabled', v);
	$('#field-producto-precio-base').prop('disabled', v);
	$('#field-producto-precio-base').val('0');
	$('#field-producto-porcentaje-impuesto').prop('disabled', v);
	$('#field-producto-porcentaje-impuesto').val('16');
	$('#field-producto-precio').val(accounting.formatMoney(0));
	$('#chk-producto-habilitado-venta').prop('disabled', v);
	$('#chk-producto-habilitado-venta').prop('checked', true);
	base_cost = 0;
	tax_rate = 16;
	taxes = 0;
	total_cost = 0;
}

function CancelProduct() {
	if(registering_product || modifying_product)
		return;
	if(new_product || modify_product) {
		EnableProduct(true);
		ClearProduct();
		new_product = false;
		modify_product = false;
		$('#btn-nuevo-producto').prop('disabled', false);
		if(selected_product != '') {
			$('#btn-modificar-producto').prop('disabled', false);
			$('#btn-modificar-producto').removeClass('!visible hidden');
		}
		$('#btn-registrar-producto').prop('disabled', true);
		$('#btn-cancelar-producto').prop('disabled', true);
		$('#btn-nuevo-producto').removeClass('!visible hidden');
		$('#btn-registrar-producto').addClass('!visible hidden');
		$('#btn-cancelar-producto').addClass('!visible hidden');
	}
}

function ClearProduct() {
	$('#field-producto-clave').val('');
	$('#field-producto-codigo-barras').val('');
	$('#field-producto').val('');
	$('#field-producto-descripcion').val('');
	$('#field-producto-precio-base').val('');
	$('#field-producto-porcentaje-impuesto').val('');
	$('#field-producto-porcentaje-impuesto').val('16');
	$('#chk-producto-habilitado-venta').prop('checked', false);
}

function GetCategories() {
	$('#select-producto-categoria').empty();
	$.ajax({
        url: `${homeURL}/api/products/categories`,
		type: 'get',
		processData: false,
		contentType: false,
		dataType: "json",
		success: function(response) {
			console.log(response);
			$.each(response.data.categories, function(k, v) {
				$('#select-producto-categoria').append($('<option>', {
					value: v.id,
					text: v.category
				}));
			});
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) { 
			console.log(XMLHttpRequest.responseText);
			let response = JSON.parse(XMLHttpRequest.responseText);
			ShowToastMessage(response.message, 'error');
		}  
	});
}

function GetUnitsMeasure() {
	$('#select-producto-unidad').empty();
	$.ajax({
        url: `${homeURL}/api/units-measure`,
		type: 'get',
		data: {
			search: ''
		},
		processData: false,
		contentType: false,
		dataType: "json",
		success: function(response) {
			console.log(response);
			$.each(response.data.units_measure, function(k, v) {
				$('#select-producto-unidad').append($('<option>', {
					value: v.id,
					text: v.unidad,
					'data-code': v.codigo
				}));
			});
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) { 
			let response = JSON.parse(XMLHttpRequest.responseText);
			ShowToastMessage(response.message, 'error')
		}  
	});
}

function GetProducts() {
	$('#table-products tbody').empty();
	$.ajax({
        url: `${homeURL}/api/products`,
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
			$.each(response.data.products, function(k, v) {
				rows += `<tr onclick="SelectProduct('${v.id}')" class="transition duration-300 ease-in-out border-b hover:bg-neutral-100 dark:border-neutral-500 dark:hover:bg-neutral-600 cursor-pointer">
                            <td class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                <span class="font-medium capitalize text-dark dark:text-title-dark text-15">${v.code}</span>
                            </td>
                            <td class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
								${v.product}
							</td>
                            <td class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
								${v.category}
							</td>
                            <td class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                ${v.unit_measure}
							</td>
                            <td class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                ${accounting.formatMoney(v.total_cost)}
							</td>
                            <td class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                ${v.enabled_sale == 1 ? 'Si' : 'No'}
							</td>
                        </tr>`;
			});
			$('#table-products').append(rows);
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) { 
			let response = JSON.parse(XMLHttpRequest.responseText);
			ShowToastMessage(response.message, 'error')
		}  
	});
}

function SelectProduct(id) {
	ClearProduct();
	$.ajax({
        url: `${homeURL}/api/products/${id}`,
		type: 'get',
		dataType: "json",
		success: function(response) {
			console.log(response);
			if(response.success) {
				$('#field-producto-clave').val(response.data.code);
				$('#field-producto-codigo-barras').val(response.data.bar_code);
				$('#select-producto-categoria').val(response.data.category);
				refreshSelectOption('select-producto-categoria');
				$('#field-producto').val(response.data.name);
				$('#field-producto-descripcion').val(response.data.description);
				$('#select-producto-unidad').val(response.data.unit);
				refreshSelectOption('select-producto-unidad');
				$('#field-producto-precio-base').val(accounting.formatMoney(response.data.base_cost, ''));
				$('#field-producto-porcentaje-impuesto').val(response.data.tax_rate);
				$('#field-producto-precio').val(accounting.formatMoney(response.data.total_cost, ''));
				$('#chk-producto-habilitado-venta').prop('checked', response.data.sale_enabled == 1 ? true : false);
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

function RegisterProduct() {
	$.ajax({
        url: `${homeURL}/api/products`,
		type: 'post',
		data: {
			code: $('#field-producto-clave').val(),
			bar_code: $('#field-producto-codigo-barras').val(),
			category: $('#select-producto-categoria').val(),
			product: $('#field-producto').val(),
			description: $('#field-producto-descripcion').val(),
			unit_measure: $('#select-producto-unidad').val(),
			base_cost: base_cost,
			tax_rate: tax_rate,
			taxes: taxes,
			total_cost: total_cost,
			enable_sale: $('#chk-producto-habilitado-venta').is(':checked') ? 1 : 0
		},
		dataType: "json",
		success: function(response) {
			if(response.success) {
				ShowToastMessage('Producto registrado con éxito.', 'success');
				CancelProduct();
			}
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) { 
			console.log(XMLHttpRequest.responseText);
			let response = JSON.parse(XMLHttpRequest.responseText);
			ShowToastMessage(response.message, 'error')
		}  
	});
}