// JavaScript Document

var homeURL;

var selected_sale = '';
var loading_status = true;
var searchTimer = null, searchValue = '';

function InitializeValues(home) {
	homeURL = home;
	$('#select-users-types').on('change', GetUserTypeRoles);
	$('#select-users').on('change', GetUserRoles);
	$('#btn-users-types-permissions-add').on('click', AddUserTypeRole);
	$('#btn-users-permissions-add').on('click', AddUserRole);
	GetRoles();
	GetUsers();
	GetUsersTypes();
}

function GetRoles() {
	$.ajax({
        url: `${homeURL}/api/users/roles`,
		type: 'get',
		dataType: "json",
		success: function(response) {
			console.log(response);
			$.each(response.data.roles, function(k, v) {
				$('#select-users-permissions').append($('<option>', {
					value: v.id,
					text: `${v.role}`
				}));
				$('#select-users-types-permissions').append($('<option>', {
					value: v.id,
					text: `${v.role}`
				}));
			});
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

function GetUsers() {
	$.ajax({
        url: `${homeURL}/api/users`,
		type: 'get',
		data: {
			limit: 10000000,
			offset: 0,
		},
		dataType: "json",
		success: function(response) {
			console.log(response);
			$.each(response.data.users, function(k, v) {
				$('#select-users').append($('<option>', {
					value: v.id,
					text: `${v.name} (${v.type})`
				}));
			});
        	refreshSelectOption('select-users');
			
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

function GetUsersTypes() {
	$.ajax({
        url: `${homeURL}/api/users/types`,
		type: 'get',
		dataType: "json",
		success: function(response) {
			console.log(response);
			$.each(response.data.users_types, function(k, v) {
				$('#select-users-types').append($('<option>', {
					value: v.id,
					text: `${v.type}`
				}));
			});
        	refreshSelectOption('select-users-types');
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

function GetUserTypeRoles() {
	$('#table-user-type-permissions tbody').empty();
	$.ajax({
        url: `${homeURL}/api/users/types/${$('#select-users-types').val()}/roles`,
		type: 'get',
		dataType: "json",
		success: function(response) {
			console.log(response);
			if(response.success) {
				$.each(response.data.permissions, function(k, v) {
					$('#table-user-type-permissions tbody').append(`<tr class="transition duration-300 ease-in-out border-b hover:bg-neutral-100 dark:border-neutral-500 dark:hover:bg-neutral-600 cursor-pointer">
													<td class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
														<span class="font-medium capitalize text-dark dark:text-title-dark text-15">${v.role}</span>
													</td>
													<td class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
														${v.update_date}
													</td>
													<td class="ps-4 pe-0 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent rounded-e-[4px]">
														<div class="text-danger dark:text-subtitle-dark text-[19px] flex px-2.5 justify-start m-0 gap-[5px]">
															<button type="button" class="uil uil-trash-alt hover:text-danger cursor-pointer" title="Remover Permiso" onclick="RemoveUserTypeRole('${v.id}');"></button>
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

function GetUserRoles() {
	$('#table-user-permissions tbody').empty();
	$.ajax({
        url: `${homeURL}/api/users/${$('#select-users').val()}/roles`,
		type: 'get',
		dataType: "json",
		success: function(response) {
			console.log(response);
			if(response.success) {
				$.each(response.data.permissions, function(k, v) {
					$('#table-user-permissions tbody').append(`<tr class="transition duration-300 ease-in-out border-b hover:bg-neutral-100 dark:border-neutral-500 dark:hover:bg-neutral-600 cursor-pointer">
													<td class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
														<span class="font-medium capitalize text-dark dark:text-title-dark text-15">${v.role}</span>
													</td>
													<td class="px-4 py-2.5 font-normal capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
														${v.update_date}
													</td>
													<td class="ps-4 pe-0 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent rounded-e-[4px]">
														<div class="text-danger dark:text-subtitle-dark text-[19px] flex px-2.5 justify-start m-0 gap-[5px]">
															<button type="button" class="uil uil-trash-alt hover:text-danger cursor-pointer" title="Remover Permiso" onclick="RemoveUserRole('${v.id}');"></button>
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

function AddUserTypeRole() {
	$.ajax({
        url: `${homeURL}/api/user-types/${$('#select-users-types').val()}/permissions`,
		type: 'post',
		data: {
			permission: $('#select-users-types-permissions').val()
		},
		dataType: "json",
		success: function(response) {
			console.log(response);
			if(response.success) {
				ShowToastMessage('Permiso asignado con éxito.', 'success');
				GetUserTypeRoles();
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

function AddUserRole() {
	$.ajax({
        url: `${homeURL}/api/users/${$('#select-users').val()}/permissions`,
		type: 'post',
		data: {
			permission: $('#select-users-permissions').val()
		},
		dataType: "json",
		success: function(response) {
			console.log(response);
			if(response.success) {
				ShowToastMessage('Permiso asignado con éxito.', 'success');
				GetUserRoles();
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