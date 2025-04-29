<?php

namespace App\Services\EmailService;


use Exception;
use App\Services\Core\HelperService;
use App\Models\EmailService\EmailEvent;
use App\Services\Core\BaseModelService;

class EmailEventService extends BaseModelService
{

    public function model(): string
    {
        return EmailEvent::class;
    }

    public function createEmailEvent($data)
    {
        return $this->create($data);
    }

    public function syncData($request)
    {
        try {
            $email_providers= $request->all();
            $this->model()::updateOrCreate(["slug" => $email_providers["slug"]], $email_providers);
        } catch (Exception $e) {
            HelperService::captureException($e);
        }
    }
}
