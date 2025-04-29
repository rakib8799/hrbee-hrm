<?php

namespace App\Services\EmailService;


use Exception;
use App\Services\Core\HelperService;
use App\Services\Core\BaseModelService;
use App\Models\EmailService\EmailEventTemplate;

class EmailEventTemplateService extends BaseModelService
{

    public function model(): string
    {
        return EmailEventTemplate::class;
    }

    public function syncData($request)
    {
        try {
            $email_template= $request->all();
            $this->model()::updateOrCreate(["email_provider_id" => $email_template["email_provider_id"], ""], $email_template);
        } catch (Exception $e) {
            HelperService::captureException($e);
        }
    }

}
