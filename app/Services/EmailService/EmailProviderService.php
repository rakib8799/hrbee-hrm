<?php

namespace App\Services\EmailService;


use Exception;
use App\Services\Core\HelperService;
use App\Services\ConfigurationService;
use App\Services\Core\BaseModelService;
use App\Models\EmailService\EmailProvider;

class EmailProviderService extends BaseModelService
{
    private ConfigurationService $configurationService;

    public function __construct(ConfigurationService $configurationService)
    {
        $this->configurationService = $configurationService;
    }
    
    public function model(): string
    {
        return EmailProvider::class;
    }

    public function createEmailProvider($data)
    {
        return $this->create($data);
    }

    public function syncData($request)
    {
        try {
            $this->model()::query()->update(['is_active' => false]);
            $emailProviders = $request->all();
            // Check if the data is a single object (associative array)
            if (isset($emailProviders['id'])) {
                $emailProviders = [$emailProviders];
            }

            foreach ($emailProviders as $emailProvider) {
                $emailProvider['central_id'] = $emailProvider['id'];
                $newEmailProvider = $this->model()::updateOrCreate(["central_id" => $emailProvider["central_id"]], $emailProvider);
                if ($newEmailProvider->is_active == true) {
                    $this->configurationService->updateConfiguration(['email_provider_id' => $newEmailProvider->id]);
                }
            }
        } catch (Exception $e) {
            HelperService::captureException($e);
        }
    }
}
