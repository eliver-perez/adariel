<?php

declare(strict_types=1);

namespace App\Pdf\Sale;

use TCPDF;

class SalePdf extends TCPDF
{
    private array $headerData = [];

    public function setHeaderDataCustom(array $data): void
    {
        $this->headerData = $data;
    }

    public function Header(): void
    {
        $organization = $this->headerData['organization'] ?? '';
        $appLogo = $this->headerData['appLogo'] ?? null;
        $logo = $this->headerData['logo'] ?? null;
        $logoHeight = (float)($this->headerData['logo_height'] ?? 18);

        if ($logo !== null) {

            $this->Image(
                STORAGE_PATH . DIRECTORY_SEPARATOR . $logo['ruta'],
                15,
                5,
                0,
                $logoHeight
            );

        } elseif ($appLogo !== null) {

            $this->Image(
                BASE_PATH . DIRECTORY_SEPARATOR . $appLogo,
                15,
                5,
                0,
                $logoHeight
            );
        }

        $this->SetFont('helvetica', 'B', 11);
        $this->SetTextColor(0, 0, 0);

        $safeOrganization = htmlspecialchars(
            (string)$organization,
            ENT_QUOTES | ENT_SUBSTITUTE,
            'UTF-8'
        );

        $html = '
            <div style="text-align:center;">
                <strong>' . $safeOrganization . '</strong><br>
                <span style="font-size:9pt;">Reporte de Venta</span>
            </div>
        ';

        $this->writeHTMLCell(
            0,
            0,
            0,
            10,
            $html,
            0,
            1,
            false,
            true,
            'C',
            true
        );
    }

    public function Footer(): void
    {
        $this->SetY(-15);
        $this->SetFont('helvetica', 'I', 7);

        $this->Cell(
            0,
            10,
            'Página ' . $this->getAliasNumPage() . ' de ' . $this->getAliasNbPages(),
            0,
            false,
            'R'
        );
    }
}