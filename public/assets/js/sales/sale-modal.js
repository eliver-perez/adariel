let saleModal = null;

function InitializeSaleModal() {
    saleModal = document.getElementById('modal-sale');
	$('#modal-sale').on('hidden.te.modal', function () {
		generated_report_id = '';
	});
	$('#btn-download-sale-report').on('click', DownloadSaleReport);
}

var generated_report_id = '';

function SaleReport(id) {
	selected_cash_reconciliation_report = id;
	const modal = new te.Modal(saleModal);
	
	generated_report_id = id;

	modal.show();
	
    const form = document.getElementById('form-preview-sale');
    form.action = `${homeURL}/api/sales/${id}/preview`;
    form.submit();
}

function DownloadSaleReport(id) {
	if (!generated_report_id) {
        return;
    }

    document.getElementById('frame-sale-preview').src = 'about:blank';

    const url =
        `${homeURL}/api/sales/${generated_report_id}/preview?download=1`;
	console.log(url);

    const link = document.createElement('a');

    link.href = url;
    link.style.display = 'none';

    document.body.appendChild(link);
    link.click();
    link.remove();
}