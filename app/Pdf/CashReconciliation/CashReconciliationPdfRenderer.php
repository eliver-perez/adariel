<?php

declare(strict_types=1);

namespace App\Pdf\CashReconciliation;

use App\Core\DateTimeService;
use InvalidArgumentException;

class CashReconciliationPdfRenderer
{
    public function renderPreview(array $data): string
    {
        $datetimeService = new DateTimeService($data['timezone']);

        $fontSize = (float)($data['font_size'] ?? 8);
        $lineHeight = (float)($data['line_height'] ?? 1.2);

        if ($fontSize < 6 || $fontSize > 14) {
            throw new InvalidArgumentException(
                'Valor de tamaño de fuente fuera de rango.'
            );
        }

        if ($lineHeight < 1 || $lineHeight > 3) {
            throw new InvalidArgumentException(
                'Valor de interlineado fuera de rango.'
            );
        }

        $cash = $data['cash_reconciliation'];

        $pdf = new CashReconciliationPdf(
            'L',
            'mm',
            'LETTER',
            true,
            'UTF-8',
            false
        );

        $pdf->setCreator($data['appName']);
        $pdf->setAuthor($data['organization'] ?? 'Adariel ERP');
        $pdf->setTitle('Reporte de Corte de Caja');
        $pdf->setSubject('Corte de Caja');

        $pdf->setHeaderDataCustom([
            'organization'      => $data['organization'] ?? 'Adariel ERP',
            'appLogo'           => $data['appLogo'] ?? null,
            'logo'              => $data['logo'] ?? null,
            'logo_width'        => $data['logo_width'] ?? 60,
            'logo_height'        => $data['logo_height'] ?? 20,
        ]);

        /*
        * Este margen superior deja libre el espacio
        * ocupado por logo + encabezado.
        */
        $pdf->setMargins(15, 22, 15);

        $pdf->setHeaderMargin(0);
        $pdf->setFooterMargin(15);
        $pdf->setAutoPageBreak(true, 18);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $pdf->AddPage();

        $pdf->SetFont('helvetica', '', $fontSize);
        $pdf->setCellHeightRatio($lineHeight);

        /*
        * ========================================================
        * RESUMEN DEL CORTE
        * Solamente se imprime una vez.
        * ========================================================
        */

        $summary = $this->buildSummary($cash, $datetimeService);

        $pdf->writeHTML(
            $summary,
            true,
            false,
            true,
            false,
            ''
        );

        /*
        * Espacio entre resumen y pagos
        */
        // $pdf->Ln(1);

        /*
        * ========================================================
        * PAGOS
        * ========================================================
        */

        $payments = $cash['payments'] ?? [];

        $paymentsHtml = $this->buildPaymentsTable($payments, $datetimeService);

        $pdf->writeHTML(
            $paymentsHtml,
            true,
            false,
            true,
            false,
            ''
        );

        return $pdf->Output('preview-corte.pdf', 'S');
    }

    private function buildSummary(array $cash, $datetimeService): string {
        $money = static function ($value): string {
            return '$' . number_format((float)$value, 2);
        };

        $e = static function ($value): string {
            return htmlspecialchars(
                (string)($value ?? ''),
                ENT_QUOTES | ENT_SUBSTITUTE,
                'UTF-8'
            );
        };

        $openedAmount = $money($cash['opened_amount'] ?? 0);
        $cashSales = $money($cash['cash'] ?? 0);
        $otherPayments = $money($cash['other_payment_methods'] ?? 0);
        $totalSale = $money($cash['total_sale'] ?? 0);

        $cancelled = $money($cash['cancelled'] ?? 0);
        $withdrawals = $money($cash['cash_withdrawals'] ?? 0);
        $deposits = $money($cash['cash_deposits'] ?? 0);
        $expectedCash = $money($cash['expected_cash'] ?? 0);
        $difference = $money($cash['cash_difference'] ?? 0);

        $closedAmount = $money($cash['closed_amount'] ?? 0);

        $folio = $e($cash['folio'] ?? '');
        $cashRegister = $e($cash['caja'] ?? '');
        $status = $e($cash['status'] ?? '');

        $openedBy = $e($cash['opened_by_name'] ?? '');
        $openedDate = $cash['opened_date'] != null ? $datetimeService->fromUtcFormatted($cash['opened_date']) : '';

        $closedBy = $e($cash['closed_by_name'] ?? '');
        $closedDate = $cash['closed_date'] != null ? $datetimeService->fromUtcFormatted($cash['closed_date']) : '';

        return <<<HTML
            
            <label style="font-size:11pt; font-weight: bold; text-align: center;">Resumen del Corte</label>
            
            <br>
            <br>

            <table
                cellpadding="5"
                cellspacing="0"
                width="100%"
                style="border:1px solid #dddddd;"
            >
                <tr style="background-color:#f4f4f4;">
                    <td width="33%">
                        <span style="color:#666666;">Folio</span><br>
                        <b>{$folio}</b>
                    </td>

                    <td width="34%">
                        <span style="color:#666666;">Caja</span><br>
                        <b>{$cashRegister}</b>
                    </td>

                    <td width="33%">
                        <span style="color:#666666;">Estatus</span><br>
                        <b>{$status}</b>
                    </td>
                </tr>

                <tr>
                    <td width="25%">
                        <span style="color:#666666;">Abierto por</span><br>
                        <b>{$openedBy}</b>
                    </td>

                    <td width="25%">
                        <span style="color:#666666;">Fecha apertura</span><br>
                        <b>{$openedDate}</b>
                    </td>

                    <td width="25%">
                        <span style="color:#666666;">Cerrado por</span><br>
                        <b>{$closedBy}</b>
                    </td>

                    <td width="25%">
                        <span style="color:#666666;">Fecha cierre</span><br>
                        <b>{$closedDate}</b>
                    </td>
                </tr>

                <tr style="background-color:#f4f4f4;">
                    <td width="20%">
                        <span style="color:#666666;">Monto de apertura</span><br>
                        <b>{$openedAmount}</b>
                    </td>

                    <td width="20%">
                        <span style="color:#666666;">Ventas en efectivo</span><br>
                        <b>{$cashSales}</b>
                    </td>

                    <td width="20%">
                        <span style="color:#666666;">Otros medios</span><br>
                        <b>{$otherPayments}</b>
                    </td>

                    <td width="20%">
                        <span style="color:#666666;">Venta total</span><br>
                        <b>{$totalSale}</b>
                    </td>

                    <td width="20%">
                        <span style="color:#666666;">Efectivo esperado</span><br>
                        <b>{$expectedCash}</b>
                    </td>
                </tr>

                <tr>
                    <td width="20%">
                        <span style="color:#666666;">Cancelado</span><br>
                        <b>{$cancelled}</b>
                    </td>

                    <td width="20%">
                        <span style="color:#666666;">Retiros</span><br>
                        <b>{$withdrawals}</b>
                    </td>

                    <td width="20%">
                        <span style="color:#666666;">Depósitos</span><br>
                        <b>{$deposits}</b>
                    </td>

                    <td width="20%">
                        <span style="color:#666666;">Monto de cierre</span><br>
                        <b>{$closedAmount}</b>
                    </td>

                    <td width="20%">
                        <span style="color:#666666;">Diferencia</span><br>
                        <b>{$difference}</b>
                    </td>
                </tr>
            </table>

        HTML;
    }

    private function buildPaymentsTable(array $payments, $datetimeService): string {
        $rows = '';

        $fillColor = "#f4f4f4";
        $fill = false;
        foreach ($payments as $payment) {

            $fillColor = $fill ? '#fafafa' : '#fff';

            $folio = $this->escape($payment['folio'] ?? '');
            $cliente = $this->escape($payment['cliente'] ?? '');
            $metodo = $this->escape($payment['metodo_pago'] ?? '');
            $registro = $this->escape($payment['registro'] ?? '');
            $fecha = $payment['f_pago'] != null ? $datetimeService->fromUtcFormatted($payment['f_pago']) : '';

            $monto = '$' . number_format(
                (float)($payment['monto_pago'] ?? 0),
                2
            );

            $estatus = $payment['estatus'];
            $color = ((string)$payment['estatus_codigo'] ?? '') === 'active' ? '#000' : '#b22222';

            $rows .= '
                <tr style="background-color: '.$fillColor.'; color: ' . $color . ';">
                    <td width="9%">' . $folio . '</td>
                    <td width="23%">' . $cliente . '</td>
                    <td width="12%" align="center">' . $metodo . '</td>
                    <td width="12%" align="right">' . $monto . '</td>
                    <td width="21%">' . $registro . '</td>
                    <td width="8%" align="center">' . $estatus . '</td>
                    <td width="15%" align="right">' . $fecha . '</td>
                </tr>
            ';

            $fill = !$fill;
        }

        if ($rows === '') {
            $rows = '
                <tr>
                    <td colspan="7" align="center">
                        No existen pagos registrados en este corte.
                    </td>
                </tr>
            ';
        }

        return '
            <label style="font-size:11pt; font-weight: bold;">Pagos Registrados</label>
            
            <br>
            <br>

            <table
                cellpadding="5"
                cellspacing="0"
                width="100%"
                border="0"
            >

                <thead>
                    <tr
                        style="
                            background-color:#f1f2f4;
                            font-weight:bold;
                            color:#555555;
                        "
                    >
                        <th width="9%">FOLIO</th>
                        <th width="23%">CLIENTE</th>
                        <th width="12%" align="center">MÉTODO DE PAGO</th>
                        <th width="12%" align="right">MONTO</th>
                        <th width="21%">REGISTRO</th>
                        <th width="8%" align="center">ESTATUS</th>
                        <th width="15%" align="center">FECHA</th>
                    </tr>
                </thead>

                <tbody>
                    ' . $rows . '
                </tbody>

            </table>
        ';
    }

    private function escape(mixed $value): string {
        return htmlspecialchars(
            (string)$value,
            ENT_QUOTES | ENT_SUBSTITUTE,
            'UTF-8'
        );
    }
}