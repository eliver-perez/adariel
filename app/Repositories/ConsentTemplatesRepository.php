<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

class ConsentTemplatesRepository {
    public function __construct(private PDO $db) {
    }

    public function getConnection() : PDO {
        return $this->db;
    }

    public function getAll(array $data): array
    {
        $stmt = $this->db->prepare("
            SELECT 
                cp.uuid,
                cp.codigo,
                cp.nombre,
                cp.version,
                pe.codigo estatus_codigo,
                pe.estatus,
                u.nombre registro,
                COALESCE(DATE_FORMAT(cp.f_registro, '%d/%m/%Y %r'), '') f_registro
            FROM consentimientos_plantillas cp
                INNER JOIN plantillas_estatus pe
                    ON cp.estatus = pe.id
                INNER JOIN usuarios u
                    ON cp.registro = u.id
            WHERE cp.empresa = :empresa
            ORDER BY cp.f_registro ASC
        ");
        $stmt->bindValue(':empresa', $data['organization'], PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTemplate(array $data): ?array {
        $stmt = $this->db->prepare("
            SELECT
                cp.codigo,
                cp.nombre,
                cp.version,
                pe.codigo estatus_codigo,
                pe.estatus,
                cp.logo_width,
                cp.interlineado,
                cp.font_size,
                u.nombre registro,
                CASE WHEN pe.codigo = 'borrador'
                    THEN cp.delta_borrador
                    ELSE cp.delta_json
                END delta,
                a.uuid logoId,
                a.nombre_original,
                COALESCE(DATE_FORMAT(cp.f_registro, '%d/%m/%Y %r'), '') f_registro,
                COALESCE(DATE_FORMAT(cp.f_actualizacion, '%d/%m/%Y %r'), '') f_actualizacion
            FROM consentimientos_plantillas cp
                INNER JOIN plantillas_estatus pe
                    ON cp.estatus = pe.id
                INNER JOIN usuarios u
                    ON cp.registro = u.id
                LEFT JOIN archivos a
                    ON cp.uuid = a.referencia
            WHERE cp.uuid = :uuid
                AND cp.empresa = :empresa
            LIMIT 1
        ");

        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':empresa', $data['organization'], PDO::PARAM_LOB);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        return $row ?: null;
    }

    public function getTemplateStatus(array $data): ?array {
        $stmt = $this->db->prepare("
            SELECT
                pe.codigo,
                pe.estatus
            FROM consentimientos_plantillas cp
                INNER JOIN plantillas_estatus pe
                    ON cp.estatus = pe.id
            WHERE cp.uuid = :uuid
                AND cp.empresa = :empresa
            LIMIT 1
        ");

        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':empresa', $data['organization'], PDO::PARAM_LOB);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function existsById(int $id): bool
    {
        $stmt = $this->db->prepare("
            SELECT 1
            FROM consentimientos_plantillas
            WHERE id = :id
            LIMIT 1
        ");

        $stmt->execute([
            'id' => $id
        ]);

        return (bool) $stmt->fetchColumn();
    }

    public function existsByCode(array $data): bool
    {
        $stmt = $this->db->prepare("
            SELECT 1
            FROM consentimientos_plantillas
            WHERE codigo = :code
                AND empresa = :empresa
            LIMIT 1
        ");

        $stmt->bindValue(':code', $data['code'], PDO::PARAM_STR);
        $stmt->bindValue(':empresa', $data['organization'], PDO::PARAM_INT);
        $stmt->execute();

        return (bool) $stmt->fetchColumn();
    }

    public function getStatusIdByCode(string $code): ?int {
        $stmt = $this->db->prepare("
            SELECT id
            FROM plantillas_estatus
            WHERE codigo = :code
            LIMIT 1");
        
        $stmt->execute([
            'code'  => $code
        ]);

        $id = $stmt->fetchColumn();

        return $id !== false ? (int) $id : null;
    }

    public function getTemplateNextVersion(): int {
        $stmt = $this->db->prepare("
            SELECT COALESCE(MAX(version), 0) + 1 AS version
            FROM consentimientos_plantillas
            FOR UPDATE
        ");

        $stmt->execute();

        return (int)$stmt->fetchColumn();
    }

    public function insert(array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO consentimientos_plantillas (
                uuid,
                empresa,
                codigo,
                nombre,
                version,
                plantilla,
                delta_borrador,
                documento_borrador,
                delta_json,
                contenido_html,
                estatus,
                registro,
                f_registro,
                f_actualizacion
            ) VALUES (
                :uuid,
                :empresa,
                :codigo,
                :nombre,
                0,
                1,
                '[]',
                '',
                '[]',
                '',
                :estatus,
                :registro,
                NOW(),
                NOW()
            );
        ");

        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':empresa', $data['organization'], PDO::PARAM_INT);
        $stmt->bindValue(':codigo', $data['code'], PDO::PARAM_STR);
        $stmt->bindValue(':nombre', $data['template_name'], PDO::PARAM_STR);
        $stmt->bindValue(':estatus', $data['status'], PDO::PARAM_STR);
        $stmt->bindValue(':registro', $data['uid'], PDO::PARAM_STR);
        $stmt->execute();

        return (int) $this->db->lastInsertId();
    }

    public function insertTemplateVersion($templateId, $version): void {
        $stmt = $this->db->prepare("UPDATE consentimientos_plantillas 
                                            SET version = :version
                                            WHERE id = :id");

        $stmt->execute([
            'id'                    => $templateId,
            'version'               => $version,
            ]);
    }

    public function update(array $data): void {
        $stmt = $this->db->prepare("UPDATE consentimientos_plantillas
                                            SET documento_borrador = :html,
                                                delta_borrador = :delta,
                                                logo = :logo,
                                                logo_checksum = :logo_checksum
                                            WHERE uuid = :uuid");
        $stmt->bindValue(':html', $data['template_html'], PDO::PARAM_STR);
        $stmt->bindValue(':delta', $data['template_delta'], PDO::PARAM_STR);
        $stmt->bindValue(':logo', $data['logo'], PDO::PARAM_STR);
        $stmt->bindValue(':logo_checksum', $data['logo_checksum'], PDO::PARAM_STR);
        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->execute();
    }

    public function getClinicName(array $data): string {
        $stmt = $this->db->prepare("SELECT valor FROM ajustes_empresas WHERE ajuste = 'clinica' AND empresa = :empresa");
        $stmt->bindValue(':empresa', $data['organization'], PDO::PARAM_LOB);
        $stmt->execute();
        return (string)$stmt->fetchColumn();
    }

    public function getTemplateLogo($data) {
        $stmt = $this->db->prepare("
            SELECT cp.logo, cp.logo_checksum
                    FROM consentimientos_plantillas cp
                    WHERE cp.uuid = :uuid
                        AND cp.empresa = :empresa
                    LIMIT 1
        ");
        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':empresa', $data['organization'], PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function deactivateAll($data) {
        $stmt = $this->db->prepare("
            UPDATE consentimientos_plantillas
                SET estatus = :inactive
                WHERE estatus = :active
                    AND empresa = :empresa
        ");
        $stmt->bindValue(':inactive', $data['inactive_id'], PDO::PARAM_INT);
        $stmt->bindValue(':active', $data['active_id'], PDO::PARAM_INT);
        $stmt->bindValue(':empresa', $data['organization'], PDO::PARAM_INT);
        $stmt->execute();
    }

    public function activate($data) {
        $stmt = $this->db->prepare("
            UPDATE consentimientos_plantillas
                SET delta_json = delta_borrador,
                    contenido_html = documento_borrador,
                    estatus = :active
                WHERE uuid = :uuid
                    AND empresa = :empresa
        ");
        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':active', $data['active_id'], PDO::PARAM_INT);
        $stmt->bindValue(':empresa', $data['organization'], PDO::PARAM_INT);
        $stmt->execute();
    }
}