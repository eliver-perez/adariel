// JavaScript Document

var homeURL;

function InitializeValues(home) {
	homeURL = home;
	$('#btn-registrar-personal').on('click', function() {
		window.location.href = `${homeURL}/staff/add`;
	});
	GetStaff();
}

function GetStaff() {
	$.ajax({
        url: `${homeURL}/api/staff`,
		type: 'get',
		data: {
			search: ''
		},
		processData: false,
		contentType: false,
		dataType: "json",
		success: function(response) {
			var rows = '';
			console.log(response);
			if(response.success) {
				$.each(response.data.staff, function(k, v) {
					rows += `<tr class="transition duration-300 ease-in-out border-b hover:bg-neutral-100 dark:border-neutral-500 dark:hover:bg-neutral-600 cursor-pointer">
								<td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
									<span class="font-medium capitalize text-dark dark:text-title-dark text-15">${v.name}</span>
								</td>
								<td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
									${v.address}
								</td>
								<td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
									${v.role}
								</td>
								<td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
									${v.dob}
								</td>
								<td class="px-4 py-2.5 font-normal last:text-end capitalize text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent">
									${v.phone}
								</td>
								<td class="ps-4 pe-4 py-2.5 font-normal last:text-end text-[14px] text-dark dark:text-title-dark border-none group-hover:bg-transparent rounded-e-[4px]">
									<span class="${v.status != '' ? 'bg-primary' : 'bg-danger/10'} font-medium inline px-[11.85px] py-[4.5px] rounded-[15px] text-[13px] text-white">${v.status}</span>
								</td>
							</tr>`;
				});
				$('#table-staff').append(rows);
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