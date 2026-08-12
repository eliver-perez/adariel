<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Service;
use App\Repositories\POSRepository;
use App\Repositories\SalesRepository;
use App\Repositories\SalesStatusRepository;
use App\Repositories\PaymentsRepository;
use App\Repositories\PaymentsMethodsRepository;
use App\Repositories\ClientsRepository;
use App\Repositories\PatientsRepository;
use App\Repositories\CashReconciliationRepository;
use App\Repositories\ProductsRepository;
use App\Repositories\FoliosRepository;
use App\Repositories\SettingsRepository;
use App\Services\SettingsService;
use InvalidArgumentException;
use RuntimeException;

class POSService extends Service
{
    public function __construct(
        private POSRepository $posRepository,
        private SalesRepository $salesRepository,
        private SalesStatusRepository $salesStatusRepository,
        private PaymentsRepository $paymentsRepository,
        private PaymentsMethodsRepository $paymentsMethodsRepository,
        private ClientsRepository $clientsRepository,
        private PatientsRepository $patientsRepository,
        private CashReconciliationRepository $cashReconciliationRepository,
        private ProductsRepository $productsRepository,
        private FoliosRepository $foliosRepository,
        private SettingsRepository $settingsRepository
    ) {
    }

    public function getCart() {
        if(!isset($_SESSION['cart']))
            $this->createEmptyCart();
        return $_SESSION['cart'];
    }

    public function updateCart(array $data) {
        $uid = $this->normalizeRequiredInt($data['uid'] ?? null, 'No existe una sesion activa.');
        $organizationId = $this->normalizeRequiredInt($data['organizationId'] ?? null, 'No se encontraron datos de su empresa.');
        $branchId = $this->normalizeRequiredInt($data['branchId'] ?? null, 'No se encontraron datos de una sucursal.');

        if(!isset($_SESSION['cart']))
            $this->createEmptyCart();
        switch($data['action']) {
            case 'add_sale':
                if(!$this->saleExistsInCart($data['data']->sale)) {
                    $sale_data = $this->addSaleToCart([
                        'organization'              => $organizationId,
                        'branch'                    => $branchId,
                        'sale'                      => $data['data']->sale
                    ]);
                    if($_SESSION['cart']['client'] == null) {
                        $_SESSION['cart']['client'] = $sale_data['client_id'];
                        $_SESSION['cart']['client_name'] = $sale_data['client'];
                    }
                    if($_SESSION['cart']['patient'] == null) {
                        $_SESSION['cart']['patient'] = $sale_data['patient_id'];
                        $_SESSION['cart']['patient_name'] = $sale_data['patient'];
                    }
                    return [
                        'id'                        => $_SESSION['cart']['id'],
                        'client'                    => $_SESSION['cart']['client'],
                        'client_name'               => $_SESSION['cart']['client_name'],
                        'patient'                   => $_SESSION['cart']['patient'],
                        'patient_name'              => $_SESSION['cart']['patient_name'],
                        'coupon'                    => $_SESSION['cart']['coupon'],
                        'discount'                  => $_SESSION['cart']['discount'],
                        'subtotal'                  => $_SESSION['cart']['subtotal'],
                        'taxes'                     => $_SESSION['cart']['taxes'],
                        'total'                     => $_SESSION['cart']['total'],
                        'balance_due'               => $_SESSION['cart']['balance_due'],
                        'sale'                      => $sale_data,
                    ];
                } else {
                    throw new RuntimeException("La venta ya se encuentra en el carrito.");
                }
                break;
            case 'delete_sale':
                if($this->saleExistsInCart($data['data']->sale)) {
                    $this->removeSaleFromCart($data['data']->sale);
                    if($_SESSION['cart']['balance_due'] == 0) {
                        $_SESSION['cart']['id'] = null;
                        $_SESSION['cart']['client'] = null;
                        $_SESSION['cart']['client_name'] = null;
                        $_SESSION['cart']['patient'] = null;
                        $_SESSION['cart']['patient_name'] = null;
                    }
                    return [
                        'id'                        => $_SESSION['cart']['id'],
                        'client'                    => $_SESSION['cart']['client'],
                        'client_name'               => $_SESSION['cart']['client_name'],
                        'patient'                   => $_SESSION['cart']['patient'],
                        'patient_name'              => $_SESSION['cart']['patient_name'],
                        'coupon'                    => $_SESSION['cart']['coupon'],
                        'discount'                  => $_SESSION['cart']['discount'],
                        'subtotal'                  => $_SESSION['cart']['subtotal'],
                        'taxes'                     => $_SESSION['cart']['taxes'],
                        'total'                     => $_SESSION['cart']['total'],
                        'balance_due'               => $_SESSION['cart']['balance_due'],
                        'sale'                      => $data['data']->sale,
                    ];
                } else {
                    throw new RuntimeException("La venta no se encuentra en el carrito.");
                }
                break;
            case 'select_client':
                $client_uuid = $this->uuidStringToBinary($data['data']->client);
                $client_name = $this->clientsRepository->getClientName([
                    'uuid'                          => $client_uuid,
                    'organization'                  => $organizationId
                ]);
                if($client_name != null) {
                    $_SESSION['cart']['client'] = $data['data']->client;
                    $_SESSION['cart']['client_name'] = $client_name;
                }
                return [
                    'client'                        => $_SESSION['cart']['client'],
                    'client_name'                   => $_SESSION['cart']['client_name'],
                ];
                break;
            case 'add_product':
                $product_data = $this->productsRepository->getProductData([
                    'uuid'                          => $this->uuidStringtoBinary($data['data']->product),
                    'organization'                  => $organizationId
                ]);
                if(!$this->productExistsInCart($data['data']->product)) {
                    $product = [
                        'id'                            => $this->uuidBinaryToString($product_data['uuid']),
                        'sale_id'                       => -1,
                        'sale_uuid'                     => '',
                        'code'                          => $product_data['clave'],
                        'name'                          => $product_data['nombre'],
                        'category'                      => $product_data['categoria'],
                        'unit_measure'                  => $product_data['unidad'],
                        'qty'                           => 1,
                        'unit_price'                    => $product_data['precio_total'],
                        'subtotal'                      => $product_data['precio_base'],
                        'tax_rate'                      => $product_data['porc_impuestos'],
                        'taxes'                         => $product_data['impuestos'],
                        'total'                         => $product_data['precio_total'],
                        'paid'                          => 0,
                        'discount'                      => 0,
                        'balance_due'                   => $product_data['precio_total'],
                    ];
                    array_push($_SESSION['cart']['products'], $product);
                    $add = 1;
                } else {
                    $product = $this->addProductQuantity($data['data']->product, $product_data, 1);
                    $add = 0;
                }
                $this->calculateTotal();
                return [
                    'id'                        => $_SESSION['cart']['id'],
                    'client'                    => $_SESSION['cart']['client'],
                    'client_name'               => $_SESSION['cart']['client_name'],
                    'patient'                   => $_SESSION['cart']['patient'],
                    'patient_name'              => $_SESSION['cart']['patient_name'],
                    'coupon'                    => $_SESSION['cart']['coupon'],
                    'discount'                  => $_SESSION['cart']['discount'],
                    'subtotal'                  => $_SESSION['cart']['subtotal'],
                    'taxes'                     => $_SESSION['cart']['taxes'],
                    'total'                     => $_SESSION['cart']['total'],
                    'balance_due'               => $_SESSION['cart']['balance_due'],
                    'product'                   => $product,
                    'add'                       => $add
                ];
                break;
            case 'change_product_qty':
                $product_data = $this->productsRepository->getProductData([
                    'uuid'                          => $this->uuidStringtoBinary($data['data']->product),
                    'organization'                  => $organizationId
                ]);
                if(!$this->productExistsInCart($data['data']->product)) {
                    $product = [
                        'id'                            => $this->uuidBinaryToString($product_data['uuid']),
                        'sale_id'                       => -1,
                        'sale_uuid'                     => '',
                        'code'                          => $product_data['clave'],
                        'name'                          => $product_data['nombre'],
                        'category'                      => $product_data['categoria'],
                        'unit_measure'                  => $product_data['unidad'],
                        'qty'                           => 1,
                        'unit_price'                    => $product_data['precio_total'],
                        'subtotal'                      => $product_data['precio_base'],
                        'tax_rate'                      => $product_data['porc_impuestos'],
                        'taxes'                         => $product_data['impuestos'],
                        'total'                         => $product_data['precio_total'],
                        'paid'                          => 0,
                        'discount'                      => 0,
                        'balance_due'                   => $product_data['precio_total'],
                    ];
                    array_push($_SESSION['cart']['products'], $product);
                    $add = 1;
                } else {
                    $product = $this->updateProductQuantity($data['data']->product, $product_data, $data['data']->qty);
                    $add = 0;
                }
                $this->calculateTotal();
                return [
                    'id'                        => $_SESSION['cart']['id'],
                    'client'                    => $_SESSION['cart']['client'],
                    'client_name'               => $_SESSION['cart']['client_name'],
                    'patient'                   => $_SESSION['cart']['patient'],
                    'patient_name'              => $_SESSION['cart']['patient_name'],
                    'coupon'                    => $_SESSION['cart']['coupon'],
                    'discount'                  => $_SESSION['cart']['discount'],
                    'subtotal'                  => $_SESSION['cart']['subtotal'],
                    'taxes'                     => $_SESSION['cart']['taxes'],
                    'total'                     => $_SESSION['cart']['total'],
                    'balance_due'               => $_SESSION['cart']['balance_due'],
                    'product'                   => $product,
                    'add'                       => $add
                ];
                break;
            case 'remove_product':
                if($this->productExistsInCart($data['data']->product)) {
                    $this->removeProductFromCart($data['data']->product);
                    return [
                        'id'                        => $_SESSION['cart']['id'],
                        'client'                    => $_SESSION['cart']['client'],
                        'client_name'               => $_SESSION['cart']['client_name'],
                        'patient'                   => $_SESSION['cart']['patient'],
                        'patient_name'              => $_SESSION['cart']['patient_name'],
                        'coupon'                    => $_SESSION['cart']['coupon'],
                        'discount'                  => $_SESSION['cart']['discount'],
                        'subtotal'                  => $_SESSION['cart']['subtotal'],
                        'taxes'                     => $_SESSION['cart']['taxes'],
                        'total'                     => $_SESSION['cart']['total'],
                        'balance_due'               => $_SESSION['cart']['balance_due'],
                        'product'                      => $data['data']->product,
                    ];
                } else {
                    throw new RuntimeException("El producto no se encuentra en el carrito.");
                }
                break;
        }
    }

    public function clearCartSales() {
        if(isset($_SESSION['cart']['sales']))
            $_SESSION['cart']['sales'] = [];
    }

    public function addSaleToCart(array $data) {
        $sale_data = $this->salesRepository->getSaleData([
            'uuid'                              => $this->uuidStringToBinary($data['sale']),
            'branch'                            => $data['branch']
        ]);
        if($sale_data['adeudo'] > 0) {
            $data = $this->salesRepository->getSaleDetails([
                'uuid'                          => $this->uuidStringToBinary($data['sale']),
                'branch'                        => $data['branch']
            ]);
            $sale_details = array();
            foreach($data as $sd) {
                array_push($sale_details, array(
                    'id'                                => $this->uuidBinaryToString($sd['uuid']),
                    'service_id'                        => $this->uuidBinaryToString($sd['servicio_uuid']),
                    'service'                           => $sd['servicio'],
                    'product_id'                        => $sd['producto_uuid'],
                    'product_code'                      => $sd['producto_codigo'],
                    'product'                           => $sd['producto'],
                    'description'                       => $sd['descripcion'],
                    'quantity'                          => $sd['cantidad'],
                    'unit_price'                        => $sd['precio_base'],
                    'subtotal'                          => $sd['subtotal'],
                    'taxes'                             => $sd['impuestos'],
                    'total'                             => $sd['total'],
                    'discount'                          => $sd['descuento'],
                    'paid'                              => $sd['pagado'],
                    'balance_due'                       => $sd['adeudo'],
                ));
            }
            $sale_element = [
                'id'                                    => $this->uuidBinaryToString($sale_data['uuid']),
                'folio'                                 => $sale_data['folio'],
                'client_id'                             => $this->uuidBinaryToString($sale_data['cliente_uuid']),
                'client'                                => $sale_data['cliente'],
                'patient_id'                            => $this->uuidBinaryToString($sale_data['paciente_uuid']),
                'patient'                               => $sale_data['paciente'],
                'subtotal'                              => $sale_data['subtotal'],
                'taxes'                                 => $sale_data['impuestos'],
                'total'                                 => $sale_data['total'],
                'discount'                              => $sale_data['descuento'],
                'paid'                                  => $sale_data['pagado'],
                'balance_due'                           => $sale_data['adeudo'],
                'observations'                          => $sale_data['observaciones'],
                'details'                               => $sale_details,
            ];
            array_push($_SESSION['cart']['sales'], $sale_element);
            $this->calculateTotal();
            return $sale_element;
        } else {
            throw new RuntimeException("La venta no tiene adeudo pendiente.");
        }
    }

    function removeSaleFromCart($id) {
        $_SESSION['cart']['sales'] = array_filter($_SESSION['cart']['sales'], fn($item) => $item['id'] !== $id);

        $_SESSION['cart']['sales'] = array_values($_SESSION['cart']['sales']);
        $this->calculateTotal();
    }

    function removeProductFromCart($id) {
        $_SESSION['cart']['products'] = array_filter($_SESSION['cart']['products'], fn($item) => $item['id'] !== $id);

        $_SESSION['cart']['products'] = array_values($_SESSION['cart']['products']);
        $this->calculateTotal();
    }

    public function calculateTotal() {
        if(isset($_SESSION['cart'])) {
            $sales_balance_due = 0;
            foreach($_SESSION['cart']['sales'] as $cs) {
                $sales_balance_due += $cs['balance_due'];
            }
            $products_total = 0;
            foreach($_SESSION['cart']['products'] as $cp) {
                $products_total += $cp['total'];
            }
            $total = $sales_balance_due + $products_total;
            $_SESSION['cart']['subtotal'] = $total;
            $_SESSION['cart']['total'] = $total;
            $_SESSION['cart']['balance_due'] = $total;
        }
    }

    public function productExistsInCart($id) {
        if(isset($_SESSION['cart']))
            foreach($_SESSION['cart']['products'] as $cp) {
                if($cp['id'] == $id)
                    return true;
            }
        return false;
    }

    public function addProductQuantity($id, $data, $qty) {
        for($i = 0; $i < count($_SESSION['cart']['products']); $i++) {
            if($_SESSION['cart']['products'][$i]['id'] == $id) {
                $_SESSION['cart']['products'][$i]['qty'] += $qty;
                $_SESSION['cart']['products'][$i]['total'] = round($_SESSION['cart']['products'][$i]['unit_price'] *  $_SESSION['cart']['products'][$i]['qty'], 2);
                if($_SESSION['cart']['products'][$i]['tax_rate'] > 0) {
                    $_SESSION['cart']['products'][$i]['subtotal'] = round($_SESSION['cart']['products'][$i]['total'] / ($_SESSION['cart']['products'][$i]['tax_rate'] / 100 + 1), 2);
                    $_SESSION['cart']['products'][$i]['taxes'] = $_SESSION['cart']['products'][$i]['total'] - $_SESSION['cart']['products'][$i]['subtotal'];
                } else {
                    $_SESSION['cart']['products'][$i]['subtotal'] = $_SESSION['cart']['products'][$i]['total'];
                    $_SESSION['cart']['products'][$i]['taxes'] = 0;
                }
                $_SESSION['cart']['products'][$i]['balance_due'] = $_SESSION['cart']['products'][$i]['total'];
                return $_SESSION['cart']['products'][$i];
            }
        }
    }

    public function updateProductQuantity($id, $data, $qty) {
        for($i = 0; $i < count($_SESSION['cart']['products']); $i++) {
            if($_SESSION['cart']['products'][$i]['id'] == $id) {
                $_SESSION['cart']['products'][$i]['qty'] = $qty;
                $_SESSION['cart']['products'][$i]['total'] = round($_SESSION['cart']['products'][$i]['unit_price'] *  $_SESSION['cart']['products'][$i]['qty'], 2);
                if($_SESSION['cart']['products'][$i]['tax_rate'] > 0) {
                    $_SESSION['cart']['products'][$i]['subtotal'] = round($_SESSION['cart']['products'][$i]['total'] / ($_SESSION['cart']['products'][$i]['tax_rate'] / 100 + 1), 2);
                    $_SESSION['cart']['products'][$i]['taxes'] = $_SESSION['cart']['products'][$i]['total'] - $_SESSION['cart']['products'][$i]['subtotal'];
                } else {
                    $_SESSION['cart']['products'][$i]['subtotal'] = $_SESSION['cart']['products'][$i]['total'];
                    $_SESSION['cart']['products'][$i]['taxes'] = 0;
                }
                $_SESSION['cart']['products'][$i]['balance_due'] = $_SESSION['cart']['products'][$i]['total'];
                return $_SESSION['cart']['products'][$i];
            }
        }
    }

    public function saleExistsInCart($id) {
        if(isset($_SESSION['cart']))
            foreach($_SESSION['cart']['sales'] as $cs) {
                if($cs['id'] == $id)
                    return true;
            }
        return false;
    }

    public function addElementCart($id, $value) {
        if(!isset($_SESSION['cart']))
            $this->createEmptyCart();
        $_SESSION['cart'][$id] = $value;
    }

    public function createEmptyCart() {
        $_SESSION['cart'] = [
            'id'                    => null,
            'client'                => null,
            'client_name'           => '',
            'patient'               => null,
            'patient_name'          => '',
            'sales'                 => [],
            'products'              => [],
            'subtotal'              => 0,
            'taxes'                 => 0,
            'total'                 => 0,
            'coupon'                => null,
            'discount'              => 0,
            'balance_due'           => 0,
            'pay_details'           => [],
        ];
    }

    public function checkout(array $data) {
        $uid = $this->normalizeRequiredInt($data['uid'] ?? null, 'No existe una sesion activa.');
        $organizationId = $this->normalizeRequiredInt($data['organizationId'] ?? null, 'No se encontraron datos de su empresa.');
        $branchId = $this->normalizeRequiredInt($data['branchId'] ?? null, 'No se encontraron datos de una sucursal.');
        if($this->validateCart([
            'organization'          => $organizationId,
            'branch'                => $branchId,
            'cart'                  => $data['cart']
            ])) {
            $conn = $this->posRepository->getConnection();
            $conn->beginTransaction();

            try {
                $cashReconciliationUuid = $this->cashReconciliationRepository->verifyIfExistsOpen([
                    'uid'                           => $data['uid'],
                    'branch'                        => $branchId
                ]);
                if($cashReconciliationUuid == null) {
                    $_SESSION['cash_reconciliation'] = null;
                    throw new RuntimeException("No existe un corte activo");
                }
                
                if($this->uuidBinarytoString($cashReconciliationUuid) != $_SESSION['cash_reconciliation']['id']) {
                    $cashReconciliationData = $this->cashReconciliationRepository->getCashReconciliationData([
                        'uuid'                      => $cashReconciliationUuid,
                        'organization'              => $organizationId
                    ]);
                    $_SESSION['cash_reconciliation']['id'] = $this->uuidBinarytoString($cashReconciliationData['uuid']);
                    $_SESSION['cash_reconciliation']['opened_by'] = $this->uuidBinarytoString($cashReconciliationData['opened_by_id']);
                    $_SESSION['cash_reconciliation']['opened_by_name'] = $cashReconciliationData['opened_by_name'];
                    $_SESSION['cash_reconciliation']['opened_date'] = $cashReconciliationData['opened_date'];
                }

                $year = date('Y');
                $c_aux = $this->foliosRepository->getConsecutive([
                    'type'                                  => 'pago',
                    'branch'                                => $branchId,
                    'year'                                  => $year
                ]);
                if(!$c_aux) {
                    $c_aux = 0;
                    $this->foliosRepository->insertConsecutive([
                        'consecutive'                           => $c_aux,
                        'type'                                  => 'pago',
                        'branch'                                => $branchId,
                        'year'                                  => $year
                    ]);
                }
                $payment_consecutive = $c_aux + 1;
                $folio = 'P-' . str_pad((string) $payment_consecutive, 7, '0', STR_PAD_LEFT) . '/' . substr($year, -2);

                $pending_sale_status_id = $this->salesStatusRepository->getIdByCode('pendiente');
                $paid_sale_status_id = $this->salesStatusRepository->getIdByCode('pagado');


                if($_SESSION['cart']['client'] != null) {
                    $client_id = $this->clientsRepository->getClientId([
                        'uuid'                                      => $this->uuidStringtoBinary($_SESSION['cart']['client']),
                        'organization'                              => $organizationId
                    ]);
                } else {
                    $client_id = $this->clientsRepository->getDefaultClientId();
                }
                $cash_reconciliation_id = $this->cashReconciliationRepository->getCashReconciliationId([
                    'uuid'                                      => $this->uuidStringtoBinary($_SESSION['cash_reconciliation']['id']),
                    'branch'                                    => $branchId
                ]);

                $payment_method_id = $this->paymentsMethodsRepository->getPaymentMethodIdByCode($data['cart']->payment_method);

                $payment_uuid = $this->generateUuidBinary();
                $payment_amount = $data['cart']->payment_amount;
                $payment_id = $this->paymentsRepository->registerPayment([
                    'uuid'                                          => $payment_uuid,
                    'branch'                                        => $branchId,
                    'year'                                          => $year,
                    'folio'                                         => $folio,
                    'consecutive'                                   => $payment_consecutive,
                    'client'                                        => $client_id,
                    'cash_reconciliation'                           => $cash_reconciliation_id,
                    'payment_method'                                => $payment_method_id,
                    'reference'                                     => $data['cart']->payment_reference,
                    'balance_due_before'                            => $_SESSION['cart']['balance_due'],
                    'payment_amount'                                => $payment_amount,
                    'balance_due'                                   => $_SESSION['cart']['balance_due'] - $data['cart']->payment_amount,
                    'uid'                                           => $data['uid'],
                ]);
                $remaining_payment = $payment_amount;
                if(count($_SESSION['cart']['products']) > 0) {
                    $year = date('Y');
                    $c_aux = $this->foliosRepository->getConsecutive([
                        'type'                                  => 'venta',
                        'branch'                                => $branchId,
                        'year'                                  => $year
                    ]);
                    if(!$c_aux) {
                        $c_aux = 0;
                        $this->foliosRepository->insertConsecutive([
                            'consecutive'                           => $c_aux,
                            'type'                                  => 'venta',
                            'branch'                                => $branchId,
                            'year'                                  => $year
                        ]);
                    }
                    $sale_consecutive = $c_aux + 1;
                    $folio = 'V-' . str_pad((string) $sale_consecutive, 7, '0', STR_PAD_LEFT) . '/' . substr($year, -2);

                    $product_sale_subtotal = 0;
                    $product_sale_taxes = 0;
                    $product_sale_total = 0;
                    $product_sale_discount = 0;
                    $product_sale_paid = 0;
                    $product_sale_balance_due = 0;
                    $sale_pending_status = $this->salesStatusRepository->getIdByCode('pendiente');
                    $client_id = isset($_SESSION['cart']['client']) ? $this->clientsRepository->getClientId([
                        'uuid'                                      => $this->uuidStringtoBinary($_SESSION['cart']['client']),
                        'organization'                              => $organizationId
                    ]) : null;
                    $patient_id = isset($_SESSION['cart']['patient']) ? $this->patientsRepository->getPatientId([
                        'uuid'                                      => $this->uuidStringtoBinary($_SESSION['cart']['patient']),
                        'organization'                              => $organizationId
                    ]) : null;
                    foreach($_SESSION['cart']['products'] as $ps) {
                        $product_sale_subtotal = $ps['subtotal'];
                        $product_sale_taxes = $ps['taxes'];
                        $product_sale_total = $ps['total'];
                        $product_sale_balance_due = $ps['total'];
                    }
                    $product_sale_uuid = $this->generateUuidBinary();

                    $product_sale_id = $this->salesRepository->registerSale([
                        'uuid'                                      => $product_sale_uuid,
                        'branch'                                    => $branchId,
                        'year'                                      => $year,
                        'folio'                                     => $folio,
                        'consecutive'                               => $sale_consecutive,
                        'client'                                    => $client_id,
                        'patient'                                   => $patient_id,
                        'subtotal'                                  => $product_sale_subtotal,
                        'taxes'                                     => $product_sale_taxes,
                        'total'                                     => $product_sale_total,
                        'discount'                                  => $product_sale_discount,
                        'paid'                                      => $product_sale_paid,
                        'balance_due'                               => $product_sale_balance_due,
                        'status'                                    => $sale_pending_status,
                        'observations'                              => $data['observations'] ?? '',
                        'uid'                                       => $data['uid'],
                    ]);
                    $this->foliosRepository->updateConsecutive([
                        'consecutive'                           => $sale_consecutive,
                        'type'                                  => 'venta',
                        'branch'                                => $branchId,
                        'year'                                  => $year
                    ]);

                    foreach($_SESSION['cart']['products'] as $key => $ps) {
                        $product_sale_details_uuid = $this->generateUuidBinary();
                        $product_id = $this->productsRepository->getProductId([
                            'uuid'                              => $this->uuidStringtoBinary($ps['id']),
                            'organization'                      => $organizationId
                        ]);
                        $_SESSION['cart']['products'][$key]['sale_uuid'] = $product_sale_details_uuid;
                        $_SESSION['cart']['products'][$key]['sale_id'] = $this->salesRepository->registerSaleProductDetails([
                            'uuid'                                      => $product_sale_details_uuid,
                            'sale'                                      => $product_sale_id,
                            'product'                                   => $product_id,
                            'description'                               => $ps['name'],
                            'quantity'                                  => $ps['qty'],
                            'unit_price'                                => $ps['unit_price'],
                            'subtotal'                                  => $ps['subtotal'],
                            'taxes'                                     => $ps['taxes'],
                            'total'                                     => $ps['total'],
                            'discount'                                  => $ps['discount'],
                            'paid'                                      => $ps['paid'],
                            'balance_due'                               => $ps['balance_due'],
                            'observations'                              => $data['observations'] ?? '',
                            'uid'                                       => $data['uid'],
                        ]);
                    }

                    $payment_sale_uuid = $this->generateUuidBinary();
                    if($remaining_payment >= $product_sale_balance_due)
                        $product_payment_amount = $product_sale_balance_due;
                    else
                        $product_payment_amount = $remaining_payment;
                    $remaining_payment -= $product_payment_amount;
                    $remaining_sale_balance = $product_sale_balance_due - $product_payment_amount;
                    $sale_payment_id = $this->paymentsRepository->registerSalePayment([
                        'uuid'                                      => $payment_sale_uuid,
                        'payment'                                   => $payment_id,
                        'sale'                                      => $product_sale_uuid,
                        'balance_due_before'                        => $product_sale_balance_due,
                        'payment_amount'                            => $product_payment_amount,
                        'balance_due'                               => $remaining_sale_balance,
                    ]);
                    $this->salesRepository->updateSaleBalance([
                        'sale'                                      => $product_sale_uuid,
                        'payment_amount'                            => $product_payment_amount,
                        'balance_due'                               => $remaining_sale_balance,
                        'status'                                    => $remaining_sale_balance > 0 ? $pending_sale_status_id : $paid_sale_status_id,
                    ]);

                    $remaining_sale_payment = $product_payment_amount;
                    foreach($_SESSION['cart']['products'] as $ps) {
                        if($ps['balance_due'] > 0) {
                            if($remaining_sale_payment >= $ps['balance_due'])
                                $sale_detail_payment_amount = $ps['balance_due'];
                            else
                                $sale_detail_payment_amount = $remaining_sale_payment;
                            $remaining_sale_payment -= $sale_detail_payment_amount;
                            $remaining_sale_detail_balance = $ps['balance_due'] - $sale_detail_payment_amount;
                            $payment_sale_detail_uuid = $this->generateUuidBinary();
                            $this->paymentsRepository->registerSaleDetailPayment([
                                'uuid'                                      => $payment_sale_detail_uuid,
                                'payment'                                   => $payment_id,
                                'sale_detail'                               => $ps['sale_uuid'],
                                'balance_due_before'                        => $ps['balance_due'],
                                'payment_amount'                            => $sale_detail_payment_amount,
                                'balance_due'                               => $remaining_sale_detail_balance,
                            ]);
                            $this->salesRepository->updateSaleDetailBalance([
                                'sale'                                      => $ps['sale_uuid'],
                                'payment_amount'                            => $sale_detail_payment_amount,
                                'balance_due'                               => $remaining_sale_detail_balance,
                            ]);
                        }
                    }
                }

                foreach($_SESSION['cart']['sales'] as $cs) {
                    if($cs['balance_due'] > 0) {
                        if($remaining_payment >= $cs['balance_due'])
                            $sale_payment_amount = $cs['balance_due'];
                        else
                            $sale_payment_amount = $remaining_payment;
                        $remaining_payment -= $sale_payment_amount;
                        $remaining_sale_balance = $cs['balance_due'] - $sale_payment_amount;
                        $payment_sale_uuid = $this->generateUuidBinary();
                        $sale_payment_id = $this->paymentsRepository->registerSalePayment([
                            'uuid'                                      => $payment_sale_uuid,
                            'payment'                                   => $payment_id,
                            'sale'                                      => $this->uuidStringtoBinary($cs['id']),
                            'balance_due_before'                        => $cs['balance_due'],
                            'payment_amount'                            => $sale_payment_amount,
                            'balance_due'                               => $remaining_sale_balance,
                        ]);
                        $this->salesRepository->updateSaleBalance([
                            'sale'                                      => $this->uuidStringtoBinary($cs['id']),
                            'payment_amount'                            => $sale_payment_amount,
                            'balance_due'                               => $remaining_sale_balance,
                            'status'                                    => $remaining_sale_balance > 0 ? $pending_sale_status_id : $paid_sale_status_id,
                        ]);
                        $remaining_sale_payment = $sale_payment_amount;
                        foreach($cs['details'] as $d) {
                            if($d['balance_due'] > 0) {
                                if($remaining_sale_payment >= $d['balance_due'])
                                    $sale_detail_payment_amount = $d['balance_due'];
                                else
                                    $sale_detail_payment_amount = $remaining_sale_payment;
                                $remaining_sale_payment -= $sale_detail_payment_amount;
                                $remaining_sale_detail_balance = $d['balance_due'] - $sale_detail_payment_amount;
                                $payment_sale_detail_uuid = $this->generateUuidBinary();
                                $this->paymentsRepository->registerSaleDetailPayment([
                                    'uuid'                                      => $payment_sale_detail_uuid,
                                    'payment'                                   => $payment_id,
                                    'sale_detail'                               => $this->uuidStringtoBinary($d['id']),
                                    'balance_due_before'                        => $d['balance_due'],
                                    'payment_amount'                            => $sale_detail_payment_amount,
                                    'balance_due'                               => $remaining_sale_detail_balance,
                                ]);
                                $this->salesRepository->updateSaleDetailBalance([
                                    'sale'                                      => $this->uuidStringtoBinary($d['id']),
                                    'payment_amount'                            => $sale_detail_payment_amount,
                                    'balance_due'                               => $remaining_sale_detail_balance,
                                ]);
                            }
                        }
                    }
                }

                if($data['cart']->payment_method == 'efectivo')
                    $this->cashReconciliationRepository->updateCashReconciliationCash([
                        'uuid'                                          => $this->uuidStringtoBinary($_SESSION['cash_reconciliation']['id']),
                        'cash'                                          => $payment_amount,
                    ]);
                else
                    $this->cashReconciliationRepository->updateCashReconciliationOther([
                        'uuid'                                          => $this->uuidStringtoBinary($_SESSION['cash_reconciliation']['id']),
                        'amount'                                        => $payment_amount,
                    ]);
                $this->foliosRepository->updateConsecutive([
                    'consecutive'                           => $payment_consecutive,
                    'type'                                  => 'pago',
                    'branch'                                => $branchId,
                    'year'                                  => $year
                ]);
                $this->createEmptyCart();
                $conn->commit();
            } catch (\Throwable $e) {
                if ($conn->inTransaction()) {
                    $conn->rollBack();
                }
                throw $e;
            }
        }
    }

    private function validateCart(array $data): bool {
        if($data['cart']->client != $_SESSION['cart']['client'])
            throw new RuntimeException("Los datos del cliente seleccionado no coinciden.");
        if($data['cart']->patient != $_SESSION['cart']['patient'])
            throw new RuntimeException("Los datos del paciente seleccionado no coinciden.");
        if(floatval($data['cart']->balance_due) != floatval($_SESSION['cart']['balance_due']))
            throw new RuntimeException('El adeudo no coincide.');
        if(floatval($data['cart']->payment_amount) > floatval($_SESSION['cart']['balance_due']))
            throw new RuntimeException("El pago no puede ser mayor al adeudo.");
        if(floatval($_SESSION['cart']['balance_due']) <= 0)
            throw new RuntimeException("El adeudo debe de ser mayor a 0.");
        if(floatval($data['cart']->payment_amount) <= 0)
            throw new RuntimeException("El pago debe de ser mayor a 0.");
        foreach($data['cart']->sales as $cs) {
            $found = false;
            foreach($_SESSION['cart']['sales'] as $session_cs) {
                if($session_cs['id'] == $cs->id) {
                    if($session_cs['balance_due'] != $cs->balance_due)
                        throw new RuntimeException("El monto de adeudos de las consultas no coincide.");
                    $balance = $this->salesRepository->getSaleBalanceDue([
                        'uuid'                          => $this->uuidStringtoBinary($cs->id),
                        'branch'                        => $data['branch']
                    ]);
                    if($balance != $session_cs['balance_due'])
                        throw new RuntimeException("Hubo cambios en los adeudos, inicia la venta de nuevo.");
                    $found = true;
                }
            }
            if(!$found)
                throw new RuntimeException("Las consultas agregadas no coinciden.");
        }
        $products_balance = 0;
        foreach($data['cart']->products as $ps) {
            $found = false;
            foreach($_SESSION['cart']['products'] as $session_ps) {
                if($session_ps['id'] == $ps->id) {
                    if($session_ps['total'] != $ps->total)
                        throw new RuntimeException("El monto de los productos no coincide.");
                    $product_data = $this->productsRepository->getProductData([
                        'uuid'                          => $this->uuidStringtoBinary($ps->id),
                        'organization'                  => $data['organization']
                    ]);
                    if($product_data['precio_total'] != $session_ps['unit_price'])
                        throw new RuntimeException("Hubo cambios en los costos del producto ".$session_ps['name'].", inicia la venta de nuevo.");
                    if(round($product_data['precio_total'] * $session_ps['qty'], 2) != $session_ps['total'])
                        throw new RuntimeException("Ocurrio un error al validar el costo del producto ".$product_data['name'].".");
                    $products_balance += $session_ps['total'];
                    $found = true;
                }
            }
            if(!$found)
                throw new RuntimeException("Los productos agregados no coinciden.");
        }
        if($data['cart']->payment_amount < $products_balance) 
            throw new RuntimeException("Es necesario que el pago cubra el monto de los productos agregados");
        return true;
    }
}