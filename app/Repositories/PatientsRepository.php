<?php

declare(strict_types=1);

namespace App\Repositories;

use PDO;

class PatientsRepository
{
    public function __construct(private PDO $db) {
    }

    public function getConnection() : PDO {
        return $this->db;
    }

    public function getAll(array $data): array {
        $sql = "
            SELECT
                p.id,
                p.uuid,
                p.clave,
                TRIM(
                    CONCAT(
                        p.nombre, ' ',
                        COALESCE(p.paterno, ''), ' ',
                        COALESCE(p.materno, '')
                    )
                ) nombre,
                COALESCE(DATE_FORMAT(p.f_nacimiento, '%d/%m/%Y'), '') f_nacimiento,
                g.genero,
                p.telefono,
                p.movil,
                COALESCE(DATE_FORMAT(p.f_registro, '%d/%m/%Y %r'), '') f_registro,
                COALESCE(DATE_FORMAT(p.f_ultima_visita, '%d/%m/%Y %r'), '') f_ultima_visita
            FROM pacientes p
                LEFT JOIN generos g
                    ON p.genero = g.id
            WHERE p.empresa = :empresa
        ";

        $params = [];

        $fields = ['p.clave', 'p.nombre', 'p.paterno', 'p.materno', 'p.telefono', 'p.movil'];

        $conditions = [];
        $params = [];

        foreach ($fields as $i => $field) {
            $param = "search_$i";
            $conditions[] = "$field LIKE :$param";
            $params[$param] = '%' . $data['search'] . '%';
        }

        $sql .= " AND (" . implode(' OR ', $conditions) . ")";

        $sql .= "
            ORDER BY nombre ASC
            LIMIT :limit OFFSET :offset
        ";

        $stmt = $this->db->prepare($sql);

        foreach ($params as $key => $value) {
            $stmt->bindValue(':' . $key, $value, PDO::PARAM_STR);
        }

        $stmt->bindValue(':empresa', $data['organizationId'], PDO::PARAM_INT);
        $stmt->bindValue(':limit', $data['limit'], PDO::PARAM_INT);
        $stmt->bindValue(':offset', $data['offset'], PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getPatientId(array $data): ?int {
        $stmt = $this->db->prepare("
            SELECT id
            FROM pacientes
            WHERE uuid = :uuid
                AND empresa = :empresa
            LIMIT 1");
        $stmt->bindValue(':uuid', $data['uuid'], PDO::PARAM_LOB);
        $stmt->bindValue(':empresa', $data['organization'], PDO::PARAM_INT);
        $stmt->execute();
        $id = $stmt->fetchColumn();

        return $id !== false ? (int) $id : null;
    }

    public function getRelationshipIdByCode(string $code): ?int {
        $stmt = $this->db->prepare("
            SELECT id
            FROM parentescos
            WHERE codigo = :code
            LIMIT 1");
        
        $stmt->execute([
            'code'  => $code
        ]);

        $id = $stmt->fetchColumn();

        return $id !== false ? (int) $id : null;
    }

    public function getPatientNextConsecutive(): int {
        $stmt = $this->db->prepare("
            SELECT COALESCE(MAX(consecutivo), 0) + 1 AS consecutivo
            FROM pacientes
            FOR UPDATE
        ");

        $stmt->execute();

        return (int)$stmt->fetchColumn();
    }

    public function updatePatientCode(int $patientId, array $data): void {
        $stmt = $this->db->prepare("
            UPDATE pacientes
            SET consecutivo = :consecutivo,
                clave = :clave
            WHERE id = :id
        ");

        $stmt->execute([
            'consecutivo' => $data['consecutive'],
            'clave' => $data['code'],
            'id' => $patientId,
        ]);
    }

    public function getClientNextConsecutive(): int {
        $stmt = $this->db->prepare("
            SELECT COALESCE(MAX(consecutivo), 0) + 1 AS consecutivo
            FROM clientes
            FOR UPDATE
        ");

        $stmt->execute();

        return (int)$stmt->fetchColumn();
    }

    public function updateClientCode(int $clientId, array $data): void {
        $stmt = $this->db->prepare("
            UPDATE clientes
            SET consecutivo = :consecutivo,
                clave = :clave
            WHERE id = :id
        ");

        $stmt->execute([
            'consecutivo' => $data['consecutive'],
            'clave' => $data['code'],
            'id' => $clientId,
        ]);
    }

    public function insertPatient(array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO pacientes (
                uuid,
                empresa,
                nombre,
                paterno,
                materno,
                curp,
                f_nacimiento,
                genero,
                telefono,
                movil,
                email,
                calle,
                num_ext,
                num_int,
                colonia,
                medicamentos,
                suplementos,
                antecedentes_familiares,
                observaciones_generales,
                registro,
                f_registro
            ) VALUES (
                :uuid,
                :empresa,
                :nombre,
                :paterno,
                :materno,
                :curp,
                :f_nacimiento,
                :genero,
                :telefono,
                :movil,
                :email,
                :calle,
                :num_ext,
                :num_int,
                :colonia,
                :medicamentos,
                :suplementos,
                :antecedentes_familiares,
                :observaciones_generales,
                :registro,
                NOW()
            )
        ");

        $stmt->execute([
            'uuid'                          => $data['uuid'],
            'empresa'                       => $data['organizationId'],
            'nombre'                        => $data['first_name'],
            'paterno'                       => $data['last_name'],
            'materno'                       => $data['last_name_2'],
            'curp'                          => $data['curp'],
            'f_nacimiento'                  => $data['dob'],
            'genero'                        => $data['gender'],
            'telefono'                      => $data['phone'],
            'movil'                         => $data['mobile'],
            'email'                         => $data['email'],
            'calle'                         => $data['street'],
            'num_ext'                       => $data['ext_no'],
            'num_int'                       => $data['int_no'],
            'colonia'                       => $data['locality'],
            'medicamentos'                  => $data['current_medications'],
            'suplementos'                   => $data['supplements'],
            'antecedentes_familiares'       => $data['family_medical_history'],
            'observaciones_generales'       => $data['general_observations'],
            'registro'                      => $data['uid'],
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function insertClient(array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO clientes (
                uuid,
                empresa,
                es_empresa,
                razon_social,
                nombre,
                paterno,
                materno,
                curp,
                f_nacimiento,
                genero,
                telefono,
                movil,
                email,
                calle,
                num_ext,
                num_int,
                colonia,
                adeudo,
                ultimo_pago,
                registro,
                f_registro
            ) VALUES (
                :uuid,
                :empresa,
                0,
                '',
                :nombre,
                :paterno,
                :materno,
                :curp,
                :f_nacimiento,
                :genero,
                :telefono,
                :movil,
                :email,
                :calle,
                :num_ext,
                :num_int,
                :colonia,
                0,
                0,
                :registro,
                NOW()
            )
        ");

        $stmt->execute([
            'uuid'                          => $data['uuid'],
            'empresa'                       => $data['organizationId'],
            'nombre'                        => $data['first_name'],
            'paterno'                       => $data['last_name'],
            'materno'                       => $data['last_name_2'],
            'curp'                          => $data['curp'],
            'f_nacimiento'                  => $data['dob'],
            'genero'                        => $data['gender'],
            'telefono'                      => $data['phone'],
            'movil'                         => $data['mobile'],
            'email'                         => $data['email'],
            'calle'                         => $data['street'],
            'num_ext'                       => $data['ext_no'],
            'num_int'                       => $data['int_no'],
            'colonia'                       => $data['locality'],
            'registro'                      => $data['uid'],
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function insertClientPatient(int $clientId, int $patientId, array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO clientes_pacientes (
                uuid,
                cliente,
                paciente,
                parentesco,
                principal,
                registro,
                activo,
                f_registro
            ) VALUES (
                :uuid,
                :cliente,
                :paciente,
                :parentesco,
                1,
                :registro,
                1,
                NOW()
            )
        ");

        $stmt->execute([
            'uuid'                          => $data['uuid'],
            'cliente'                       => $clientId,
            'paciente'                      => $patientId,
            'parentesco'                    => $data['relationship'],
            'registro'                      => $data['uid'],
        ]);

        return (int) $this->db->lastInsertId();
    }

    public function insertClientBilling(int $clientId, array $data): int {
        $stmt = $this->db->prepare("
            INSERT INTO clientes_facturacion (
                uuid,
                cliente,
                regimen,
                rfc,
                razon_social,
                calle,
                num_ext,
                num_int,
                colonia,
                cp,
                telefono,
                email,
                f_registro
            ) VALUES (
                :uuid,
                :cliente,
                :regimen,
                :rfc,
                :razon_social,
                :calle,
                :num_ext,
                :num_int,
                :colonia,
                :cp,
                :telefono,
                :email,
                NOW()
            )
        ");

        $stmt->execute([
            'uuid'                          => $data['uuid'],
            'cliente'                       => $clientId,
            'regimen'                       => $data['regimen'],
            'rfc'                           => $data['rfc'],
            'razon_social'                  => $data['name'],
            'calle'                         => $data['street'],
            'num_ext'                       => $data['ext_no'],
            'num_int'                       => $data['int_no'],
            'colonia'                       => $data['locality'],
            'cp'                            => $data['zip_code'],
            'telefono'                      => $data['phone'],
            'email'                         => $data['email'],
        ]);

        return (int) $this->db->lastInsertId();
    }
}