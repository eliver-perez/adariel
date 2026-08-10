// JavaScript Document

var homeURL;
var defaultCountry = 118;
var defaultState = 19;
var loadingCountries = loadingStates = loadingMunicipalities = loadingLocalities = false;

var dobDatePicker;

function InitializeValues(home) {
	homeURL = home;
    dobDatePicker = initDatePicker('field-fecha-nacimiento');
    GetUsers();
    GetStaffRoles();
    GetGenders();
    GetSpecialties();
    GetCountries();
    $('#select-pais').on('change', GetStates);
    $('#select-universidad-pais').on('change', GetStates);
    $('#select-estado').on('change', GetMunicipalities);
    $('#select-universidad-estado').on('change', GetMunicipalities);
    $('#select-municipio').on('change', GetLocalities);
}

var registrando = false;

function GetUsers() {
	try {
		$.ajax({
				url: `${homeURL}/api/my-users`,
				type: 'get',
                data: {
                    active: 1,
                },
                dataType: "json",
				success: function(response) {
                    console.log(response)
					$.each(response.data.users, function(k, v) {
                        $('#select-usuario').append($('<option>', {
                            value: v.id,
                            text: v.name
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

function GetStaffRoles() {
	try {
		$.ajax({
				url: `${homeURL}/api/roles`,
				type: 'get',
                dataType: "json",
				success: function(response) {
					$.each(response.data.roles, function(k, v) {
                        $('#select-puesto').append($('<option>', {
                            value: v.id,
                            text: v.puesto
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

function GetSpecialties() {
	try {
		$.ajax({
				url: `${homeURL}/api/specialties`,
				type: 'get',
                dataType: "json",
				success: function(response) {
					$.each(response.data.specialties, function(k, v) {
                        $('#select-especialidad').append($('<option>', {
                            value: v.id,
                            text: v.especialidad
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
                console.log(response);
                $.each(response.data.countries, function(k, v) {
                    $('#select-pais').append($('<option>', {
                        value: v.id,
                        text: v.pais
                    }));

                    $('#select-universidad-pais').append($('<option>', {
                        value: v.id,
                        text: v.pais
                    }));
                });
                loadingCountries = false;
                $('#select-pais').val(defaultCountry);
                refreshSelectOption('select-pais');
                $('#select-universidad-pais').val(defaultCountry);
                refreshSelectOption('select-universidad-pais');
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
        else if(country_select == 'select-universidad-pais')
            state_select = 'select-universidad-estado';
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
        else if(state_select == 'select-universidad-estado')
            municipality_select = 'select-universidad-municipio';
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

function GetLocalities() {
    if(!loadingMunicipalities) {
        $(`#select-colonia`).empty();
        loadingLocalities = true;
        $.ajax({
                url: `${homeURL}/api/locations/localities`,
                type: 'get',
                data: {
                    id: $(`#select-municipio`).val()
                },
                dataType: "json",
                success: function(response) {
                    $.each(response.data.localities, function(k, v) {
                        $(`#select-colonia`).append($('<option>', {
                            value: v.id,
                            text: v.colonia
                        }));
                    });
                    refreshSelectOption('select-colonia');
                    loadingLocalities = false;
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

function RegisterStaff() {
    var formElement = $('#form-staff-add')[0]; 
    var formData = new FormData(formElement);

    $.ajax({
        url: `${homeURL}/api/staff`,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function(response) {
            if(response.success) {
                ShowToastMessage(response.message, 'success');
                ShowSweetAlertConfirmCallback('info', 'Registrado', 'Personal registrado con éxito.', 'Entendido', () => {
                    window.open(`${homeURL}/staff`, '_self');
                })
            } else {
                ShowToastMessage(response.message, 'error');
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
            console.log('STATUS:', textStatus);
            console.log('ERROR:', errorThrown);
            console.log('RESPONSE TEXT:', XMLHttpRequest.responseText);

            alert(XMLHttpRequest.responseText);
        } 
    });
}