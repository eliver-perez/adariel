<?php
    $escape = static function ($value): string {
        return htmlspecialchars(
            (string) $value,
            ENT_QUOTES | ENT_SUBSTITUTE,
            'UTF-8'
        );
    };

    $money = static function ($value): string {
        return '$'.number_format((float) $value, 2);
    };
?>

<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width">

    <style>
        @media only screen and (max-width: 620px) {
            .email-column {
                display: block !important;
                width: 100% !important;
                text-align: center !important;
                padding: 10px 0 !important;
            }

            .email-table {
                font-size: 12px !important;
            }

            .email-cell {
                padding: 10px 5px !important;
            }
        }
    </style>
</head>

<body style="margin:0; padding:0; background:#f3f4f6; font-family:Arial, Helvetica, sans-serif; color:#111827;">
    <table
        role="presentation"
        width="100%"
        cellspacing="0"
        cellpadding="0"
        border="0"
        style="background:#f3f4f6;"
    >
        <tr>
            <td align="center" style="padding:30px 12px;">
                <table
                    role="presentation"
                    width="760"
                    cellspacing="0"
                    cellpadding="0"
                    border="0"
                    style="width:100%; max-width:760px; background:#ffffff; border-radius:12px;"
                >
                    <tr>
                        <td align="center" style="padding:35px 20px 20px;">
                            <h1 style="margin:0; font-size:32px; line-height:40px;">
                                Recibo de Pago
                            </h1>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:0 25px 30px;">
                            <table
                                role="presentation"
                                width="100%"
                                cellspacing="0"
                                cellpadding="0"
                                border="0"
                                style="background:#f7f8fa; border-radius:16px;"
                            >
                                <tr>
                                    <td
                                        class="email-column"
                                        width="34%"
                                        align="center"
                                        valign="middle"
                                        style="padding:25px;"
                                    >
                                        <?php if ($hasLogo): ?>
                                            <img
                                                src="cid:organization_logo"
                                                alt="Logotipo"
                                                style="display:block; max-width:220px; max-height:130px; width:auto; height:auto;"
                                            >
                                        <?php endif; ?>
                                    </td>

                                    <td
                                        class="email-column"
                                        width="24%"
                                        align="center"
                                        valign="middle"
                                        style="padding:25px 10px;"
                                    >
                                        <img
                                            src="cid:receipt_qr"
                                            alt="QR del recibo"
                                            width="130"
                                            height="130"
                                            style="display:block; width:130px; height:130px; background:#ffffff; padding:8px; border-radius:8px;"
                                        >
                                    </td>

                                    <td
                                        class="email-column"
                                        width="42%"
                                        valign="middle"
                                        style="padding:25px; font-size:14px; line-height:22px;"
                                    >
                                        <div>
                                            <strong>Folio:</strong>
                                            <?= $escape($receipt['folio']); ?>
                                        </div>

                                        <div>
                                            <strong>Fecha:</strong>
                                            <?= $escape($receipt['payment_date']); ?>
                                        </div>

                                        <div style="height:16px;"></div>

                                        <div style="font-weight:bold; text-transform:uppercase;">
                                            Recibo de:
                                        </div>

                                        <div>
                                            <?= $escape($receipt['client']); ?>
                                        </div>

                                        <div>
                                            <strong>Método de pago:</strong>
                                            <?= $escape($receipt['payment_method']); ?>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="padding:0 25px;">
                            <table
                                class="email-table"
                                width="100%"
                                cellspacing="0"
                                cellpadding="0"
                                border="0"
                                style="border-collapse:collapse; font-size:14px;"
                            >
                                <thead>
                                    <tr style="background:#f7f8fa; color:#6b7280;">
                                        <th class="email-cell" align="left" style="padding:15px 10px;">
                                            #
                                        </th>
                                        <th class="email-cell" align="left" style="padding:15px 10px;">
                                            Producto
                                        </th>
                                        <th class="email-cell" align="right" style="padding:15px 10px;">
                                            PU
                                        </th>
                                        <th class="email-cell" align="right" style="padding:15px 10px;">
                                            Total
                                        </th>
                                        <th class="email-cell" align="right" style="padding:15px 10px;">
                                            Adeudo
                                        </th>
                                        <th class="email-cell" align="right" style="padding:15px 10px;">
                                            Pago
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    <?php foreach ($receipt['details'] as $detail): ?>
                                        <tr>
                                            <td class="email-cell" style="padding:16px 10px; border-bottom:1px solid #e5e7eb;">
                                                <?= (int) $detail['quantity']; ?>
                                            </td>

                                            <td class="email-cell" style="padding:16px 10px; border-bottom:1px solid #e5e7eb; font-weight:bold;">
                                                <?= $escape($detail['description']); ?>
                                            </td>

                                            <td class="email-cell" align="right" style="padding:16px 10px; border-bottom:1px solid #e5e7eb;">
                                                <?= $money($detail['base_cost']); ?>
                                            </td>

                                            <td class="email-cell" align="right" style="padding:16px 10px; border-bottom:1px solid #e5e7eb;">
                                                <?= $money($detail['total']); ?>
                                            </td>

                                            <td class="email-cell" align="right" style="padding:16px 10px; border-bottom:1px solid #e5e7eb;">
                                                <?= $money($detail['balance_before_payment']); ?>
                                            </td>

                                            <td class="email-cell" align="right" style="padding:16px 10px; border-bottom:1px solid #e5e7eb;">
                                                <?= $money($detail['payment_amount']); ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <td colspan="4"></td>

                                        <td align="right" style="padding:25px 10px; font-size:16px; font-weight:bold;">
                                            Total:
                                        </td>

                                        <td align="right" style="padding:25px 10px; color:#3154d8; font-size:20px; font-weight:bold;">
                                            <?= $money($receipt['amount_payment']); ?>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td align="center" style="padding:15px 25px 35px; color:#6b7280; font-size:12px;">
                            Este correo fue generado automáticamente por Adariel.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>