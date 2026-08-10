// JavaScript Document

var homeURL;
var defaultCountry = 118;
var defaultState = 19;
var loadingCountries = loadingStates = loadingMunicipalities = loadingLocalities = false;

var dobDatePicker;

function InitializeValues(home) {
	homeURL = home;
    dobDatePicker = initDatePicker('field-fecha-nacimiento');
    GetGenders();
    GetRegimenes();
    GetCountries();
    $('#select-pais').on('change', GetStates);
    $('#select-estado').on('change', GetMunicipalities);
    $('#select-municipio').on('change', GetLocalities);
    $('#select-facturacion-pais').on('change', GetStates);
    $('#select-facturacion-estado').on('change', GetMunicipalities);
    $('#select-facturacion-municipio').on('change', GetLocalities);
    $('.billing-data').hide();
    $('#chk-agregar-facturacion').on('change', ShowBilling);
}

function ShowBilling() {
    if($('#chk-agregar-facturacion').is(':checked'))
        $('.billing-data').show();
    else
        $('.billing-data').hide();
}

function GetRegimenes() {
	try {
		$.ajax({
				url: `${homeURL}/api/billing-regimenes`,
				type: 'get',
                dataType: "json",
				success: function(response) {
					$.each(response.data.regimenes, function(k, v) {
                        $('#select-facturacion-regimen').append($('<option>', {
                            value: v.id,
                            text: `${v.codigo_sat} - ${v.regimen}`
                        }));
                    });
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) { 
					console.log('STATUS:', textStatus);
					console.log('ERROR:', errorThrown);
					console.log('RESPONSE TEXT:', XMLHttpRequest.responseText);

					alert(XMLHttpRequest.responseText);
				}  
		});
	} catch(E) {
		alert(E.message);
	}
}

function GetGenders() {
	try {
		$.ajax({
				url: `${homeURL}/api/genders`,
				type: 'get',
                dataType: "json",
				success: function(response) {
					$.each(response.data.genders, function(k, v) {
                        $('#select-genero').append($('<option>', {
                            value: v.id,
                            text: v.genero
                        }));
                    });
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) { 
					console.log('STATUS:', textStatus);
					console.log('ERROR:', errorThrown);
					console.log('RESPONSE TEXT:', XMLHttpRequest.responseText);

					alert(XMLHttpRequest.responseText);
				}  
		});
	} catch(E) {
		alert(E.message);
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
                    $('#select-pais').append($('<option>', {
                        value: v.id,
                        text: v.pais
                    }));

                    $('#select-facturacion-pais').append($('<option>', {
                        value: v.id,
                        text: v.pais
                    }));
                });
                loadingCountries = false;
                $('#select-pais').val(defaultCountry);
        	    refreshSelectOption('select-pais');
                $('#select-pais').trigger('change');
                $('#select-facturacion-pais').val(defaultCountry);
        	    refreshSelectOption('select-facturacion-pais');
                $('#select-facturacion-pais').trigger('change');
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) { 
                console.log('STATUS:', textStatus);
                console.log('ERROR:', errorThrown);
                console.log('RESPONSE TEXT:', XMLHttpRequest.responseText);

                alert(XMLHttpRequest.responseText);
                loadingCountries = false;
            }  
    });
}

function GetStates(object) {
    if(!loadingCountries) {
        var country_select = object.target.id;
        var state_select = null;
        if(country_select == 'select-pais')
            state_select = 'select-estado';
        else if(country_select == 'select-facturacion-pais')
            state_select = 'select-facturacion-estado';
        else
            return;
        $(`#${state_select}`).empty();
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
                        $(`#${state_select}`).append($('<option>', {
                            value: v.id,
                            text: v.estado
                        }));
                    });
                    loadingStates = false;
                    $(`#${state_select}`).val(defaultState);
        	        refreshSelectOption(state_select);
                    $(`#${state_select}`).trigger('change');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    console.log('STATUS:', textStatus);
                    console.log('ERROR:', errorThrown);
                    console.log('RESPONSE TEXT:', XMLHttpRequest.responseText);

                    alert(XMLHttpRequest.responseText);
                    loadingStates = false;
                }  
        });
    }
}

function GetMunicipalities(object) {
    if(!loadingStates) {
        var state_select = object.target.id;
        var municipality_select = null;
        if(state_select == 'select-estado')
            municipality_select = 'select-municipio';
        else if(state_select == 'select-facturacion-estado')
            municipality_select = 'select-facturacion-municipio';
        else
            return;
        $(`#${municipality_select}`).empty();
        loadingMunicipalities = true;
        $.ajax({
                url: `${homeURL}/api/locations/municipalities`,
                type: 'get',
                data: {
                    id: $(`#${state_select}`).val()
                },
                dataType: "json",
                success: function(response) {
                    $.each(response.data.municipalities, function(k, v) {
                        $(`#${municipality_select}`).append($('<option>', {
                            value: v.id,
                            text: v.municipio
                        }));
                    });
                    loadingMunicipalities = false;
        	        refreshSelectOption(municipality_select);
                    $(`#${municipality_select}`).trigger('change');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    console.log('STATUS:', textStatus);
                    console.log('ERROR:', errorThrown);
                    console.log('RESPONSE TEXT:', XMLHttpRequest.responseText);

                    alert(XMLHttpRequest.responseText);
                    loadingMunicipalities = false;
                }  
        });
    }
}

function GetLocalities(object) {
    if(!loadingMunicipalities) {
        var municipality_select = object.target.id;
        var locality_select = null;
        if(municipality_select == 'select-municipio')
            locality_select = 'select-colonia';
        else if(municipality_select == 'select-facturacion-municipio')
            locality_select = 'select-facturacion-colonia';
        else
            return;
        $(`#${locality_select}`).empty();
        loadingLocalities = true;
        $.ajax({
                url: `${homeURL}/api/locations/localities`,
                type: 'get',
                data: {
                    id: $(`#${municipality_select}`).val()
                },
                dataType: "json",
                success: function(response) {
                    $.each(response.data.localities, function(k, v) {
                        $(`#${locality_select}`).append($('<option>', {
                            value: v.id,
                            text: v.colonia
                        }));
                    });
                    loadingLocalities = false;
        	        refreshSelectOption(locality_select);
                    $(`#${locality_select}`).trigger('change');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    console.log('STATUS:', textStatus);
                    console.log('ERROR:', errorThrown);
                    console.log('RESPONSE TEXT:', XMLHttpRequest.responseText);

                    alert(XMLHttpRequest.responseText);
                    loadingLocalities = false;
                }  
        });
    }
}

function RegisterPatient() {
    var formElement = $('#form-patient-add')[0]; 
    var formData = new FormData(formElement);

    $.ajax({
        url: `${homeURL}/api/patients`,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function(response) {
            console.log('Success!', response);
            ShowSweetAlertConfirmCallback('success', 'Paciente Registrado', '', 'Entendido', (result) => {
                if(result.isConfirmed) {
                    if(callbackRequest == 'schedule')
                        window.location.href = `${homeURL}/appointments/add?pid=${response.data.puid}`
                    else
                        window.location.href = `${homeURL}/patients`
                }
            });
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
            console.log('STATUS:', textStatus);
            console.log('ERROR:', errorThrown);
            console.log('RESPONSE TEXT:', XMLHttpRequest.responseText);

            alert(XMLHttpRequest.responseText);
        } 
    });
}