<?php

namespace App\Services\EmailService;

use App\Models\Configuration;
use App\Models\EmailService\EmailProvider;
use Illuminate\Support\Str;

class EmailService
{

    protected Configuration $companyConfiguration;

    public function __construct(Configuration $companyConfiguration)
    {
        $this->companyConfiguration = $companyConfiguration;
    }

    /**
     * @throws \Exception
     */
    public function send($to, $subject, $body)
    {
        $emailProviderId = $this->companyConfiguration->where('option_name', 'email_provider_id')->first() ?? new Configuration();

        $emailProviderId = $emailProviderId->option_value;
        if($emailProviderId) {
            $activeProvider = EmailProvider::where('id', $emailProviderId)->where('is_active', 1)->first();
            if ($activeProvider) {
                $providerService = $this->getProviderService($activeProvider);
                return $providerService->send($to, $subject, $body);
            } else {
                // throw new \Exception('No active email provider configured.');
            }
        }
    }

    protected function getProviderService(EmailProvider $provider): EmailServiceInterface
    {
        $providerName = Str::studly($provider->name);
        $providerClass = "App\\Services\\EmailService\\{$providerName}Service";

        if (!class_exists($providerClass)) {
            throw new \Exception("Provider service class {$providerClass} not found.");
        }

        return new $providerClass($provider);
    }

}
