<?php

declare(strict_types=1);

namespace App\Pdf\Consent;

use InvalidArgumentException;

class ConsentPdfRenderer
{
    public function renderPreview(array $data): string
    {
        $fontSize = (float)($data['font_size'] ?? 9);
        $lineHeight = (float)($data['line_height'] ?? 1.2);

        if ($fontSize < 6 || $fontSize > 14) {
            throw new InvalidArgumentException('Valor de tamaño de fuente fuera de rango.');
        }

        if ($lineHeight < 1 || $lineHeight > 3) {
            throw new InvalidArgumentException('Valor de interlineado fuera de rango.');
        }

        $pdf = new ConsentPdf('P', 'mm', 'LETTER', true, 'UTF-8', false);

        $pdf->setCreator($data['appName']);
        $pdf->setAuthor($data['clinic_name'] ?? 'Empresa');
        $pdf->setTitle('Vista previa de consentimiento informado');
        $pdf->setSubject('Consentimiento informado');

        $pdf->setConsentHeaderData([
            'clinic_name' => $data['clinic_name'] ?? 'Empresa',
            'logo' => $data['logo'] ?? null,
            'logo_width' => $data['logo_width'] ?? 30,
        ]);

        $pdf->setMargins(15, 35, 15);
        $pdf->setHeaderMargin(8);
        $pdf->setFooterMargin(15);
        $pdf->setAutoPageBreak(true, 18);
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $pdf->AddPage();

        $pdf->SetFont('helvetica', '', $fontSize);
        $pdf->setCellHeightRatio($lineHeight);

        $delta = (string)($data['delta'] ?? '');

        $parser = new QuillDeltaToTcpdfParser();
        $blocks = $parser->parse($delta);

        $values = ConsentVariables::previewValues($data);

        foreach ($blocks as $block) {
            $html = strtr($block['html'], $values);

            if (trim(strip_tags($html)) !== '') {
                $pdf->writeHTMLCell(
                    0,
                    0,
                    '',
                    '',
                    $html,
                    0,
                    1,
                    false,
                    true,
                    '',
                    true
                );
            }

            switch ($block['type']) {
                case 'header':
                    $pdf->Ln(1.5);
                    break;

                case 'list_item':
                    $pdf->Ln(0.5);
                    break;

                case 'paragraph':
                    $pdf->Ln(1);
                    break;

                case 'list_close':
                    $pdf->Ln(0.5);
                    break;
            }
        }

        return $pdf->Output('preview-consentimiento.pdf', 'S');
    }
}