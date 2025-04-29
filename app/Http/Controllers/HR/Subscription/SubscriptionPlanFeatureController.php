<?php

namespace App\Http\Controllers\HR\Subscription;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Subscription\SubscriptionPlanFeatureService;

class SubscriptionPlanFeatureController extends Controller
{
    protected SubscriptionPlanFeatureService $subscriptionPlanFeatureService;

    public function __construct(SubscriptionPlanFeatureService $subscriptionPlanFeatureService)
    {
        $this->subscriptionPlanFeatureService = $subscriptionPlanFeatureService;
    }
}
