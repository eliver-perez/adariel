<?php

declare(strict_types=1);

namespace App\Pdf\CashReconciliation;

use TCPDF;

class CashReconciliationPdf extends TCPDF
{
    private array $consentHeaderData = [];

    public function setHeaderDataCustom(array $data): void
    {
        $this->consentHeaderData = $data;
    }

    public function Header(): void
    {
        $clinicName = $this->consentHeaderData['organization'] ?? '';
        $appLogo = $this->consentHeaderData['appLogo'] ?? null;
        $logo = $this->consentHeaderData['logo'] ?? null;
        $logoWidth = (float)($this->consentHeaderData['logo_width'] ?? 60);
        $logoHeight = (float)($this->consentHeaderData['logo_height'] ?? 20);

        if($logo != null) {

            $this->Image(
                STORAGE_PATH . DIRECTORY_SEPARATOR . $logo['ruta'],
                15,
                5,
                0,
                $logoHeight
            );

        } else if ($appLogo != null) {

            $this->Image(BASE_PATH . DIRECTORY_SEPARATOR . $appLogo,
                15,
                5,
                0,
                $logoHeight
            );

        }

        $this->SetFont('helvetica', 'B', 11);
        $this->SetTextColor(0, 0, 0);

        $safeOrganization = htmlspecialchars(
            (string)$clinicName,
            ENT_QUOTES | ENT_SUBSTITUTE,
            'UTF-8'
        );

        $html = '
            <div style="text-align:center;">
                <strong>' . $safeOrganization . '</strong><br>
                <span style="font-size:9pt;">Corte de Caja</span>
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
            'C'
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