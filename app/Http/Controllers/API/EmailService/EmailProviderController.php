<?php

namespace App\Http\Controllers\API\EmailService;

use App\Http\Controllers\Controller;
use App\Services\EmailService\EmailProviderService;
use Illuminate\Http\Request;

class EmailProviderController extends Controller
{
    private EmailProviderService $EmailProviderService;

    public function __construct(EmailProviderService $EmailProviderService)
    {
        $this->EmailProviderService = $EmailProviderService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function syncEmailProviders(Request $request)
    {
        $this->EmailProviderService->syncData($request);
        return response()->json(['message' => 'Email providers synced successfully']);
    }
}
