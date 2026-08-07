<?php

namespace App\Repositories;

use JsonException;
use PDO;
use RuntimeException;

final readonly class WhatsAppMessageRepository
{
    public function __construct(
        private PDO $database
    ) {
    }

    public function createPending(
        string $uuid,
        int $companyId,
        int $integrationId,
        string $provider,
        string $type,
        string $recipient,
        ?string $event = null,
        ?string $referenceType = null,
        ?int $referenceId = null,
        ?string $template = null,
        ?string $language = null,
        ?string $content = null,
        array $parameters = [],
        array $requestPayload = [],
        bool $isTest = false,
        ?int $userId = null
    ): int {
        $statement = $this->database->prepare(
            'INSERT INTO mensajes_whatsapp (
                uuid,
                empresa,
                integracion,
                proveedor,
                tipo,
                evento,
                referencia_tipo,
                referencia_id,
                destinatario,
                plantilla,
                idioma,
                contenido,
                parametros,
                estatus,
                solicitud,
                intentos,
                es_prueba,
                registrado_por
            ) VALUES (
                :uuid,
                :empresa,
                :integracion,
                :proveedor,
                :tipo,
                :evento,
                :referencia_tipo,
                :referencia_id,
                :destinatario,
                :plantilla,
                :idioma,
                :contenido,
                :parametros,
                :estatus,
                :solicitud,
                :intentos,
                :es_prueba,
                :registrado_por
            )'
        );

        $parametersJson = $this->encodeNullableJson($parameters);
        $requestJson = $this->encodeNullableJson($requestPayload);

        $statement->bindParam(
            ':uuid',
            $uuid,
            PDO::PARAM_LOB
        );

        $statement->bindParam(
            ':empresa',
            $companyId,
            PDO::PARAM_INT
        );

        $statement->bindParam(
            ':integracion',
            $integrationId,
            PDO::PARAM_INT
        );

        $statement->bindParam(
            ':proveedor',
            $provider,
            PDO::PARAM_STR
        );

        $statement->bindParam(
            ':tipo',
            $type,
            PDO::PARAM_STR
        );

        $statement->bindValue(
            ':evento',
            $event,
            $event === null ? PDO::PARAM_NULL : PDO::PARAM_STR
        );

        $statement->bindValue(
            ':referencia_tipo',
            $referenceType,
            $referenceType === null
                ? PDO::PARAM_NULL
                : PDO::PARAM_STR
        );

        $statement->bindValue(
            ':referencia_id',
            $referenceId,
            $referenceId === null
                ? PDO::PARAM_NULL
                : PDO::PARAM_INT
        );

        $statement->bindParam(
            ':destinatario',
            $recipient,
            PDO::PARAM_STR
        );

        $statement->bindValue(
            ':plantilla',
            $template,
            $template === null
                ? PDO::PARAM_NULL
                : PDO::PARAM_STR
        );

        $statement->bindValue(
            ':idioma',
            $language,
            $language === null
                ? PDO::PARAM_NULL
                : PDO::PARAM_STR
        );

        $statement->bindValue(
            ':contenido',
            $content,
            $content === null
                ? PDO::PARAM_NULL
                : PDO::PARAM_STR
        );

        $statement->bindValue(
            ':parametros',
            $parametersJson,
            $parametersJson === null
                ? PDO::PARAM_NULL
                : PDO::PARAM_STR
        );

        $statement->bindValue(
            ':estatus',
            'pendiente',
            PDO::PARAM_STR
        );

        $statement->bindValue(
            ':solicitud',
            $requestJson,
            $requestJson === null
                ? PDO::PARAM_NULL
                : PDO::PARAM_STR
        );

        $statement->bindValue(
            ':intentos',
            1,
            PDO::PARAM_INT
        );

        $statement->bindValue(
            ':es_prueba',
            $isTest ? 1 : 0,
            PDO::PARAM_INT
        );

        $statement->bindValue(
            ':registrado_por',
            $userId,
            $userId === null
                ? PDO::PARAM_NULL
                : PDO::PARAM_INT
        );

        $statement->execute();

        return (int) $this->database->lastInsertId();
    }

    public function markAsSent(
        int $messageId,
        ?string $providerMessageId,
        ?int $statusCode,
        array $response
    ): void {
        $statement = $this->database->prepare(
            'UPDATE mensajes_whatsapp
             SET proveedor_mensaje_id = :proveedor_mensaje_id,
                 estatus = :estatus,
                 codigo_http = :codigo_http,
                 codigo_error = NULL,
                 error = NULL,
                 respuesta = :respuesta,
                 enviado_at = CURRENT_TIMESTAMP,
                 fallido_at = NULL
             WHERE id = :id'
        );

        $statement->execute([
            'proveedor_mensaje_id' => $providerMessageId,
            'estatus'              => 'enviado',
            'codigo_http'          => $statusCode,
            'respuesta'            => $this->encodeNullableJson($response),
            'id'                   => $messageId,
        ]);
    }

    public function markAsFailed(
        int $messageId,
        ?int $statusCode,
        ?string $errorCode,
        ?string $error,
        array $response = []
    ): void {
        $statement = $this->database->prepare(
            'UPDATE mensajes_whatsapp
             SET estatus = :estatus,
                 codigo_http = :codigo_http,
                 codigo_error = :codigo_error,
                 error = :error,
                 respuesta = :respuesta,
                 fallido_at = CURRENT_TIMESTAMP
             WHERE id = :id'
        );

        $statement->execute([
            'estatus'      => 'fallido',
            'codigo_http'  => $statusCode,
            'codigo_error' => $errorCode,
            'error'        => $error,
            'respuesta'    => $this->encodeNullableJson($response),
            'id'           => $messageId,
        ]);
    }

    private function encodeNullableJson(array $data): ?string
    {
        if ($data === []) {
            return null;
        }

        try {
            return json_encode(
                $data,
                JSON_THROW_ON_ERROR |
                JSON_UNESCAPED_UNICODE |
                JSON_UNESCAPED_SLASHES
            );
        } catch (JsonException $exception) {
            throw new RuntimeException(
                'No fue posible convertir la información del mensaje a JSON.',
                previous: $exception
            );
        }
    }
}