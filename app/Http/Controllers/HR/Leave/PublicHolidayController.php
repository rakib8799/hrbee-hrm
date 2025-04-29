<?php

namespace App\Http\Controllers\HR\Leave;

use App\Constants\Constants;
use App\Http\Controllers\Controller;
use App\Http\Requests\HR\Leave\CreatePublicHolidayRequest;
use App\Http\Requests\HR\Leave\UpdatePublicHolidayRequest;
use App\Models\HR\Leave\CalendarLeave;
use App\Services\HR\Leave\CalendarLeaveService;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Inertia\Inertia;

class PublicHolidayController extends Controller implements HasMiddleware
{
    protected CalendarLeaveService $calendarLeaveService;

    public function __construct(CalendarLeaveService $calendarLeaveService)
    {
        $this->calendarLeaveService = $calendarLeaveService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:can-create-public-holiday', only: ['create', 'store']),
            new Middleware('permission:can-edit-public-holiday', only: ['edit', 'update']),
            new Middleware('permission:can-view-public-holiday', only: ['index'])
        ];
    }

    public function index()
    {
        $breadcrumbs = Breadcrumbs::generate('publicHolidays');
        $publicHolidays = $this->calendarLeaveService->getPublicHolidays();
        $responseData = [
            'publicHolidays' => $publicHolidays,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => 'Public Holiday List'
        ];

        return Inertia::render('PublicHoliday/Index', $responseData);
    }

    public function create()
    {
        $breadcrumbs = Breadcrumbs::generate('addPublicHoliday');
        $responseData = [
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => 'Add Public Holiday'
        ];

        return Inertia::render('PublicHoliday/Create', $responseData);
    }

    public function store(CreatePublicHolidayRequest $request)
    {
        $validatedData = $request->validated();
        $publicHoliday = $this->calendarLeaveService->createPublicHoliday($validatedData);
        $status = $publicHoliday ? Constants::SUCCESS : Constants::ERROR;
        $message = $publicHoliday ? 'Public holiday created successfully' : 'Public holiday could not be created';
        return Redirect::route('public-holidays.index')->with($status, $message);
    }

    public function edit(CalendarLeave $calendarLeave)
    {
        $breadcrumbs = Breadcrumbs::generate('editPublicHoliday', $calendarLeave);
        $responseData = [
            'publicHoliday' => $calendarLeave,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => 'Edit Public Holiday'
        ];

        return Inertia::render('PublicHoliday/Create', $responseData);
    }

    public function update(UpdatePublicHolidayRequest $request, CalendarLeave $calendarLeave)
    {
        $validatedData = $request->validated();
        $isUpdated = $this->calendarLeaveService->updatePublicHoliday($calendarLeave, $validatedData);
        $status = $isUpdated ? Constants::SUCCESS : Constants::ERROR;
        $message = $isUpdated ? 'Public holiday updated successfully' : 'Public holiday could not be updated';
        return Redirect::route('public-holidays.index')->with($status, $message);
    }

    public function destroy(CalendarLeave $calendarLeave)
    {
        $isDeleted = $this->calendarLeaveService->deletePublicHoliday($calendarLeave);
        $status = $isDeleted ? Constants::SUCCESS : Constants::ERROR;
        $message = $isDeleted ? 'Public holiday deleted successfully' : 'Public holiday could not be deleted';
        return Redirect::route('public-holidays.index')->with($status, $message);
    }
}
