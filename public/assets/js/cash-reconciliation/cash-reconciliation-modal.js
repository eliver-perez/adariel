let cashReconciliationModal = null;

function InitializeCashReconciliationModal() {
    cashReconciliationModal = document.getElementById('modal-cash-reconciliation');
	$('#modal-cash-reconciliation').on('hidden.te.modal', function () {
		generated_report_id = '';
	});
	$('#btn-download-cash-reconciliation-report').on('click', DownloadCashReconciliationReport);
}

var generated_report_id = '';

function CashReconciliationReport(id) {
	selected_cash_reconciliation_report = id;
	const modal = new te.Modal(cashReconciliationModal);
	
	generated_report_id = id;

	modal.show();
	
    const form = document.getElementById('form-preview-cash-reconciliation');
    form.action = `${homeURL}/api/cash-reconciliation/${id}/preview`;
    form.submit();
}

function DownloadCashReconciliationReport(id) {
	if (!generated_report_id) {
        return;
    }

    const url =
        `${homeURL}/api/cash-reconciliation/${generated_report_id}/preview?download=1`;
	console.log(url);

    const link = document.createElement('a');

    link.href = url;
    link.style.display = 'none';

    document.body.appendChild(link);
    link.click();
    link.remove();
}