<?php

namespace App\Http\Controllers\HR\Leave;

use App\Http\Controllers\Controller;
use App\Models\Configuration;
use App\Services\HR\Leave\CalendarLeaveService;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use Inertia\Inertia;

class CalendarLeaveController extends Controller implements HasMiddleware
{
    protected CalendarLeaveService $calendarLeaveService;

    public function __construct(CalendarLeaveService $calendarLeaveService)
    {
        $this->calendarLeaveService = $calendarLeaveService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:can-view-calendar-leave', only: ['index'])
        ];
    }

    public function index()
    {
        $breadcrumbs = Breadcrumbs::generate('calendarLeaves');
        $calendarLeaves = $this->calendarLeaveService->getCalendarLeaves();
        $weekendsConfig = Configuration::where('option_name', 'weekends')->first();
        $weekends = json_decode($weekendsConfig->option_value, true);
        $responseData = [
            'calendarLeaves' => $calendarLeaves,
            'weekends' => $weekends,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => 'Calendar Leave List'
        ];

        return Inertia::render('CalendarLeave/Index', $responseData);
    }
}
