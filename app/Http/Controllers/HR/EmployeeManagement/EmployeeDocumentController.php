<?php

namespace App\Http\Controllers\HR\EmployeeManagement;

use App\Http\Controllers\Controller;
use App\Http\Requests\HR\EmployeeManagement\CreateEmployeeDocumentRequest;
use App\Http\Requests\HR\EmployeeManagement\UpdateEmployeeDocumentRequest;
use App\Models\HR\EmployeeDocument;
use App\Services\HR\Employee\EmployeeDocumentService;

class EmployeeDocumentController extends Controller
{
    protected EmployeeDocumentService $employeeDocumentService;

    public function __construct(EmployeeDocumentService $employeeDocumentService)
    {
        $this->employeeDocumentService = $employeeDocumentService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $employeeDocuments = $this->employeeDocumentService->getEmployeeDocuments();
        return $employeeDocuments;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreateEmployeeDocumentRequest $request)
    {
        $validatedData = $request->validated();
        $employeeDocument = $this->employeeDocumentService->create($validatedData);
        return $employeeDocument;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEmployeeDocumentRequest $request, EmployeeDocument $employeeDocument)
    {
        $validatedData = $request->validated();
        $employeeDocument = $this->employeeDocumentService->update($employeeDocument, $validatedData);
        return $employeeDocument;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        $employeeDocument = $this->employeeDocumentService->delete($id);
        return $employeeDocument;
    }
}
