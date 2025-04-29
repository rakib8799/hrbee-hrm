<?php

namespace App\Http\Middleware;

use App\Constants\Constants;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Response;

class CheckIsLeaveAllocationEditable
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $leaveAllocation = $request->route('leave_allocation');
        if ($leaveAllocation->status === Constants::APPROVED || $leaveAllocation->status === Constants::DECLINED) { 
            return Redirect::back();
        }
        return $next($request);
    }
}
