<?php
    $title = "Productos";
    $section = "Productos";

    require_once __DIR__.'/../layout/title.php';
?>

<div class="sm:grid sm:grid-cols-12 max-sm:flex max-sm:flex-col gap-[25px]">
    <div class="col-span-12 2xl:col-span-8">
        <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative h-full">
            <div class="px-[25px] text-dark dark:text-title-dark font-medium text-[17px] flex flex-wrap items-center justify-between max-sm:flex-col max-sm:h-auto max-sm:mb-[15px]">
                <h2 class="mb-0 inline-flex items-center py-[16px] max-sm:pb-[5px] overflow-hidden whitespace-nowrap text-ellipsis text-[18px] font-semibold text-dark dark:text-title-dark  capitalize">
                    Productos
                </h2>
            </div>
            
            <div class="p-[25px] pt-0">
                <div class="scrollbar overflow-y-auto" style="max-height: 420px">
                    <div data-te-tab-active class="hidden opacity-100 transition-opacity duration-150 ease-linear data-[te-tab-active]:block">
                        <table class="min-w-full text-sm font-light text-left whitespace-nowrap" id="table-products">
                            <thead>
                                <tr>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-start text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden rounded-s-[4px]">
                                        CLAVE</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        PRODUCTO</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        CATEGORÍA</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        UNIDAD</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        PRECIO</th>
                                    <th class="bg-regularBG dark:bg-box-dark-up px-4 py-2.5 text-light dark:text-title-dark text-[12px] font-medium border-none before:hidden">
                                        VENTA</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-box-dark">
                                
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-span-12 2xl:col-span-4">
        <div class="bg-white dark:bg-box-dark m-0 p-0 text-body dark:text-subtitle-dark text-[15px] rounded-10 relative h-full">
            <div class="px-[25px] text-dark dark:text-title-dark font-medium text-[17px] flex flex-wrap items-center justify-between max-sm:flex-col max-sm:h-auto max-sm:mb-[15px]">
                <h2 class="mb-0 inline-flex items-center py-[16px] max-sm:pb-[5px] overflow-hidden whitespace-nowrap text-ellipsis text-[18px] font-semibold text-dark dark:text-title-dark  capitalize">
                    Detalles
                </h2>
            </div>

            <form id="form-registrar-producto" action="javascript:RegisterProduct()">
                <div class="sm:grid sm:grid-cols-12 max-sm:flex max-sm:flex-col px-[25px] pb-[10px] gap-[10px]">
                    <div class="col-span-12 md:col-span-5 xl:col-span-5">
                        <label for="field-producto-clave" class="inline-flex items-center w-[178px] mb-[2px] text-[14px] font-medium capitalize dark:text-title-dark">
                            Clave
                        </label>
                        <div class="flex flex-col flex-1 md:flex-row">
                            <input type="text"
                                    id="field-producto-clave"
                                    name="clave"
                                    class="rounded-4 border-normal border-1 text-[14px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[6px] min-h-[40px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" 
                                    placeholder="Clave"
                                    maxlength="12"
                                    required
                                    disabled>
                        </div>
                    </div>
                    <div class="col-span-12 md:col-span-7 xl:col-span-7">
                        <label for="field-producto-codigo-barras" class="inline-flex items-center w-[178px] mb-[2px] text-[14px] font-medium capitalize dark:text-title-dark">
                            Código Barras
                        </label>
                        <div class="flex flex-col flex-1 md:flex-row">
                            <input type="text"
                                    id="field-producto-codigo-barras"
                                    name="codigo_barras"
                                    class="rounded-4 border-normal border-1 text-[14px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[6px] min-h-[40px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                    placeholder="Código Barras"
                                    maxlength="32"
                                    required
                                    disabled>
                        </div>
                    </div>
                    <div class="col-span-12">
                        <div class="flex flex-col md:flex-col">
                            <label for="select-producto-categoria" class="inline-flex items-center w-[178px] mb-[2px] text-[14px] font-medium capitalize dark:text-title-dark">Categoría</label>
                            <div class="flex items-center flex-1">
                                <div class="w-full">
                                    <select id="select-producto-categoria"
                                            name="categoria"
                                            data-te-select-init data-te-select-filter="true"
                                            data-te-select-init data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto"
                                            data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none"
                                            data-te-class-notch-middle="!border-0 !shadow-none !outline-none"
                                            data-te-class-notch-trailing="!border-0 !shadow-none !outline-none"
                                            disabled>
                                        
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12">
                        <label for="field-producto" class="inline-flex items-center w-[178px] mb-[2px] text-[14px] font-medium capitalize dark:text-title-dark">
                            Producto
                        </label>
                        <div class="flex flex-col flex-1 md:flex-row">
                            <input type="text"
                                    id="field-producto"
                                    name="procedure"
                                    class="rounded-4 border-normal border-1 text-[14px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[6px] min-h-[40px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                    placeholder="Producto"
                                    maxlength="100"
                                    required
                                    disabled>
                        </div>
                    </div>
                    <div class="col-span-12">
                        <label for="field-producto-descripcion" class="inline-flex items-center w-[178px] mb-[2px] text-[14px] font-medium capitalize dark:text-title-dark">
                            Descripción
                        </label>
                        <div class="flex flex-col flex-1 md:flex-row">
                            <textarea id="field-producto-descripcion"
                                    name="descripcion"
                                    rows="3"
                                    class="rounded-4 border-normal border-1 text-[15px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[12px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary resize-none"
                                    placeholder="Descripción del producto..."
                                    maxlength="255"
                                    disabled></textarea>
                        </div>
                    </div>
                    <div class="col-span-12 md:col-span-6">
                        <div class="flex flex-col md:flex-col">
                            <label for="select-producto-unidad" class="inline-flex items-center w-[178px] mb-[2px] text-[14px] font-medium capitalize dark:text-title-dark">Unidad</label>
                            <div class="flex items-center flex-1">
                                <div class="w-full">
                                    <select id="select-producto-unidad"
                                            name="unidad"
                                            data-te-select-init data-te-select-filter="true"
                                            data-te-select-init data-te-class-select-input="py-[11px] px-[20px] text-[14px] capitalize [&~span]:top-[18px] [&~span]:w-[12px] w-full [&~span]:h-[15px] [&~span]:text-body dark:[&~span]:text-white text-dark dark:text-subtitle-dark border-normal dark:border-box-dark-up border-1 rounded-6 dark:bg-box-dark-up focus:border-primary outline-none ltr:[&~span]:right-[3px] rtl:[&~span]:left-[3px] rtl:[&~span]:right-auto"
                                            data-te-class-notch-leading="!border-0 !shadow-none group-data-[te-input-focused]:shadow-none group-data-[te-input-focused]:border-none"
                                            data-te-class-notch-middle="!border-0 !shadow-none !outline-none"
                                            data-te-class-notch-trailing="!border-0 !shadow-none !outline-none"
                                            disabled>
                                        
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 md:col-span-6 xl:col-span-6">
                        <label for="field-producto-precio-base" class="inline-flex items-center w-[178px] mb-[2px] text-[14px] font-medium capitalize dark:text-title-dark">
                            Precio Base
                        </label>
                        <div class="flex flex-col flex-1 md:flex-row">
                            <input type="text"
                                    id="field-producto-precio-base"
                                    name="precio_base"
                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^(\d+\.\d{2}).*$/, '$1')"
                                    class="rounded-4 border-normal border-1 text-[14px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[6px] min-h-[40px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary"
                                    placeholder="Precio Base"
                                    maxlength="28"
                                    required
                                    disabled>
                        </div>
                    </div>
                    <div class="col-span-12 md:col-span-6 xl:col-span-6">
                        <label for="field-producto-porcentaje-impuesto" class="inline-flex items-center w-[178px] mb-[2px] text-[14px] font-medium capitalize dark:text-title-dark">
                            Porcentaje Impuesto
                        </label>
                        <div class="flex flex-col flex-1 md:flex-row">
                            <input type="text"
                                    id="field-producto-porcentaje-impuesto"
                                    name="porcentaje_impuesto"
                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^(\d+\.\d{2}).*$/, '$1')"
                                    class="rounded-4 border-normal border-1 text-[14px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[6px] min-h-[40px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" 
                                    placeholder="Porcentaje Impuesto"
                                    maxlength="12"
                                    required
                                    value="16"
                                    disabled>
                        </div>
                    </div>
                    <div class="col-span-12 md:col-span-6 xl:col-span-6">
                        <label for="field-producto-precio" class="inline-flex items-center w-[178px] mb-[2px] text-[14px] font-medium capitalize dark:text-title-dark">
                            Precio
                        </label>
                        <div class="flex flex-col flex-1 md:flex-row">
                            <input type="text"
                                    id="field-producto-precio"
                                    name="precio"
                                    oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1').replace(/^(\d+\.\d{2}).*$/, '$1')"
                                    class="rounded-4 border-normal border-1 text-[14px] dark:bg-box-dark-up dark:border-box-dark-up px-[20px] py-[6px] min-h-[40px] outline-none placeholder:text-[#A0A0A0] text-body dark:text-subtitle-dark w-full focus:ring-primary focus:border-primary" 
                                    placeholder="Precio"
                                    maxlength="12"
                                    required
                                    disabled>
                        </div>
                    </div>
                    <div class="col-span-12 md:col-span-6 xl:col-span-6">
                        <div class="flex flex-col pb-4 md:flex-row gap-[25px]">
                            <label for="chk-producto-habilitado-venta" class="inline-flex items-center w-[278px] mb-2 text-sm font-medium capitalize text-dark dark:text-title-dark">Habilitado Venta</label>
                            <div class="flex items-center flex-1">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input type="checkbox" 
                                            id="chk-producto-habilitado-venta"
                                            name="procedure_require_material"
                                            class="sr-only peer"
                                            disabled>
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary/10 dark:peer-focus:ring-transparent rounded-full peer dark:bg-box-dark-up peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary">
                                    </div>
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="col-span-12 flex flex-row-reverse items-center gap-[5px]">
                        <button type="button" id="btn-nuevo-producto" class="px-[30px] h-[34px] mb-[14px] text-white bg-primary border-regular hover:bg-primary-hbr disabled:text-neutral-600 disabled:bg-lightgray disabled:cursor-not-allowed font-medium rounded-4 text-sm w-full sm:w-auto text-center inline-flex items-center justify-center capitalize transition-all duration-300 ease-linear" data-te-ripple-init="" data-te-ripple-color="light">Nuevo</button>
                        <button type="submit" id="btn-registrar-producto" class="!visible hidden px-[30px] h-[34px] mb-[14px] text-white bg-primary border-regular hover:bg-primary-hbr disabled:text-neutral-600 disabled:bg-lightgray disabled:cursor-not-allowed font-medium rounded-4 text-sm w-full sm:w-auto text-center inline-flex items-center justify-center capitalize transition-all duration-300 ease-linear" data-te-ripple-init="" data-te-ripple-color="light" disabled>Registrar</button>
                        <button type="button" id="btn-modificar-producto" class="!visible hidden px-[30px] h-[34px] mb-[14px] text-white bg-warning border-regular hover:bg-warning-hbr disabled:text-neutral-600 disabled:bg-lightgray disabled:cursor-not-allowed font-medium rounded-4 text-sm w-full sm:w-auto text-center inline-flex items-center justify-center capitalize transition-all duration-300 ease-linear" data-te-ripple-init="" data-te-ripple-color="light" disabled>Modificar</button>
                        <button type="button" id="btn-cancelar-producto" class="!visible hidden px-[30px] h-[34px] mb-[14px] text-white bg-danger border-regular hover:bg-danger-hbr disabled:text-neutral-600 disabled:bg-lightgray disabled:cursor-not-allowed font-medium rounded-4 text-sm w-full sm:w-auto text-center inline-flex items-center justify-center capitalize transition-all duration-300 ease-linear" data-te-ripple-init="" data-te-ripple-color="light" disabled>Cancelar</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="<?= asset('js/products/index.js'); ?>"></script>

<script>
    var currentLink = '<?= base_url('products'); ?>';
</script>