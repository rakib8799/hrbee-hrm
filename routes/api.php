<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\EmployeeController;
use App\Http\Controllers\API\AttendanceController;
use App\Http\Controllers\API\HealthCheckController;
use App\Http\Controllers\API\ConfigurationController;
use App\Http\Controllers\API\Tenant\TenantController;
use App\Http\Controllers\API\EmailService\EmailEventController;
use App\Http\Controllers\API\Subscription\SubscriptionController;
use App\Http\Controllers\API\EmailService\EmailProviderController;
use App\Http\Controllers\API\Subscription\SubscriptionPlanController;
use App\Http\Controllers\API\EmailService\EmailEventTemplateController;
use App\Http\Controllers\API\Subscription\SubscriptionPlanFeatureController;


Route::prefix('v1')->group(function () {
    Route::middleware(['api.key', 'throttle:api'])->group(function () {
        Route::post('/hrbee-hrm/sync/email-events', [EmailEventController::class, 'syncEmailEvent']);
        Route::post('/hrbee-hrm/sync/email-event-template', [EmailEventTemplateController::class, 'syncEmailEventTemplates']);
        Route::post('/hrbee-hrm/sync/email-providers', [EmailProviderController::class, 'syncEmailProviders']);
        Route::post('/hrbee-hrm/sync/subscription-plan-features', [SubscriptionPlanFeatureController::class, 'syncSubscriptionPlanFeatures']);
        Route::post('/hrbee-hrm/sync/subscription-plans', [SubscriptionPlanController::class, 'syncSubscriptionPlans']);
        Route::post('/hrbee-hrm/sync/subscription', [SubscriptionController::class, 'syncSubscription']);
        Route::post('/hrbee-hrm/sync/configurations', [ConfigurationController::class, 'syncConfigurations']);
        Route::post('/hrbee-hrm/tenant/migration', [TenantController::class, 'runMigrations']);
        Route::post('/hrbee-hrm/tenant/run-seeders', [TenantController::class, 'runSeeders']);
        Route::post('/hrbee-hrm/tenant/create-admin-user', [TenantController::class, 'createAdminUser']);
    });

    Route::get('/health-check', [HealthCheckController::class, 'index']);
    Route::get('/health-check-test', [HealthCheckController::class, 'index']);

    Route::group(['middleware' => 'throttle:api'], function () {
        Route::post('/auth/admin-login', [AuthController::class, 'adminLogin']);
        // configuration
        Route::get('/configurations/company-config', [ConfigurationController::class, 'companyConfiguration']);
    });
    
    Route::group(['middleware' => ['auth:sanctum', 'throttle:api']], function () {
        // Put Authenticated routes
        Route::post('/auth/logout', [AuthController::class, 'logout']);
        Route::get('/employees/{staff_id}', [EmployeeController::class, 'getEmployeeDetails']);
        Route::post('/employees/{staff_id}/attendance', [AttendanceController::class, 'store']);
    });
});

