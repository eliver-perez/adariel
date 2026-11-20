<?php

declare(strict_types=1);

namespace App\Pdf\Sale;

use App\Core\DateTimeService;
use InvalidArgumentException;

class SalePdfRenderer
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

        $sale = $data['sale'] ?? null;

        if ($sale === null) {
            throw new InvalidArgumentException(
                'No se encontraron datos de la venta.'
            );
        }

        $pdf = new SalePdf(
            'P',
            'mm',
            'LETTER',
            true,
            'UTF-8',
            false
        );

        $pdf->setCreator($data['appName']);
        $pdf->setAuthor(
            $data['organization'] ?? 'Adariel ERP'
        );

        $pdf->setTitle('Reporte de Venta');
        $pdf->setSubject('Venta');

        $pdf->setHeaderDataCustom([
            'organization' => $data['organization'] ?? 'Adariel ERP',
            'appLogo'      => $data['appLogo'] ?? null,
            'logo'         => $data['logo'] ?? null,
            'logo_width'   => $data['logo_width'] ?? 60,
            'logo_height'  => $data['logo_height'] ?? 18,
        ]);

        $pdf->setMargins(15, 22, 15);

        $pdf->setHeaderMargin(0);
        $pdf->setFooterMargin(15);
        $pdf->setAutoPageBreak(true, 18);

        $pdf->setImageScale(
            PDF_IMAGE_SCALE_RATIO
        );

        $pdf->AddPage();

        $pdf->SetFont(
            'helvetica',
            '',
            $fontSize
        );

        $pdf->setCellHeightRatio(
            $lineHeight
        );

        /*
         * ========================================================
         * DATOS GENERALES
         * ========================================================
         */

        $pdf->writeHTML(
            $this->buildSaleInfo(
                $sale,
                $datetimeService
            ),
            true,
            false,
            true,
            false,
            ''
        );

        /*
         * ========================================================
         * PRODUCTOS Y SERVICIOS
         * ========================================================
         */

        $pdf->writeHTML(
            $this->buildDetailsTable(
                $sale['details'] ?? [],
                $sale
            ),
            true,
            false,
            true,
            false,
            ''
        );

        /*
         * ========================================================
         * TOTALES
         * ========================================================
         */

        $pdf->writeHTML(
            $this->buildTotals(
                $sale,
                $datetimeService
            ),
            true,
            false,
            true,
            false,
            ''
        );

        /*
         * ========================================================
         * CANCELACIÓN
         * ========================================================
         */

        if ($this->isCancelled($sale)) {

            $pdf->writeHTML(
                $this->buildCancellation(
                    $sale,
                    $datetimeService
                ),
                true,
                false,
                true,
                false,
                ''
            );
        }

        /*
         * ========================================================
         * OBSERVACIONES
         * ========================================================
         */

        if (
            trim(
                (string)(
                    $sale['observaciones']
                    ?? ''
                )
            ) !== ''
        ) {

            $pdf->writeHTML(
                $this->buildObservations(
                    $sale
                ),
                true,
                false,
                true,
                false,
                ''
            );
        }

        return $pdf->Output(
            'reporte-venta.pdf',
            'S'
        );
    }

    /*
     * ============================================================
     * DATOS GENERALES DE LA VENTA
     * ============================================================
     */

    private function buildSaleInfo(
        array $sale,
        DateTimeService $datetimeService
    ): string {

        $folio = $this->escape(
            $sale['folio'] ?? ''
        );

        $cliente = trim(
            (string)(
                $sale['cliente']
                ?? ''
            )
        );

        if ($cliente === '') {
            $cliente = 'Público en General';
        }

        $cliente = $this->escape(
            $cliente
        );

        $paciente = trim(
            (string)(
                $sale['paciente']
                ?? ''
            )
        );

        $paciente = $this->escape(
            $paciente
        );

        $registeredBy = $this->escape(
            $sale['registro'] ?? ''
        );

        $status = $this->escape(
            $sale['estatus'] ?? ''
        );

        /*
         * Fecha de venta almacenada en UTC.
         */

        $saleDate = '';

        if (!empty($sale['f_venta'])) {

            $saleDate =
                $datetimeService->fromUtcFormatted(
                    $sale['f_venta']
                );
        }

        $saleDate = $this->escape(
            $saleDate
        );

        /*
         * Color solamente para canceladas.
         */

        $statusColor = $this->isCancelled($sale)
            ? '#b22222'
            : '#000000';

        /*
         * Paciente es opcional.
         */

        $patientRow = '';

        if ($paciente !== '') {

            $patientRow = '
                <tr>

                    <td
                        width="15%"
                        style="color:#666666;"
                    >
                        Paciente:
                    </td>

                    <td width="85%">
                        <b>' . $paciente . '</b>
                    </td>

                </tr>
            ';
        }

        return '

            <br>

            <table
                cellpadding="3"
                cellspacing="0"
                width="100%"
            >

                <tr>

                    <td
                        width="15%"
                        style="color:#666666;"
                    >
                        Folio:
                    </td>

                    <td width="45%">
                        <b>' . $folio . '</b>
                    </td>

                    <td
                        width="15%"
                        align="right"
                        style="color:#666666;"
                    >
                        Estatus:
                    </td>

                    <td
                        width="25%"
                        align="right"
                    >
                        <b
                            style="
                                color:' . $statusColor . ';
                            "
                        >
                            ' . $status . '
                        </b>
                    </td>

                </tr>

                <tr>

                    <td
                        width="15%"
                        style="color:#666666;"
                    >
                        Fecha:
                    </td>

                    <td width="85%">
                        <b>' . $saleDate . '</b>
                    </td>

                </tr>

                <tr>

                    <td
                        width="15%"
                        style="color:#666666;"
                    >
                        Cliente:
                    </td>

                    <td width="85%">
                        <b>' . $cliente . '</b>
                    </td>

                </tr>

                ' . $patientRow . '

                <tr>

                    <td
                        width="15%"
                        style="color:#666666;"
                    >
                        Registró:
                    </td>

                    <td width="85%">
                        <b>' . $registeredBy . '</b>
                    </td>

                </tr>

            </table>

            <br>

            <hr style="color:#dddddd;">

        ';
    }

    /*
     * ============================================================
     * PRODUCTOS Y SERVICIOS
     * ============================================================
     */

    private function buildDetailsTable(
        array $details,
        array $sale
    ): string {

        $rows = '';

        $patient = trim(
            (string)(
                $sale['paciente']
                ?? ''
            )
        );

        $fill = false;

        foreach ($details as $detail) {

            $fillColor = $fill
                ? '#fafafa'
                : '#ffffff';

            /*
             * ====================================================
             * CONCEPTO
             * ====================================================
             */

            $isService = !empty(
                $detail['servicio_id']
            );

            $concept = '';

            if ($isService) {

                /*
                 * Servicio:
                 *
                 * CORTE Uñas - Paciente 45
                 */

                $concept = trim(
                    (string)(
                        $detail['servicio']
                        ?? ''
                    )
                );

                if ($patient !== '') {

                    $concept .=
                        ' - '
                        . $patient;
                }

            } else {

                /*
                 * Producto:
                 *
                 * POD-001 - Crema Podológica
                 */

                $productCode = trim(
                    (string)(
                        $detail['producto_codigo']
                        ?? ''
                    )
                );

                $productName = trim(
                    (string)(
                        $detail['producto']
                        ?? ''
                    )
                );

                if (
                    $productCode !== ''
                    && $productName !== ''
                ) {

                    $concept =
                        $productCode
                        . ' - '
                        . $productName;

                } elseif ($productName !== '') {

                    $concept = $productName;

                } else {

                    $concept = $productCode;
                }
            }

            /*
             * Descripción adicional.
             */

            $description = trim(
                (string)(
                    $detail['descripcion']
                    ?? ''
                )
            );

            /*
             * Si no encontramos nombre de producto o servicio,
             * utilizamos descripción.
             */

            if ($concept === '') {
                $concept = $description;
            }

            /*
             * Nombre principal.
             */

            $conceptHtml =
                '<b>'
                . $this->escape($concept)
                . '</b>';

            /*
             * Descripción debajo del concepto.
             */

            if (
                $description !== ''
                && strcasecmp(
                    $description,
                    $concept
                ) !== 0
            ) {

                $conceptHtml .=
                    '<br>'
                    . '<span style="color:#666666;">'
                    . $this->escape($description)
                    . '</span>';
            }

            /*
             * ====================================================
             * CANTIDAD
             * ====================================================
             */

            $rawQuantity = (float)(
                $detail['cantidad']
                ?? 0
            );

            if (
                floor($rawQuantity)
                === $rawQuantity
            ) {

                $quantity = number_format(
                    $rawQuantity,
                    0
                );

            } else {

                $quantity = number_format(
                    $rawQuantity,
                    2
                );
            }

            /*
             * ====================================================
             * MONTOS
             * ====================================================
             */

            $price = $this->money(
                $detail['precio_base']
                ?? 0
            );

            $discount = $this->money(
                $detail['descuento']
                ?? 0
            );

            $total = $this->money(
                $detail['total']
                ?? 0
            );

            /*
             * ====================================================
             * FILA
             * ====================================================
             */

            $rows .= '

                <tr
                    style="
                        background-color:'
                        . $fillColor
                        . ';
                    "
                >

                    <td width="48%">
                        ' . $conceptHtml . '
                    </td>

                    <td
                        width="10%"
                        align="center"
                    >
                        ' . $quantity . '
                    </td>

                    <td
                        width="14%"
                        align="right"
                    >
                        ' . $price . '
                    </td>

                    <td
                        width="14%"
                        align="right"
                    >
                        ' . $discount . '
                    </td>

                    <td
                        width="14%"
                        align="right"
                    >
                        <b>' . $total . '</b>
                    </td>

                </tr>
            ';

            $fill = !$fill;
        }

        /*
         * Venta sin detalles.
         */

        if ($rows === '') {

            $rows = '

                <tr>

                    <td
                        colspan="5"
                        align="center"
                    >
                        No existen productos o servicios
                        registrados en esta venta.
                    </td>

                </tr>
            ';
        }

        return '

            <br>

            <label
                style="
                    font-size:11pt;
                    font-weight:bold;
                "
            >
                Productos y Servicios
            </label>

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

                        <th width="48%">
                            CONCEPTO
                        </th>

                        <th
                            width="10%"
                            align="center"
                        >
                            CANT.
                        </th>

                        <th
                            width="14%"
                            align="right"
                        >
                            P. UNITARIO
                        </th>

                        <th
                            width="14%"
                            align="right"
                        >
                            DESCUENTO
                        </th>

                        <th
                            width="14%"
                            align="right"
                        >
                            TOTAL
                        </th>

                    </tr>

                </thead>

                <tbody>

                    ' . $rows . '

                </tbody>

            </table>

        ';
    }

    /*
     * ============================================================
     * TOTALES
     * ============================================================
     */

    private function buildTotals(
        array $sale,
        DateTimeService $datetimeService
    ): string {

        $subtotal = $this->money(
            $sale['subtotal'] ?? 0
        );

        $taxes = $this->money(
            $sale['impuestos'] ?? 0
        );

        $discount = $this->money(
            $sale['descuento'] ?? 0
        );

        $total = $this->money(
            $sale['total'] ?? 0
        );

        $paid = $this->money(
            $sale['pagado'] ?? 0
        );

        $due = $this->money(
            $sale['adeudo'] ?? 0
        );

        /*
         * ========================================================
         * FECHA DE LIQUIDACIÓN
         * ========================================================
         */

        $paidDate = '';

        if (!empty($sale['f_pagado'])) {

            $paidDate =
                $datetimeService->fromUtcFormatted(
                    $sale['f_pagado']
                );
        }

        $paidDate = $this->escape(
            $paidDate
        );

        /*
         * ========================================================
         * CANCELADO
         * ========================================================
         */

        $cancelledRow = '';

        if ($this->isCancelled($sale)) {

            /*
             * Este valor viene directamente de ventas.monto_cancelado.
             *
             * No se calcula a partir del total.
             */

            $cancelledAmount = $this->money(
                $sale['monto_cancelado']
                ?? 0
            );

            $cancelledRow = '

                <tr>

                    <td width="58%">
                    </td>

                    <td
                        width="20%"
                        align="right"
                    >
                        <span style="color:#666666;">
                            Cancelado
                        </span>
                    </td>

                    <td
                        width="22%"
                        align="right"
                    >
                        <b style="color:#b22222;">
                            ' . $cancelledAmount . '
                        </b>
                    </td>

                </tr>
            ';
        }

        /*
         * ========================================================
         * FECHA PAGADO
         * ========================================================
         */

        $paidDateRow = '';

        if ($paidDate !== '') {

            $paidDateRow = '

                <tr>

                    <td width="58%">
                    </td>

                    <td
                        width="20%"
                        align="right"
                        style="
                            color:#777777;
                            font-size:7pt;
                        "
                    >
                        Fecha de liquidación
                    </td>

                    <td
                        width="22%"
                        align="right"
                        style="
                            color:#777777;
                            font-size:7pt;
                        "
                    >
                        ' . $paidDate . '
                    </td>

                </tr>
            ';
        }

        /*
         * Utilizamos tres columnas:
         *
         * 58% espacio vacío
         * 20% etiqueta
         * 22% monto
         *
         * De esta forma evitamos tablas anidadas y TCPDF
         * mantiene mejor el bloque alineado a la derecha.
         */

        return '

            <br>

            <table
                cellpadding="3"
                cellspacing="0"
                width="100%"
            >

                <tr>

                    <td width="58%">
                    </td>

                    <td
                        width="20%"
                        align="right"
                    >
                        <span style="color:#666666;">
                            Subtotal
                        </span>
                    </td>

                    <td
                        width="22%"
                        align="right"
                    >
                        ' . $subtotal . '
                    </td>

                </tr>

                <tr>

                    <td width="58%">
                    </td>

                    <td
                        width="20%"
                        align="right"
                    >
                        <span style="color:#666666;">
                            Impuestos
                        </span>
                    </td>

                    <td
                        width="22%"
                        align="right"
                    >
                        ' . $taxes . '
                    </td>

                </tr>

                <tr>

                    <td width="58%">
                    </td>

                    <td
                        width="20%"
                        align="right"
                    >
                        <span style="color:#666666;">
                            Descuento
                        </span>
                    </td>

                    <td
                        width="22%"
                        align="right"
                    >
                        ' . $discount . '
                    </td>

                </tr>

                <tr>

                    <td width="58%">
                    </td>

                    <td
                        width="20%"
                        align="right"
                        style="
                            border-top:1px solid #cccccc;
                            font-size:10pt;
                        "
                    >
                        <b>Total</b>
                    </td>

                    <td
                        width="22%"
                        align="right"
                        style="
                            border-top:1px solid #cccccc;
                            font-size:10pt;
                        "
                    >
                        <b>' . $total . '</b>
                    </td>

                </tr>

                <tr>

                    <td width="58%">
                    </td>

                    <td
                        width="20%"
                        align="right"
                    >
                        <span style="color:#666666;">
                            Pagado
                        </span>
                    </td>

                    <td
                        width="22%"
                        align="right"
                    >
                        <b>' . $paid . '</b>
                    </td>

                </tr>

                ' . $cancelledRow . '

                <tr>

                    <td width="58%">
                    </td>

                    <td
                        width="20%"
                        align="right"
                    >
                        <span style="color:#666666;">
                            Pendiente
                        </span>
                    </td>

                    <td
                        width="22%"
                        align="right"
                    >
                        <b>' . $due . '</b>
                    </td>

                </tr>

                ' . $paidDateRow . '

            </table>

        ';
    }

    /*
     * ============================================================
     * DATOS DE CANCELACIÓN
     * ============================================================
     */

    private function buildCancellation(
        array $sale,
        DateTimeService $datetimeService
    ): string {

        if (!$this->isCancelled($sale)) {
            return '';
        }

        $cancelledBy = $this->escape(
            $sale['cancelo'] ?? ''
        );

        $cancelledDate = '';

        if (!empty($sale['f_cancelacion'])) {

            $cancelledDate =
                $datetimeService->fromUtcFormatted(
                    $sale['f_cancelacion']
                );
        }

        $cancelledDate = $this->escape(
            $cancelledDate
        );

        /*
         * No repetimos monto_cancelado aquí.
         * Ya está mostrado en el bloque de totales.
         */

        return '

            <br>
            <br>

            <table
                cellpadding="4"
                cellspacing="0"
                width="100%"
                style="
                    border-top:1px solid #dddddd;
                "
            >

                <tr>

                    <td width="50%">

                        <span style="color:#666666;">
                            Cancelada por:
                        </span>

                        &nbsp;

                        <b>
                            ' . $cancelledBy . '
                        </b>

                    </td>

                    <td
                        width="50%"
                        align="right"
                    >

                        <span style="color:#666666;">
                            Fecha de cancelación:
                        </span>

                        &nbsp;

                        <b>
                            ' . $cancelledDate . '
                        </b>

                    </td>

                </tr>

            </table>

        ';
    }

    /*
     * ============================================================
     * OBSERVACIONES
     * ============================================================
     */

    private function buildObservations(
        array $sale
    ): string {

        $observations = $this->escape(
            $sale['observaciones']
            ?? ''
        );

        return '

            <br>

            <table
                cellpadding="5"
                cellspacing="0"
                width="100%"
            >

                <tr>

                    <td
                        style="
                            border-bottom:1px solid #dddddd;
                        "
                    >
                        <b>Observaciones</b>
                    </td>

                </tr>

                <tr>

                    <td style="color:#555555;">
                        '
                        . nl2br($observations)
                        . '
                    </td>

                </tr>

            </table>

        ';
    }

    /*
     * ============================================================
     * VENTA CANCELADA
     * ============================================================
     */

    private function isCancelled(
        array $sale
    ): bool {

        $code = strtolower(
            trim(
                (string)(
                    $sale['estatus_codigo']
                    ?? ''
                )
            )
        );

        return in_array(
            $code,
            [
                'cancelled',
                'canceled',
                'cancelado',
                'cancelada'
            ],
            true
        );
    }

    /*
     * ============================================================
     * FORMATO MONEDA
     * ============================================================
     */

    private function money(
        mixed $value
    ): string {

        return '$'
            . number_format(
                (float)$value,
                2
            );
    }

    /*
     * ============================================================
     * ESCAPE HTML
     * ============================================================
     */

    private function escape(
        mixed $value
    ): string {

        return htmlspecialchars(
            (string)($value ?? ''),
            ENT_QUOTES | ENT_SUBSTITUTE,
            'UTF-8'
        );
    }
}