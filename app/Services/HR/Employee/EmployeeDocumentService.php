<?php

namespace App\Services\HR\Employee;

use App\Models\HR\EmployeeDocument;
use App\Services\Core\BaseModelService;

class EmployeeDocumentService extends BaseModelService
{
    public function model(): string
    {
        return EmployeeDocument::class;
    }

    public function getEmployeeDocuments()
    {
        $employeeDocuments = $this->model()::all();
        return $employeeDocuments;
    }
}
