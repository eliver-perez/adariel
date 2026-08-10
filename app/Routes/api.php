<?php

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

use App\Controllers\AuthController;
use App\Controllers\OrganizationsController;
use App\Controllers\StaffController;
use App\Controllers\UsersController;
use App\Controllers\BillingController;
use App\Controllers\PatientsController;
use App\Controllers\ClientsController;
use App\Controllers\AppointmentsController;
use App\Controllers\GenderController;
use App\Controllers\ConsentTemplatesController;
use App\Controllers\AppointmentsTypesController;
use App\Controllers\AppointmentsStatusController;
use App\Controllers\BookingChannelsController;
use App\Controllers\ProceduresController;
use App\Controllers\UserRoleController;
use App\Controllers\UsersTypesController;
use App\Controllers\RoleController;
use App\Controllers\SpecialtyController;
use App\Controllers\LocationController;
use App\Controllers\ConsultationsController;
use App\Controllers\ConsultationsCatalogController;
use App\Controllers\DiagnosticsController;
use App\Controllers\SalesController;
use App\Controllers\SalesStatusController;
use App\Controllers\CashRegisterController;
use App\Controllers\CashReconciliationController;
use App\Controllers\PaymentsController;
use App\Controllers\POSController;
use App\Controllers\UnitsMeasureController;
use App\Controllers\ProductsController;
use App\Controllers\WhatsAppIntegrationController;
use App\Controllers\WhatsAppMessageController;
use App\Controllers\MediaController;
use App\Core\Response;

$router->post('/api/auth/login', [AuthController::class, 'login']);                                                                                         // ✅
$router->post('/api/auth/logout', [AuthController::class, 'logout']);                                                                                       // ✅
$router->get('/api/auth/me', [AuthController::class, 'me']);                                                                                                // ✅

/**
 * ORGANIZATION ROUTES
 */

$router->get('/api/organizations', [OrganizationsController::class, 'index']);                                                                              // ✅
$router->get('/api/organizations/{id}', [OrganizationsController::class, 'show']);                                                                          // ✅
$router->post('/api/organizations', [OrganizationsController::class, 'store']);                                                                             // ✅

$router->get('/api/my-users', [OrganizationsController::class, 'myUsers']);                                                                                 // ✅

/**
 * STAFF ROUTES
 */
$router->get('/api/staff', [StaffController::class, 'index']);                                                                                              // ✅
$router->get('/api/staff/{id}', [StaffController::class, 'show']);                                                                                          // ✅
$router->post('/api/staff', [StaffController::class, 'store']);                                                                                             // ✅

/**
 * USERS ROUTES
 */
$router->get('/api/users', [UsersController::class, 'index']);
$router->get('/api/users/types', [UsersTypesController::class, 'index']);
$router->get('/api/users/roles', [UserRoleController::class, 'roles']);
$router->get('/api/users/types/{id}/roles', [UserRoleController::class, 'userTypeRoles']);
$router->get('/api/users/{id}/roles', [UserRoleController::class, 'userRoles']);
$router->post('/api/users/{id}/permissions', [UserRoleController::class, 'addUserPermission']);

/**
 * USER TYPES ROUTES
 */

$router->post('/api/user-types/{id}/permissions', [UserRoleController::class, 'addUserTypePermission']);

/**
 * APPOINTMENTS ROUTES
 */
$router->get('/api/appointments/calendar', [AppointmentsController::class, 'calendar']);                                                                    // ✅
$router->get('/api/appointments/status', [AppointmentsStatusController::class, 'index']);
$router->post('/api/appointments', [AppointmentsController::class, 'schedule']);                                                                            // ✅
$router->post('/api/appointments/available-slots', [AppointmentsController::class, 'availableSlots']);                                                      // ✅
$router->post('/api/appointments/check-in', [AppointmentsController::class, 'checkIn']);                                                                    // ✅
$router->post('/api/appointments/cancel', [AppointmentsController::class, 'cancel']);                                                                       // ✅

/**
 * BILLING ROUTES
 */
$router->get('/api/billing-regimenes', [BillingController::class, 'regimenes']);                                                                            // ✅

/**
 * PATIENTS ROUTES
 */
$router->get('/api/patients', [PatientsController::class, 'index']);                                                                                        // ✅
$router->post('/api/patients', [PatientsController::class, 'store']);                                                                                       // ✅

/**
 * PATIENTS ROUTES
 */
$router->get('/api/clients', [ClientsController::class, 'index']);                                                                                          // ✅

/**
 * GENDER ROUTES
 */
$router->get('/api/genders', [GenderController::class, 'index']);                                                                                           // ✅

/**
 * CONSENT TEMPLATES ROUTES
 */
$router->get('/api/consent-templates', [ConsentTemplatesController::class, 'index']);                                                                       // ✅
$router->post('/api/consent-templates', [ConsentTemplatesController::class, 'store']);                                                                      // ✅
$router->get('/api/consent-templates/{id}', [ConsentTemplatesController::class, 'show']);                                                                   // ✅
$router->put('/api/consent-templates/{id}', [ConsentTemplatesController::class, 'update']);                                                                 // ✅

$router->post('/api/consent-templates/preview', [ConsentTemplatesController::class, 'preview']);                                                            // ✅

$router->get('/api/consent-templates/{id}/status', [ConsentTemplatesController::class, 'status']);                                                          // ✅
$router->put('/api/consent-templates/{id}/activate', [ConsentTemplatesController::class, 'activate']);                                                      // ✅

/**
 * APPOINTMENTS TYPE ROUTES
 */
$router->get('/api/appointments-types', [AppointmentsTypesController::class, 'index']);                                                                     // ✅

/**
 * BOOKING TYPE ROUTES
 */
$router->get('/api/booking-channels', [BookingChannelsController::class, 'index']);                                                                         // ✅

/**
 * PROCEDURES ROUTES
 */
$router->get('/api/procedures', [ProceduresController::class, 'index']);                                                                                    // ✅
$router->post('/api/procedures', [ProceduresController::class, 'store']);                                                                                   // ✅
$router->put('/api/procedures/{id}', [ProceduresController::class, 'update']);                                                                              // ✅
$router->get('/api/procedures/{id}', [ProceduresController::class, 'show']);                                                                                // ✅
$router->get('/api/procedures/{id}/staff', [ProceduresController::class, 'staff']);                                                                         // ✅
$router->get('/api/procedures/{procedureId}/staff/{staffId}', [ProceduresController::class, 'procedureStaffData']);                                         // ✅
$router->post('/api/procedures/{procedureId}/staff/{staffId}', [ProceduresController::class, 'insertProcedureStaff']);                                      // ✅

/**
 * USER ROLE ROUTES
 */
$router->get('/api/users-roles', [UserRoleController::class, 'index']);                                                                                     // ✅

/**
 * STAFF ROLE ROUTES
 */
$router->get('/api/roles', [RoleController::class, 'index']);                                                                                               // ✅

/**
 * SPECIALTIES ROUTES
 */
$router->get('/api/specialties', [SpecialtyController::class, 'index']);                                                                                    // ✅

/**
 * LOCATION ROUTES
 */
$router->get('/api/locations/countries', [LocationController::class, 'countries']);                                                                         // ✅
$router->get('/api/locations/states', [LocationController::class, 'states']);                                                                               // ✅
$router->get('/api/locations/municipalities', [LocationController::class, 'municipalities']);                                                               // ✅
$router->get('/api/locations/localities', [LocationController::class, 'localities']);                                                                       // ✅

$router->post('/api/locations/states', [LocationController::class, 'storeState']);                                                                          // ✅
$router->post('/api/locations/municipalities', [LocationController::class, 'storeMunicipality']);                                                           // ✅
$router->post('/api/locations/localities', [LocationController::class, 'storeLocality']);                                                                   // ✅

/**
 * CONSULTATIONS ROUTES
 */
$router->get('/api/consultations', [ConsultationsController::class, 'index']);                                                                              // ✅
$router->get('/api/consultations/{id}', [ConsultationsController::class, 'show']);                                                                          // ✅

$router->put('/api/consultations/{id}/observations', [ConsultationsController::class, 'update_consultation_observations']);                                 // ✅

$router->put('/api/consultations/{id}/podiatric-exploration', [ConsultationsController::class, 'update_consultation_podiatric_exploration']);               // ✅

$router->get('/api/consultations/{id}/podiatric-exploration', [ConsultationsController::class, 'get_consultation_podiatric_exploration']);                  // ✅

$router->get('/api/consultations/{id}/procedures', [ConsultationsController::class, 'get_consultation_procedures']);                                        // ✅
$router->get('/api/consultations/{id}/procedures/{procedureId}/resolve', [ConsultationsController::class, 'get_procedure_data']);                           // ✅
$router->put('/api/consultations/{id}/procedures', [ConsultationsController::class, 'update_consultation_procedures']);                                     // ✅

$router->get('/api/consultations/{id}/diagnostics', [ConsultationsController::class, 'get_consultation_diagnostics']);                                      // ✅
$router->get('/api/consultations/{id}/diagnostics/{diagnosticId}/resolve', [ConsultationsController::class, 'get_diagnostic_data']);                        // ✅
$router->put('/api/consultations/{id}/diagnostics', [ConsultationsController::class, 'update_consultation_diagnostics']);                                   // ✅

$router->get('/api/consultations/{id}/sores', [ConsultationsController::class, 'get_consultation_sores']);                                                  // ✅
$router->put('/api/consultations/{id}/sores', [ConsultationsController::class, 'update_consultation_sores']);                                               // ✅

$router->post('/api/consultations/{id}/evidence-upload', [ConsultationsController::class, 'update_consultation_evidence_upload']);                          // ✅

$router->put('/api/consultations/{id}/indications', [ConsultationsController::class, 'update_consultation_indications']);                                   // ✅

$router->post('/api/consultations/{id}/finish', [ConsultationsController::class, 'finish_consultation']);                                                   // ✅

/**
 * CONSULTATIONS CATALOG ROUTES
 */
$router->get('/api/catalog/podiatry/podiatric-exploration', [ConsultationsCatalogController::class, 'podiatric_exploration']);                              // ✅
$router->get('/api/catalog/podiatry/foot-types', [ConsultationsCatalogController::class, 'podiatry_foot_types']);                                           // ✅
$router->get('/api/catalog/podiatry/pulse-types', [ConsultationsCatalogController::class, 'podiatry_pulse_types']);                                         // ✅
$router->get('/api/catalog/podiatry/sensitivity-types', [ConsultationsCatalogController::class, 'podiatry_sensitivity_types']);                             // ✅
$router->get('/api/catalog/podiatry/temperature-types', [ConsultationsCatalogController::class, 'podiatry_temperature_types']);                             // ✅
$router->get('/api/catalog/podiatry/foot-color-types', [ConsultationsCatalogController::class, 'podiatry_foot_color_types']);                               // ✅
$router->get('/api/catalog/podiatry/metatarsal-formulas', [ConsultationsCatalogController::class, 'podiatry_metatarsal_formulas']);                         // ✅


$router->get('/api/catalog/podiatry/diagnostics', [DiagnosticsController::class, 'diagnostics']);                                                           // ✅
$router->get('/api/catalog/podiatry/diagnostic-types', [DiagnosticsController::class, 'diagnostic_types']);                                                 // ✅

$router->get('/api/catalog/podiatry/podiatric-sores', [ConsultationsCatalogController::class, 'podiatric_sores']);                                          // ✅

/**
 * POINT OF SALE ROUTES
 */
$router->get('/api/pos/cart', [POSController::class, 'get_cart']);                                                                                          // ✅
$router->post('/api/pos/cart', [POSController::class, 'update']);                                                                                           // ✅
$router->delete('/api/pos/cart', [POSController::class, 'delete']);                                                                                         // ✅
$router->post('/api/pos/checkout', [POSController::class, 'checkout']);                                                                                     // ✅
$router->post('/api/pos/empty-cart', [POSController::class, 'empty_cart']);                                                                                 // ✅

/**
 * SALES ROUTES
 */
$router->get('/api/sales-branch', [SalesController::class, 'indexBranch']);                                                                                 // ✅
$router->get('/api/sales/status', [SalesStatusController::class, 'index']);                                                                                 // ✅
$router->get('/api/sales/{id}', [SalesController::class, 'show']);                                                                                          // ✅

/**
 * CASH REGISTER ROUTES
 */
$router->get('/api/cash-register', [CashRegisterController::class, 'index']);                                                                               // ✅

/**
 * CASH RECONCILIATION ROUTES
 */
$router->post('/api/cash-reconciliation', [CashReconciliationController::class, 'store']);                                                                  // ✅
$router->put('/api/cash-reconciliation', [CashReconciliationController::class, 'close']);                                                                   // ✅
$router->get('/api/cash-reconciliation-branch', [CashReconciliationController::class, 'indexBranch']);                                                      // ✅
$router->get('/api/cash-reconciliation/{id}', [CashReconciliationController::class, 'show']);                                                               // ✅
$router->get('/api/cash-reconciliation-closing-data', [CashReconciliationController::class, 'cashReconciliationClosingData']);                              // ✅

/**
 * UNITS MEASURE ROUTES
 */
$router->get('/api/units-measure', [UnitsMeasureController::class, 'index']);                                                                               // ✅

/**
 * PRODUCTS ROUTES
 */
$router->get('/api/products/categories', [ProductsController::class, 'categories']);                                                                        // ✅
$router->get('/api/products', [ProductsController::class, 'index']);                                                                                        // ✅
$router->get('/api/products/{id}', [ProductsController::class, 'show']);                                                                                        // ✅
$router->post('/api/products', [ProductsController::class, 'store']);                                                                                       // ✅

/**
 * PAYMENTS ROUTES
 */
$router->get('/api/payments-branch', [PaymentsController::class, 'indexBranch']);                                                                           // ✅
$router->get('/api/payments/{id}', [PaymentsController::class, 'show']);                                                                                    // ✅

/**
 * WHATSAPP INTEGRATIONS ROUTES
 */
$router->get('/api/whatsapp-integration', [WhatsAppIntegrationController::class, 'show']);                                                                  // ✅
$router->post('/api/whatsapp-integration', [WhatsAppIntegrationController::class, 'save']);                                                                 // ✅
$router->post('/api/whatsapp-integration/test-connection', [WhatsAppIntegrationController::class, 'testConnection']);                                       // ✅

/**
 * WHATSAPP MESSAGES ROUTES
 */
$router->post('/api/whatsapp-messages/test', [WhatsAppMessageController::class, 'sendTestTemplate']);                                                       // ✅