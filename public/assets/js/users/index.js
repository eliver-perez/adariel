// JavaScript Document

var homeURL;

function InitializeValues(home) {
	homeURL = home;
	$('#btn-registrar-usuario').on('click', function() {
		window.location.href = `${homeURL}/users/add`;
	});
	GetUsers();
}

function GetUsers() {
	$.ajax({
        url: `${homeURL}/api/users`,
		type: 'get',
		data: {
			search: ''
		},
		dataType: "json",
		success: function(response) {
			var rows = '';
			console.log(response);
			$.each(response.data.users, function(k, v) {
				rows += `<tr class="transition duration-300 ease-in-out border-b hover:bg-neutral-100 dark:border-neutral-500 dark:hover:bg-neutral-600 cursor-pointer">
                            <td class="px-4 py-2.5 font-normal last:text-end text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                <span class="font-medium text-dark dark:text-title-dark text-15">${v.email}</span>
                            </td>
                            <td class="px-4 py-2.5 font-normal last:text-end text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
								${v.name}
							</td>
                            <td class="px-4 py-2.5 font-normal last:text-end text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
								${v.organization}
							</td>
                            <td class="px-4 py-2.5 font-normal last:text-end text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
								${v.type}
							</td>
                            <td class="px-4 py-2.5 font-normal last:text-end text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                ${v.registered_date}
							</td>
                            <td class="px-4 py-2.5 font-normal last:text-end text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
                                ${v.last_active_date}
							</td>
                            <td class="ps-4 pe-4 py-2.5 font-normal last:text-end text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent rounded-e-[4px]">
                                <span class="${v.active == 1 ? 'bg-primary' : 'bg-danger'} font-medium inline px-[11.85px] py-[4.5px] rounded-[15px] text-[13px] text-white">${v.active == 1 ? 'Si' : 'No'}</span>
                            </td>
                        </tr>`;
			});
			$('#table-users').append(rows);
		},
		error: function(XMLHttpRequest, textStatus, errorThrown) { 
			console.log('STATUS:', textStatus);
			console.log('ERROR:', errorThrown);
			console.log('RESPONSE TEXT:', XMLHttpRequest.responseText);

			alert(XMLHttpRequest.responseText);
		}  
	});
}