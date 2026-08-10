// JavaScript Document

var homeURL;
var selected_template = '';
var new_template = false, modify_template = false, registering_template = false, modifying_template = false;
var template = null;

function InitializeValues(home) {
	homeURL = home;
	$('#btn-plantilla-nueva').on('click', function() {
		if(!new_template && !modify_template && !registering_template && !modifying_template) {
			NewTemplate();
		}
	});
	$('#btn-guardar-cambios').on('click', UpdateTemplate);
	$('#btn-visualizar-plantilla').on('click', PreviewTemplate);
	$('#btn-plantilla-activar').on('click', StartTemplateActivation);
	SetupEditor();
	GetTemplates();
}

const Embed = Quill.import('blots/embed');

class VariableBlot extends Embed {
  static create(value) {
    const node = super.create();

	const key = value?.key || '';
    const label = value?.label || '';

    node.setAttribute('contenteditable', 'false');
    node.setAttribute('data-key', key);
    node.setAttribute('data-label', label);
    node.classList.add('ql-variable');

    node.innerText = `{{${label}}}`;

    return node;
  }

  static value(node) {
	const key = node.getAttribute('data-key');
	const label = node.getAttribute('data-label');

	if (!key || !label) return false;

	return { key, label };
  }
}

VariableBlot.blotName = 'variable';
VariableBlot.tagName = 'span';
VariableBlot.className = 'ql-variable';

Quill.register(VariableBlot);

function insertarVariable(quill, key, label) {
  if (!label) return; // <-- esto evita {{null}}

  const range = quill.getSelection(true);
  const index = range ? range.index : quill.getLength();

  quill.insertEmbed(index, 'variable', { key, label }, 'user');
  quill.insertText(index + 1, ' ', 'user');
  quill.setSelection(index + 2, 0, 'user');
}

function SetupEditor() {
	console.log($('#toolbar'));
	// const selector = document.getElementById('#editor-plantilla');
  	const selector = document.querySelector('.editor-plantilla');
	template = new Quill('#editor-plantilla', {
		modules: {
			toolbar: {
				container: '#toolbar',
				handlers: {
					'clinica': function () {
						insertarVariable(this.quill, 'clinica', 'CLINICA');
					},
					'domicilio-clinica': function () {
						insertarVariable(this.quill, 'domicilio-clinica', 'DOMICILIO DE LA CLINICA');
					},
					'telefono-clinica': function () {
						insertarVariable(this.quill, 'telefono-clinica', 'TELEFONO DE LA CLINICA');
					},
					'email-clinica': function () {
						insertarVariable(this.quill, 'email-clinica', 'EMAIL DE LA CLINICA');
					},
					'paciente-nombre': function () {
						insertarVariable(this.quill, 'paciente-nombre', 'NOMBRE DE PACIENTE');
					},
					'paciente-edad': function () {
						insertarVariable(this.quill, 'paciente-edad', 'EDAD DE PACIENTE');
					},
					'paciente-fecha-nacimiento': function () {
						insertarVariable(this.quill, 'paciente-fecha-nacimiento', 'FECHA DE NACIMIENTO DE PACIENTE');
					},
					'paciente-sexo': function () {
						insertarVariable(this.quill, 'paciente-sexo', 'SEXO DE PACIENTE');
					},
					'paciente-domicilio': function () {
						insertarVariable(this.quill, 'paciente-domicilio', 'DOMICILIO DE PACIENTE');
					},
					'paciente-telefono': function () {
						insertarVariable(this.quill, 'paciente-telefono', 'TELEFONO DE PACIENTE');
					},
					'responsable-nombre': function () {
						insertarVariable(this.quill, 'responsable-nombre', 'NOMBRE DE RESPONSABLE');
					},
					'responsable-parentesco': function () {
						insertarVariable(this.quill, 'responsable-parentesco', 'PARENTESCO DE RESPONSABLE');
					},
					'responsable-telefono': function () {
						insertarVariable(this.quill, 'responsable-telefono', 'TELEFONO DE RESPONSABLE');
					},
					'fecha': function () {
						insertarVariable(this.quill, 'fecha', 'FECHA');
					},
					'hora': function () {
						insertarVariable(this.quill, 'hora', 'HORA');
					},
					'procedimiento': function () {
						insertarVariable(this.quill, 'procedimiento', 'PROCEDIMIENTO');
					},
					'profesional': function () {
						insertarVariable(this.quill, 'profesional', 'PROFESIONAL');
					},
					'cedula-profesional': function () {
						insertarVariable(this.quill, 'cedula-profesional', 'CEDULA PROFESIONAL');
					},
					'diagnostico': function () {
						insertarVariable(this.quill, 'diagnostico', 'DIAGNOSTICO');
					},
					'observaciones': function () {
						insertarVariable(this.quill, 'observaciones', 'OBSERVACIONES');
					},
					'riesgos': function () {
						insertarVariable(this.quill, 'riesgos', 'RIESGOS');
					},
					'beneficios': function () {
						insertarVariable(this.quill, 'beneficios', 'BENEFICIOS');
					},
					'alternativas': function () {
						insertarVariable(this.quill, 'alternativas', 'ALTERNATIVAS');
					},
					'cuidados-posteriores': function () {
						insertarVariable(this.quill, 'cuidados-posteriores', 'CUIDADOS POSTERIORES');
					},
					'firma-paciente': function () {
						insertarVariable(this.quill, 'firma-paciente', 'FIRMA DE PACIENTE');
					},
					'firma-responsable': function () {
						insertarVariable(this.quill, 'firma-responsable', 'FIRMA DE RESPONSABLE');
					},
					'firma-profesional': function () {
						insertarVariable(this.quill, 'firma-profesional', 'FIRMA DE PROFESIONAL');
					},
				}
			}
		},
		placeholder: 'Diseña tu formato...',
		theme: 'snow'
	});
}

function GetTemplates() {
	$('#table-templates').find('tbody').empty();
	$.ajax({
        url: `${homeURL}/api/consent-templates`,
		type: 'get',
		data: {
			search: ''
		},
		processData: false,
		contentType: false,
		dataType: "json",
		success: function(response) {
			if(response.success) {
				$.each(response.data.templates, function(k, v) {
					$('#table-templates').find('tbody').append(`<tr id="tr-template-${v.id}" onclick="javascript:SelectTemplate('${v.id}');" class="transition duration-300 ease-in-out border-b hover:bg-neutral-100 dark:border-neutral-500 dark:hover:bg-neutral-600 cursor-pointer">
																	<td class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
																		<span class="font-medium capitalize text-dark dark:text-title-dark text-15">${v.template_name}</span>
																	</td>
																	<td class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
																		${v.version}
																	</td>
																	<td class="td-template-status px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
																		${v.status}
																	</td>
																	<td class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
																		${v.registered_by}
																	</td>
																	<td class="px-4 py-2.5 font-normal last:text-start capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
																		${v.registered_date}
																	</td>
																</tr>`);
				});
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

function SelectTemplate(id) {
	if(selected_template != id) {
		ClearTemplateData();
		selected_template = id;
		$.ajax({
			url: `${homeURL}/api/consent-templates/${id}`,
			type: 'get',
			dataType: "json",
			success: function(response) {
				console.log(response);
				if(response.success) {
					if($('#accordion-sector-datos-generales').find('button').attr('aria-expanded') === 'false')
						$('#accordion-sector-datos-generales').find('button').trigger('click');
					$('#field-codigo').val(response.data.code);
					$('#field-nombre').val(response.data.name);
					$('#field-version').val(response.data.version);
					$('#field-estatus').val(response.data.status);
					$('#field-fecha-actualizado').val(response.data.updated_date);
					$('#field-fecha-registrado').val(response.data.registered_date);
					$('#field-registro').val(response.data.registered_by);
					if(response.data.logo != null) {
						loadFilePreview(`${homeURL}/media/${response.data.logo}`, response.data.logo_name, true);
					}
					if(response.data.status_code == 'borrador')
						$('#btn-guardar-cambios').removeClass('!visible hidden');

					if(response.data.status_code == 'activo')
						$('#btn-plantilla-activar').attr('disabled', true);
					else
						$('#btn-plantilla-activar').attr('disabled', false);
					
					$('#btn-visualizar-plantilla').attr('disabled', false);
					template.setContents(JSON.parse(response.data.delta));
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
}

function EnableRegister(v) {
	$('#btn-plantilla-baja').attr('disabled', v);
	$('#btn-plantilla-activar').attr('disabled', v);
	$('#btn-plantilla-nueva').attr('disabled', v);
	$('#field-codigo').attr('disabled', !v);
	$('#field-nombre').attr('disabled', !v);
	if(v) {
		$('#sector-new-template-buttons').html(`<button id="btn-cancelar-plantilla" type="button" class="flex items-center px-[20px] text-sm text-white rounded-md font-semibold bg-danger border-danger hover:bg-danger-hbr h-10 gap-[6px] transition-[0.3s]">
													<i class="uil uil-trash-alt"></i>
													<span class="m-0">Cancelar</span>
												</button>
												<button id="btn-registrar-plantilla" type="submit" class="flex items-center px-[20px] text-sm text-white rounded-md font-semibold bg-primary border-primary hover:bg-primary-hbr h-10 gap-[6px] transition-[0.3s]">
													<i class="uil uil-plus"></i>
													<span class="m-0">Guardar Plantilla</span>
												</button>`);
		$('#btn-cancelar-plantilla').on('click', CancelTemplateRegister);
	} else {
		$('#sector-new-template-buttons').html(``);
	}
}

function RegisterTemplate() {
    var formElement = $('#form-register-template')[0]; 
    var formData = new FormData(formElement);

	$.ajax({
        url: `${homeURL}/api/consent-templates`,
		type: 'post',
		data: formData,
		processData: false,
		contentType: false,
		dataType: "json",
		success: function(response) {
			if(response.success) {
				ShowToastMessage('Plantilla registrada con exito', 'success');
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

function CancelTemplateRegister() {
	if(new_template) {
		if(registering_template) {
			ShowToastMessage('Se esta registrando una plantilla, no es posible cancelar.', 'error');
			return;
		}
		new_template = false;
		ClearTemplateData();
		EnableRegister(false);
		if($('#accordion-sector-datos-generales').find('button').attr('aria-expanded') === 'true')
			$('#accordion-sector-datos-generales').find('button').trigger('click');
	}
}

function ClearTemplateData() {
	$('#field-codigo').val('');
	$('#field-nombre').val('');
	$('#field-version').val('');
	$('#field-estatus').val('');
	$('#field-fecha-registrado').val('');
	$('#field-fecha-actualizado').val('');
	$('#field-registro').val('');
	$('#file-logo').val('');
	$('#file-logo').trigger('change');
	template.setContents(JSON.parse('[]'));
	$('#btn-visualizar-plantilla').attr('disabled', true);
	if(!$('#btn-guardar-cambios').hasClass('hidden'))
		$('#btn-guardar-cambios').addClass('!visible hidden');
}

function NewTemplate() {
	new_template = true;
	ClearTemplateData();
	EnableRegister(true);
	if($('#accordion-sector-datos-generales').find('button').attr('aria-expanded') === 'false')
		$('#accordion-sector-datos-generales').find('button').trigger('click');
	$('#field-codigo').focus();
	
}

function UpdateTemplate() {
	if(selected_template != '') {
		var formElement = $('#form-register-template')[0]; 
		var formData = new FormData(formElement);
		
		const html = template.root.innerHTML;
		const delta = template.getContents();
    	const logo = document.getElementById('file-logo').files[0] || null;
		
		if (logo) {
			formData.append('file-logo', logo);
		}
		formData.append('template_html', html);
		formData.append('template_delta', JSON.stringify(delta));
		formData.append('_method', 'PUT');

		if (logo) {
			formData.append('file-logo-1', logo);
		}

		$.ajax({
			url: `${homeURL}/api/consent-templates/${selected_template}`,
			type: 'post',
			data: formData,
			processData: false,
			contentType: false,
			dataType: "json",
			success: function(response) {
				if(response.success) {
					ShowToastMessage('Plantilla registrada con exito', 'success');
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
}

async function loadFilePreview(filepath, filename, loadPreview = false) {
	const res = await fetch(filepath);
	const blob = await res.blob();

	const file = new File([blob], filename, { type: blob.type });
	const dt = new DataTransfer();
	dt.items.add(file);

	const input = document.getElementById('file-logo');

	input.files = dt.files;
    input.dispatchEvent(new Event('change', { bubbles: true }));

	if(loadPreview)
		PreviewTemplate();
}

function PreviewTemplate() {
	const delta = template.getContents();
    const form = document.getElementById('form-preview-template');

    $('#preview-template').val(template.root.innerHTML);
    $('#preview-delta').val(JSON.stringify(delta));

    form.action = `${homeURL}/api/consent-templates/preview`;
    form.submit();
}

function StartTemplateActivation() {
	if(selected_template != '') {
		$.ajax({
			url: `${homeURL}/api/consent-templates/${selected_template}/status`,
			type: 'get',
			dataType: "json",
			success: function(response) {
				if(response.success) {
					if(response.data.code == 'borrador') {
						ShowSweetAlertConfirmCancelCallback('info', 'Activar Plantilla', 'Cuando la plantilla cambie de borrador a activa no será posible volver hacer cambios.', 'Activar', 'Cancelar', (r) => { if(r.isConfirmed) ActivateTemplate(response.data.uuid) });
					} else if(response.data.code == 'inactivo') {
						ActivateTemplate(response.data.uuid);
					}
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
}

function ActivateTemplate(id) {
	var formData = new FormData();
	formData.append('id', id);

	$.ajax({
		url: `${homeURL}/api/consent-templates/${selected_template}/activate`,
		type: 'put',
		data: formData,
		processData: false,
		contentType: false,
		dataType: "json",
		success: function(response) {
			if(response.success) {
				const statuses = document.querySelectorAll('.td-template-status');

				statuses.forEach(status => {
					if (status.textContent.trim().toLowerCase() !== 'borrador') {
						status.textContent = 'Inactivo';
					}
				});

				const template_row = document.getElementById(`tr-template-${response.data.uuid}`);

				if (template_row) {
					const status = template_row.querySelector('.td-template-status');

					if (status) {
						status.textContent = 'Activo';
					}
				}
				
				if(!$('#btn-guardar-cambios').hasClass('hidden'))
					$('#btn-guardar-cambios').addClass('!visible hidden');
				
				$('#btn-plantilla-activar').attr('disabled', true);

				ShowToastMessage('Plantilla activada con exito', 'success');
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