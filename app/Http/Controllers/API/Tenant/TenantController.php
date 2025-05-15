<?php

namespace App\Http\Controllers\API\Tenant;

use App\Constants\Constants;
use App\Services\ConfigurationService;
use Illuminate\Http\Request;
use App\Models\Configuration;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Services\Tenant\TenantService;
use App\Http\Requests\API\CreateUserRequest;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;

class TenantController extends Controller
{
    private TenantService $tenantService;
    private ConfigurationService $configurationService;
    public function __construct(TenantService $tenantService, ConfigurationService $configurationService)
    {
        $this->tenantService = $tenantService;
        $this->configurationService = $configurationService;
    }
    public function runMigrations(Request $request): JsonResponse
    {
        $this->tenantService->runMigrations();
        return response()->json([
            'message' => 'Migrations ran successfully'
        ]);
    }

    public function runSeeders(Request $request): JsonResponse
    {
        $this->tenantService->runSeeders();
        $requestedData = [
            'workspace' => $request->workspace,
            'email_provider_id' => $request->email_provider_id,
            'support_email' => $request->support_email,
            'support_telephone' => $request->support_telephone,
        ];
        $configuration = $this->configurationService->updateConfiguration($requestedData);
        return response()->json([
            'message' => 'Seeders ran successfully'
        ]);
    }

    public function createAdminUser(CreateUserRequest $createUserRequest): JsonResponse
    {
        $validatedData = $createUserRequest->validated();
        $user = $this->tenantService->createAdminUser($validatedData);
        #TODO: Need to remove the following two lines. We need to handle email_verified_at from VerifyEmailController.
        $user->email_verified_at = Carbon::now();
        $user->save();

        $appEnv = config('app.env');

        if ($appEnv === 'production') {
            $baseUrl = $validatedData['workspace'] . '-' . Constants::SUB_DOMAIN . '.' . Constants::DOMAIN;
        } else if ($appEnv === 'staging') {
            $baseUrl = $validatedData['workspace'] . '-' . Constants::STAGING_SERVER . '.' . Constants::SUB_DOMAIN . '.' . Constants::DOMAIN;
        } else {
            $baseUrl = $validatedData['workspace'] . '-' . Constants::DEV_SERVER . '.' . Constants::SUB_DOMAIN . '.' . Constants::DOMAIN;
        }

        $emailVerificationUrl = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(60),
            ['id' => $user->id, 'hash' => sha1($user->email)]
        );

        // Dynamic url depending on workspace and APP_ENV
        $emailVerificationUrl = str_replace(parse_url($emailVerificationUrl, PHP_URL_HOST), $baseUrl, $emailVerificationUrl);

        return response()->json([
            'message' => 'Admin user created successfully',
            'email_verification_url' => $emailVerificationUrl
        ]);
    }
}
