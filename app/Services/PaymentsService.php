<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Service;
use App\Core\DateTimeService;
use App\Repositories\CashReconciliationRepository;
use App\Repositories\CashReconciliationStatusRepository;
use App\Repositories\CashRegisterRepository;
use App\Repositories\PaymentsRepository;
use App\Repositories\SettingsRepository;
use App\Services\SettingsService;
use InvalidArgumentException;
use RuntimeException;

class PaymentsService extends Service
{
    public function __construct(
        private PaymentsRepository $paymentsRepository,
        private CashReconciliationRepository $cashReconciliationRepository,
        private CashReconciliationStatusRepository $cashReconciliationStatusRepository,
        private CashRegisterRepository $cashRegisterRepository,
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
                'consecutive'                       => $payment_data['consecutivo'],
                'client'                            => $payment_data['cliente'],
                'payment_method'                    => $payment_data['metodo_pago'],
                'amount_payment'                    => $payment_data['monto_pago'],
                'registered_by'                     => $payment_data['registro'],
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
}