<?php

declare(strict_types=1);

namespace App\Pdf\Consent;

class ConsentVariables
{
    public static function previewValues(array $data): array
    {
        return [
            '{{clinica}}' => self::e($data['clinic_name'] ?? 'Empresa'),
            '{{domicilio-clinica}}' => self::e($data['clinic_address'] ?? 'Domicilio'),
            '{{telefono-clinica}}' => self::e($data['clinic_phone'] ?? 'Teléfono'),
            '{{email-clinica}}' => self::e($data['clinic_email'] ?? 'correo@adariel.com'),

            '{{paciente-nombre}}' => self::e($data['patient_name'] ?? 'Nombre del Paciente'),
            '{{paciente-edad}}' => self::e($data['patient_age'] ?? '00'),
            '{{paciente-fecha-nacimiento}}' => self::e($data['patient_birthdate'] ?? '00/00/0000'),
            '{{paciente-sexo}' => self::e($data['patient_sex'] ?? 'No especificado'),
            '{{paciente-domicilio}}' => self::e($data['patient_address'] ?? 'Domicilio del paciente'),
            '{{paciente-telefono}}' => self::e($data['patient_phone'] ?? 'Teléfono del paciente'),

            '{{responsable-nombre}}' => self::e($data['guardian_name'] ?? 'Nombre del responsable'),
            '{{responsable-parentesco}}' => self::e($data['guardian_relationship'] ?? 'Parentesco'),
            '{{responsable-telefono}}' => self::e($data['guardian_phone'] ?? 'Teléfono del responsable'),

            '{{fecha}}' => self::e($data['date'] ?? date('d/m/Y')),
            '{{hora}}' => self::e($data['time'] ?? date('h:i A')),
            '{{procedimiento}}' => self::e($data['procedure'] ?? 'Nombre del procedimiento'),
            '{{profesional}}' => self::e($data['professional'] ?? 'Nombre del profesional'),
            '{{cedula-profesional}}' => self::e($data['professional_license'] ?? 'Cédula profesional'),
            '{{diagnostico}}' => self::e($data['diagnosis'] ?? 'Diagnóstico o motivo de atención'),
            '{{observaciones}}' => self::e($data['observations'] ?? 'Observaciones'),

            '{{riesgos}}' => self::e($data['risks'] ?? 'Riesgos del procedimiento'),
            '{{beneficios}}' => self::e($data['benefits'] ?? 'Beneficios esperados'),
            '{{alternativas}}' => self::e($data['alternatives'] ?? 'Alternativas disponibles'),
            '{{cuidados-posteriores}}' => self::e($data['aftercare'] ?? 'Cuidados posteriores'),

            '{{firma-paciente}}' => '',
            '{{firma-responsable}}' => '',
            '{{firma-profesional}}' => '',
        ];
    }

    private static function e(mixed $value): string
    {
        return htmlspecialchars((string)$value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }
}