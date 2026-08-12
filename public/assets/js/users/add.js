// JavaScript Document

var homeURL;
var selected_organization = '';

function InitializeValues(home) {
	homeURL = home;
    GetUserTypes();
    GetOrganizations();
    $('#select-organization').on('change', () => {
        selected_organization = '';
        if(selected_organization != 'N/A')
            selected_organization = $('#select-organization').val();
        GetBranches(selected_organization);
    });
}

function ShowBilling() {
    if($('#chk-agregar-facturacion').is(':checked'))
        $('.billing-data').show();
    else
        $('.billing-data').hide();
}

function GetOrganizations() {
	try {
		$.ajax({
				url: `${homeURL}/api/organizations`,
				type: 'get',
                dataType: "json",
				success: function(response) {
                    $('#select-organization').append($('<option>', {
                        value: 'N/A',
                        text: 'Sin Empresa'
                    }));
					$.each(response.data.organizations, function(k, v) {
                        $('#select-organization').append($('<option>', {
                            value: v.id,
                            text: v.organization
                        }));
                    });
                    $('#select-organization').val('');
                    refreshSelectOption('select-organization');
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    try {
                        var response = JSON.parse(XMLHttpRequest.responseText);
                        ShowToastMessage(response.message, 'error');
                        
                    } catch (e) {
                        ShowToastMessage(XMLHttpRequest.responseText, 'error');
                    }
				}  
		});
	} catch(E) {
		ShowToastMessage(E.message, 'error');
	}
}

function GetBranches(id) {
	try {
        $('#select-branch').empty();
        if(id != '' && id != 'N/A') {
            $.ajax({
                    url: `${homeURL}/api/organizations/${id}/branches`,
                    type: 'get',
                    dataType: "json",
                    success: function(response) {
                        $.each(response.data.branches, function(k, v) {
                            $('#select-branch').append($('<option>', {
                                value: v.id,
                                text: v.branch
                            }));
                        });
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) { 
                        try {
                            var response = JSON.parse(XMLHttpRequest.responseText);
                            ShowToastMessage(response.message, 'error');
                            
                        } catch (e) {
                            ShowToastMessage(XMLHttpRequest.responseText, 'error');
                        }
                    }  
            });
        }
	} catch(E) {
		ShowToastMessage(E.message, 'error');
	}
}

function GetUserTypes() {
	try {
		$.ajax({
				url: `${homeURL}/api/users-types`,
				type: 'get',
                dataType: "json",
				success: function(response) {
					$.each(response.data.users_types, function(k, v) {
                        $('#select-user-type').append($('<option>', {
                            value: v.id,
                            text: v.type,
                            'data-code': v.code,
                        }));
                    });
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) { 
                    try {
                        var response = JSON.parse(XMLHttpRequest.responseText);
                        ShowToastMessage(response.message, 'error');
                        
                    } catch (e) {
                        ShowToastMessage(XMLHttpRequest.responseText, 'error');
                    }
				}  
		});
	} catch(E) {
		ShowToastMessage(E.message, 'error');
	}
}

function RegisterUser() {
    var formElement = $('#form-user-add')[0]; 
    var formData = new FormData(formElement);

    $.ajax({
        url: `${homeURL}/api/users`,
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        dataType: "json",
        success: function(response) {
            console.log(response);
            if(response.success) {
                ShowSweetAlertConfirmCallback('success', response.message, '', 'Entendido', (result) => {
                    window.location.href = `${homeURL}/users`
                });
            } else {
                ShowToastMessage(response.message, 'error');
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) { 
            console.log(XMLHttpRequest);
            try {
                var response = JSON.parse(XMLHttpRequest.responseText);
                ShowToastMessage(response.message, 'error');
                
            } catch (e) {
                ShowToastMessage(XMLHttpRequest.responseText, 'error');
            }
        } 
    });
}