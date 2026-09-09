<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Service;
use App\Core\DateTimeService;
use App\Repositories\PaymentsRepository;
use App\Repositories\PaymentsStatusRepository;
use App\Repositories\CashReconciliationRepository;
use App\Repositories\CashReconciliationStatusRepository;
use App\Repositories\CashRegisterRepository;
use App\Repositories\MediaRepository;
use App\Repositories\SettingsRepository;
use App\Services\SettingsService;
use App\Services\MailService;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use chillerlan\QRCode\Output\QRGdImagePNG;
use InvalidArgumentException;
use RuntimeException;

class PaymentsService extends Service
{
    public function __construct(
        private PaymentsRepository $paymentsRepository,
        private PaymentsStatusRepository $paymentsStatusRepository,
        private CashReconciliationRepository $cashReconciliationRepository,
        private CashReconciliationStatusRepository $cashReconciliationStatusRepository,
        private CashRegisterRepository $cashRegisterRepository,
        private MediaRepository $mediaRepository,
        private SettingsRepository $settingsRepository
    ) {
    }

    public function getAll(array $data): array {
        try {
            $uid = $this->normalizeRequiredInt($data['uid'] ?? null, 'No existe una sesion activa.');
            $organizationId = $this->normalizeRequiredInt($data['organizationId'] ?? null, 'No se encontraron datos de su empresa.');
            $branchId = $this->normalizeRequiredInt($data['branchId'] ?? null, 'No se encontraron datos de una sucursal.');

            $datetimeService = new DateTimeService($data['timezone']);

            $payments_data = $this->paymentsRepository->getAll([
                'search_by'                         => $data['searchBy'],
                'organization'                      => $organizationId,
                'branch'                            => $branchId,
                'search'                            => $data['search'] !== '' ? $data['search'] : null,
                'limit'                             => $data['limit'],
                'offset'                            => $data['offset'],
                'status'                            => $data['status'],
                'uid'                               => $data['uid'],
            ]);

            $payments = array();
            foreach($payments_data as $p) {
                array_push($payments, array(
                    'id'                            => $this->uuidBinarytoString($p['uuid']),
                    'folio'                         => $p['folio'],
                    'consecutivo'                   => $p['consecutivo'],
                    'client'                        => $p['cliente'],
                    'payment_method'                => $p['metodo_pago'],
                    'amount'                        => $p['monto_pago'],
                    'registered_by'                 => $p['registro'],
                    'status'                        => $p['estatus'],
                    'payment_date'                  => $datetimeService->fromUtcFormatted($p['f_pago']),
                    'registered_date'               => $datetimeService->fromUtcFormatted($p['f_registro']),
                    'update_date'                   => $datetimeService->fromUtcFormatted($p['f_actualizacion']),
                ));
            }

            return $payments;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    function getPayment($data): ?array {
        try {
            $uid = $this->normalizeRequiredInt($data['uid'] ?? null, 'No existe una sesion activa.');
            $organizationId = $this->normalizeRequiredInt($data['organizationId'] ?? null, 'No se encontraron datos de su empresa.');
            $branchId = $this->normalizeRequiredInt($data['branchId'] ?? null, 'No se encontraron datos de una sucursal.');

            $datetimeService = new DateTimeService($data['timezone']);

            $uuid = $this->normalizeRequiredText(
                $data['uuid'] ?? null,
                'Error al recibir identificador del pago.'
            );

            $payment_data = $this->paymentsRepository->getPayment([
                'uuid'                          => $this->uuidStringtoBinary($uuid),
                'organization'                  => $organizationId
            ]);

            if(!$payment_data)
                throw new RuntimeException("Ocurrio un error al intentar obtener la información.");

            $payment_sales_data = $this->paymentsRepository->getSalesByPayment([
                'uuid'                          => $this->uuidStringtoBinary($uuid),
                'organization'                  => $organizationId
            ]);
            
            $payment_sales = array();
            foreach($payment_sales_data as $psd) {
                array_push($payment_sales, array(
                    'id'                                => $this->uuidBinarytoString($psd['uuid']),
                    'folio'                             => $psd['folio'],
                    'client'                            => $psd['cliente'],
                    'patient'                           => $psd['paciente'],
                    'subtotal'                          => $psd['subtotal'],
                    'taxes'                             => $psd['impuestos'],
                    'total'                             => $psd['total'],
                    'discount'                          => $psd['descuento'],
                    'amount_paid'                       => $psd['pagado'],
                    'balance_due'                       => $psd['adeudo'],
                    'balance_due_before'                => $psd['adeudo_anterior'],
                    'payment_amount'                    => $psd['monto_pago'],
                    'balance_due_after'                 => $psd['adeudo_actual'],
                    'registered_by'                     => $psd['registro'],
                    'status'                            => $psd['estatus'],
                    'sale_date'                         => $datetimeService->fromUtcFormatted($psd['f_venta']),
                    'registered_date'                   => $datetimeService->fromUtcFormatted($psd['f_registro']),
                    'update_date'                       => $datetimeService->fromUtcFormatted($psd['f_actualizacion'])
                ));
            }

            $payment_details_data = $this->paymentsRepository->getPaymentDetails([
                'uuid'                          => $this->uuidStringtoBinary($uuid),
                'organization'                  => $organizationId
            ]);

            $payment_details = array();
            foreach($payment_details_data as $psd) {
                array_push($payment_details, array(
                    'id'                            => $this->uuidBinarytoString($psd['uuid']),
                    'type'                          => $psd['tipo'],
                    'sale_folio'                    => $psd['folio_venta'],
                    'description'                   => $psd['descripcion'],
                    'quantity'                      => $psd['cantidad'],
                    'base_cost'                     => $psd['precio_base'],
                    'subtotal'                      => $psd['subtotal'],
                    'discount'                      => $psd['descuento'],
                    'taxes'                         => $psd['impuestos'],
                    'total'                         => $psd['total'],
                    'paid'                          => $psd['pagado'],
                    'current_balance_due'           => $psd['adeudo'],
                    'balance_before_payment'        => $psd['adeudo_anterior'],
                    'payment_amount'                => $psd['monto_pago'],
                    'balance_after_payment'         => $psd['adeudo_actual'],
                ));
            }
            
            return [
                'id'                                => $this->uuidBinarytoString($payment_data['uuid']),
                'folio'                             => $payment_data['folio'],
                'cash_reconciliation_folio'         => $payment_data['folio_corte'],
                'cash_reconciliation_status'        => $payment_data['codigo_estatus_corte'],
                'consecutive'                       => $payment_data['consecutivo'],
                'client'                            => $payment_data['cliente'],
                'payment_method'                    => $payment_data['metodo_pago'],
                'amount_payment'                    => $payment_data['monto_pago'],
                'registered_by'                     => $payment_data['registro'],
                'cancelled_by'                      => $payment_data['cancelo'] ?? '',
                'cancelled_date'                    => $payment_data['f_cancelacion'] != null ? $datetimeService->fromUtcFormatted($payment_data['f_cancelacion']) : '',
                'cancelled_reason'                  => $payment_data['motivo_cancelacion'] ?? '',
                'status_code'                       => $payment_data['codigo_estatus_pago'],
                'status'                            => $payment_data['estatus'],
                'payment_date'                      => $datetimeService->fromUtcFormatted($payment_data['f_pago']),
                'registered_date'                   => $datetimeService->fromUtcFormatted($payment_data['f_registro']),
                'update_date'                       => $datetimeService->fromUtcFormatted($payment_data['f_actualizacion']),
                'sales'                             => $payment_sales,
                'payment_details'                   => $payment_details
            ];
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    function getPaymentReceipt($data): ?array {
        try {
            $uid = $this->normalizeRequiredInt($data['uid'] ?? null, 'No existe una sesion activa.');
            $organizationId = $this->normalizeRequiredInt($data['organizationId'] ?? null, 'No se encontraron datos de su empresa.');
            $branchId = $this->normalizeRequiredInt($data['branchId'] ?? null, 'No se encontraron datos de una sucursal.');

            $datetimeService = new DateTimeService($data['timezone']);

            $uuid = $this->normalizeRequiredText(
                $data['uuid'] ?? null,
                'Error al recibir identificador del pago.'
            );

            $organization_data = $this->mediaRepository->findByOrganizationType([
                'type'                          => 'ORGANIZATION_LOGO',
                'organization'                  => $organizationId,
            ]);

            $payment_data = $this->paymentsRepository->getPayment([
                'uuid'                          => $this->uuidStringtoBinary($uuid),
                'organization'                  => $organizationId
            ]);

            if(!$payment_data)
                throw new RuntimeException("Ocurrio un error al intentar obtener la información.");

            $payment_details_data = $this->paymentsRepository->getPaymentDetails([
                'uuid'                          => $this->uuidStringtoBinary($uuid),
                'organization'                  => $organizationId
            ]);

            $payment_details = array();
            foreach($payment_details_data as $pd) {
                array_push($payment_details, array(
                    'id'                            => $this->uuidBinarytoString($pd['uuid']),
                    'type'                          => $pd['tipo'],
                    'description'                   => $pd['descripcion'],
                    'quantity'                      => $pd['cantidad'],
                    'base_cost'                     => $pd['precio_base'],
                    'subtotal'                      => $pd['subtotal'],
                    'discount'                      => $pd['descuento'],
                    'taxes'                         => $pd['impuestos'],
                    'total'                         => $pd['total'],
                    'paid'                          => $pd['pagado'],
                    'current_balance_due'           => $pd['adeudo'],
                    'balance_before_payment'        => $pd['adeudo_anterior'],
                    'payment_amount'                => $pd['monto_pago'],
                    'balance_after_payment'         => $pd['adeudo_actual'],
                ));
            }
            
            return [
                'id'                                => $this->uuidBinarytoString($payment_data['uuid']),
                'logo'                              => $organization_data != null ? $this->uuidBinaryToString($organization_data['uuid']) : '',
                'folio'                             => $payment_data['folio'],
                'cash_reconciliation_folio'         => $payment_data['folio_corte'],
                'consecutive'                       => $payment_data['consecutivo'],
                'client'                            => $payment_data['cliente'],
                'payment_method'                    => $payment_data['metodo_pago'],
                'amount_payment'                    => $payment_data['monto_pago'],
                'registered_by'                     => $payment_data['registro'],
                'status'                            => $payment_data['estatus'],
                'payment_date'                      => $datetimeService->fromUtcFormatted($payment_data['f_pago']),
                'registered_date'                   => $datetimeService->fromUtcFormatted($payment_data['f_registro']),
                'update_date'                       => $datetimeService->fromUtcFormatted($payment_data['f_actualizacion']),
                'details'                           => $payment_details,
            ];
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function sendReceipt(array $data): ?array {
        try {
            $uid = $this->normalizeRequiredInt($data['uid'] ?? null, 'No existe una sesion activa.');
            $organizationId = $this->normalizeRequiredInt($data['organizationId'] ?? null, 'No se encontraron registros de su empresa.');
            $uuid = $this->normalizeRequiredText($data['uuid'] ?? null, 'Error al recibir identificador del pago.');

            $method = $this->normalizeOptionalText($data['method'] ?? 'email');
            $recipient = $this->normalizeRequiredText($data['recipient'] ?? null, 'No se recibio información del destinatario.');

            if($method == 'email') {
                if (!filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
                    throw new InvalidArgumentException('El correo electrónico no es válido.');
                }
            }

            $datetimeService = new DateTimeService($data['timezone']);

            $organization_data = $this->mediaRepository->findByOrganizationType([
                'type'                          => 'ORGANIZATION_LOGO',
                'organization'                  => $organizationId,
            ]);

            $payment_data = $this->paymentsRepository->getPayment([
                'uuid'                          => $this->uuidStringtoBinary($uuid),
                'organization'                  => $organizationId
            ]);

            if(!$payment_data)
                throw new RuntimeException("Ocurrio un error al intentar obtener la información.");

            $payment_details_data = $this->paymentsRepository->getPaymentDetails([
                'uuid'                          => $this->uuidStringtoBinary($uuid),
                'organization'                  => $organizationId
            ]);

            $payment_details = array();
            foreach($payment_details_data as $pd) {
                array_push($payment_details, array(
                    'id'                            => $this->uuidBinarytoString($pd['uuid']),
                    'type'                          => $pd['tipo'],
                    'description'                   => $pd['descripcion'],
                    'quantity'                      => $pd['cantidad'],
                    'base_cost'                     => $pd['precio_base'],
                    'subtotal'                      => $pd['subtotal'],
                    'discount'                      => $pd['descuento'],
                    'taxes'                         => $pd['impuestos'],
                    'total'                         => $pd['total'],
                    'paid'                          => $pd['pagado'],
                    'current_balance_due'           => $pd['adeudo'],
                    'balance_before_payment'        => $pd['adeudo_anterior'],
                    'payment_amount'                => $pd['monto_pago'],
                    'balance_after_payment'         => $pd['adeudo_actual'],
                ));
            }

            /**
             * AGREGAR CODIGO PARA ENVIO DE COMPROBANTE, POR AHORA SOLO EMAIL DESPUES AGREGAREMOS PARA WHATSAPP
             */
            if ($method !== 'email') {
                throw new InvalidArgumentException(
                    'El método de envío todavía no está disponible.'
                );
            }

            /*
            * Aquí debes usar los nombres exactos que devuelve getPayment().
            * Dejé alternativas en español/inglés para mostrarte el mapeo.
            */
            $receipt = [
                'id' => $uuid,

                'folio' => $payment_data['folio'],

                'payment_date' =>
                    $datetimeService->fromUtcFormatted($payment_data['f_pago']),

                'client' =>
                    $payment_data['cliente']
                    ?? 'Público en general',

                'payment_method' =>
                    $payment_data['metodo_pago']
                    ?? '',

                'amount_payment' =>
                    $payment_data['monto_pago']
                    ?? 0,

                'details' => $payment_details,
            ];

            /*
            * Generamos el QR como PNG binario, no como data URI.
            */
            $qrPayload = json_encode([
                'type' => 'receipt',
                'uuid' => $uuid,
            ], JSON_UNESCAPED_SLASHES | JSON_THROW_ON_ERROR);

            $qrOptions = new QROptions([
                'outputInterface' => QRGdImagePNG::class,
                'outputBase64'    => false,
                'scale'           => 6,
            ]);

            $qrBinary = (new QRCode($qrOptions))->render($qrPayload);

            /*
            * Localizamos el logotipo físicamente dentro de /storage.
            */
            $logoPath = null;
            $logoMime = null;

            $storageRoot = realpath(STORAGE_PATH);

            $logoRelativePath =
                $organization_data['ruta_thumbnail']
                ?? $organization_data['ruta']
                ?? null;

            if ($storageRoot !== false && $logoRelativePath !== null) {
                $candidatePath = realpath(
                    rtrim($storageRoot, '/\\')
                    .DIRECTORY_SEPARATOR
                    .ltrim($logoRelativePath, '/\\')
                );

                if (
                    $candidatePath !== false &&
                    str_starts_with(
                        $candidatePath,
                        $storageRoot.DIRECTORY_SEPARATOR
                    ) &&
                    is_file($candidatePath) &&
                    is_readable($candidatePath)
                ) {
                    $detectedMime = (new \finfo(
                        FILEINFO_MIME_TYPE
                    ))->file($candidatePath);

                    if (in_array($detectedMime, [
                        'image/png',
                        'image/jpeg',
                        'image/gif',
                    ], true)) {
                        $logoPath = $candidatePath;
                        $logoMime = $detectedMime;
                    }
                }
            }

            $templatePath = dirname(__DIR__).
                '/Views/emails/payment-receipt.php';

            $html = $this->renderEmailTemplate($templatePath, [
                'receipt' => $receipt,
                'hasLogo' => $logoPath !== null,
            ]);

            $embeddedImages = [
                [
                    'content' => $qrBinary,
                    'cid'     => 'receipt_qr',
                    'name'    => 'receipt-qr.png',
                    'mime'    => 'image/png',
                ],
            ];

            if ($logoPath !== null) {
                $embeddedImages[] = [
                    'path' => $logoPath,
                    'cid'  => 'organization_logo',
                    'name' => basename($logoPath),
                    'mime' => $logoMime,
                ];
            }

            $mailService = new MailService();

            $delivery = $mailService->send([
                'to'      => $recipient,
                'subject' => 'Recibo de pago '.$receipt['folio'],
                'html'    => $html,

                'text' =>
                    "Recibo de pago {$receipt['folio']}\n".
                    "Cliente: {$receipt['client']}\n".
                    "Fecha: {$receipt['payment_date']}\n".
                    "Método de pago: {$receipt['payment_method']}\n".
                    'Total pagado: $'.
                    number_format(
                        (float) $receipt['amount_payment'],
                        2
                    ),

                'embeddedImages' => $embeddedImages,
            ]);

            return [
                'id'        => $uuid,
                'method'    => 'email',
                'recipient' => $recipient,
                'sent'      => $delivery['accepted'],
                'messageId' => $delivery['messageId'],
            ];
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    private function renderEmailTemplate(
        string $template,
        array $variables
    ): string {
        if (!is_file($template)) {
            throw new RuntimeException(
                'No se encontró la plantilla del correo.'
            );
        }

        extract($variables, EXTR_SKIP);

        ob_start();

        try {
            require $template;

            return (string) ob_get_clean();
        } catch (\Throwable $e) {
            ob_end_clean();
            throw $e;
        }
    }

    function delete($data): void {
        try {
            $uid = $this->normalizeRequiredInt($data['uid'] ?? null, 'No existe una sesion activa.');
            $organizationId = $this->normalizeRequiredInt($data['organizationId'] ?? null, 'No se encontraron datos de su empresa.');
            $branchId = $this->normalizeRequiredInt($data['branchId'] ?? null, 'No se encontraron datos de una sucursal.');

            $uuid = $this->normalizeRequiredText($data['uuid'] ?? null, 'Error al recibir identificador de pago.');

            $reason = $this->normalizeOptionalText($data['reason'] ?? null);

            $payment_data = $this->paymentsRepository->getPayment([
                'uuid'                          => $this->uuidStringtoBinary($uuid),
                'organization'                  => $organizationId
            ]);

            if(!$payment_data)
                throw new RuntimeException("No se encontro información del pago.");

            if($payment_data['codigo_estatus_corte'] != 'open')
                throw new RuntimeException("El corte ya no se encuentra abierto, no es posible eliminar el pago.");

            if($payment_data['codigo_estatus_pago'] != 'active')
                throw new RuntimeException("El pago ya no se encuentra activo, no es posible eliminarlo.");

            $payment_cancelled_id = $this->paymentsStatusRepository->getIdByCode('cancelled');
            $cash_reconciliation_uuid = $payment_data['uuid_corte'];

            if($payment_data['codigo_metodo_pago'] == 'efectivo')
                $this->cashReconciliationRepository->cancelCashPayment([
                    'uuid'                              => $cash_reconciliation_uuid,
                    'cash'                              => $payment_data['monto_pago']
                ]);
            else
                $this->cashReconciliationRepository->cancelOtherPayment([
                    'uuid'                              => $cash_reconciliation_uuid,
                    'amount'                            => $payment_data['monto_pago']
                ]);
            
            $this->paymentsRepository->delete([
                'uuid'                                  => $this->uuidStringToBinary($uuid),
                'organization'                          => $organizationId,
                'status'                                => $payment_cancelled_id,
                'reason'                                => $reason,
                'uid'                                   => $uid,
            ]);
        } catch(\Throwable $e) {
            throw $e;
        }
    }
}