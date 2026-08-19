<?php

declare(strict_types=1);

namespace App\Services;

use App\Core\Service;
use App\Core\DateTimeService;
use App\Repositories\ProductsRepository;
use App\Services\SettingsService;
use InvalidArgumentException;
use RuntimeException;

class ProductsService extends Service
{
    public function __construct(
        private ProductsRepository $productsRepository
    ) {
    }

    public function getAll(array $data): array {
        try {
            $uid = $this->normalizeRequiredInt($data['uid'] ?? null, 'No existe una sesion activa.');
            $organizationId = $this->normalizeRequiredInt($data['organizationId'] ?? null, 'No se encontraron registros de su empresa.');

            $datetimeService = new DateTimeService($data['timezone']);
            
            $products_data = $this->productsRepository->getAll([
                'organizationId'                => $organizationId,
                'search'                        => $data['search'],
                'limit'                         => $data['limit'],
                'offset'                        => $data['offset'],
            ]);
            $products = array();

            foreach($products_data as $d) {
                array_push($products, array(
                    'id'                        => $this->uuidBinaryToString($d['uuid']),
                    'code'                      => $d['clave'],
                    'product'                   => $d['nombre'],
                    'category'                  => $d['categoria'],
                    'unit_measure'              => $d['unidad'],
                    'total_cost'                => $d['precio_total'],
                    'enabled_sale'              => $d['habilitado_venta'],
                    'registered_by'             => $d['registro'],
                    'registered_date'           => $datetimeService->fromUtcFormatted($d['f_registro']),
                ));
            }

            return $products;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    function getProduct($data): ?array {
        try {
            $uid = $this->normalizeRequiredInt($data['uid'] ?? null, 'No existe una sesion activa.');
            $organizationId = $this->normalizeRequiredInt($data['organizationId'] ?? null, 'No se encontraron datos de su empresa.');

            $datetimeService = new DateTimeService($data['timezone']);

            $uuid = $this->normalizeRequiredText(
                $data['uuid'] ?? null,
                'Error al recibir identificador del producto.'
            );

            $product_data = $this->productsRepository->getProductData([
                'uuid'                          => $this->uuidStringtoBinary($uuid),
                'organization'                  => $organizationId
            ]);

            if(!$product_data)
                throw new RuntimeException("Ocurrio un error al intentar obtener la información.");
            
            return [
                'id'                                => $this->uuidBinarytoString($product_data['uuid']),
                'code'                              => $product_data['clave'],
                'bar_code'                          => $product_data['codigo_barras'],
                'unit'                              => $product_data['unidadId'],
                'category'                          => $product_data['categoriaId'],
                'name'                              => $product_data['nombre'],
                'description'                       => $product_data['unidad'],
                'base_cost'                         => $product_data['precio_base'],
                'tax_rate'                          => $product_data['porc_impuestos'],
                'taxes'                             => $product_data['impuestos'],
                'total_cost'                        => $product_data['precio_total'],
                'sale_enabled'                      => $product_data['habilitado_venta'],
                'registered_by'                     => $product_data['registro'],
                'registered_date'                   => $datetimeService->fromUtcFormatted($product_data['f_registro']),
            ];
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function getCategories(array $data): array {
        try {
            $uid = $this->normalizeRequiredInt($data['uid'] ?? null, 'No existe una sesion activa.');
            $organizationId = $this->normalizeRequiredInt($data['organizationId'] ?? null, 'No se encontraron registros de su empresa.');

            $data = $this->productsRepository->getCategories([
                'organizationId'                => $organizationId,
            ]);
            $categories = array();
            foreach($data as $d) {
                array_push($categories, array(
                    'organizationId'            => $organizationId,
                    'id'                        => $this->uuidBinaryToString($d['uuid']),
                    'category'                  => $d['categoria']
                ));
            }

            return $categories;
        } catch (\Throwable $e) {
            throw $e;
        }
    }

    public function create(array $data): ?string {
        $uid = $this->normalizeRequiredInt($data['uid'] ?? null, 'No existe una sesion activa.');
        $organizationId = $this->normalizeRequiredInt($data['organizationId'] ?? null, 'No se encontraron registros de su empresa.');
        $code = $this->normalizeOptionalText(trim($data['code'] ?? ''));
        $bar_code = $this->normalizeOptionalText(trim($data['bar_code'] ?? ''));
        $category = $this->normalizeRequiredText($data['category'] ?? null, 'Es necesario seleccionar una categoria.');
        $product = $this->normalizeRequiredText($data['product'] ?? null, 'Es necesario capturar el nombre del producto.');
        $description = $this->normalizeOptionalText(trim($data['description'] ?? ''));
        $unit_measure = $this->normalizeRequiredText($data['unit_measure'] ?? null, 'Es necesario seleccionar la unidad de medida.');
        $base_cost = $this->normalizeRequiredFloat($data['base_cost'] ?? null, 'Es necesario capturar el costo base.');
        $tax_rate = $this->normalizeRequiredFloat($data['tax_rate'] ?? null, 'Es necesario capturar el porcentaje de impuestos.', true);
        $taxes = $this->normalizeRequiredFloat($data['taxes'] ?? null, 'Es necesario capturar el porcentaje de impuestos.', true);
        $total_cost = $this->normalizeRequiredFloat($data['total_cost'] ?? null, 'Es necesario capturar el precio del producto.');
        $enable_sale = $this->normalizeRequiredInt($data['enable_sale'] ?? null, 'Es necesario seleccionar si se habilita para venta.');

        $categoryId = $this->productsRepository->getProductCategoryId($this->uuidStringToBinary($category));
        if($categoryId == null)
            throw new \RuntimeException('Ocurrio un error con la categoria.');

        $conn = $this->productsRepository->getConnection();
        $conn->beginTransaction();
        try {
            $productUuid = $this->generateUuidBinary();
            $productId = $this->productsRepository->insert([
                'uuid'                          => $productUuid,
                'organizationId'                => $organizationId,
                'code'                          => $code,
                'bar_code'                      => $bar_code,
                'product'                       => $product,
                'product_short'                 => strlen($product) > 32 ? substr($product, 0, 32) : $product,
                'category'                      => $categoryId,
                'description'                   => $description,
                'unit_measure'                  => $unit_measure,
                'base_cost'                     => $base_cost,
                'tax_rate'                      => $tax_rate,
                'taxes'                         => $taxes,
                'total_cost'                    => $total_cost,
                'enable_sale'                   => $enable_sale,
                'uid'                           => $uid,
            ]);
            
            $productCostUuid = $this->generateUuidBinary();
            $productCost = $this->productsRepository->insertCost([
                'uuid'                          => $productCostUuid,
                'product'                       => $productId,
                'tax_profile'                   => 1,
                'base_cost'                     => $base_cost,
                'tax_rate'                      => $tax_rate,
                'taxes'                         => $taxes,
                'total_cost'                    => $total_cost,
                'uid'                           => $uid
            ]);
            
            $conn->commit();
            
            return $this->uuidBinaryToString($productUuid);
        } catch (\Throwable $e) {
            if ($conn->inTransaction()) {
                $conn->rollBack();
            }
            throw $e;
        }
    }
}