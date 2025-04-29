<?php

namespace App\Services\Subscription;

use Exception;
use App\Constants\Constants;
use App\Services\Core\HelperService;
use App\Services\Core\BaseModelService;
use App\Models\Subscription\SubscriptionPlan;

class SubscriptionPlanService extends BaseModelService
{

    private SubscriptionPlanFeatureService $subscriptionPlanFeatureService;
    public function __construct(SubscriptionPlanFeatureService $subscriptionPlanFeatureService)
    {
        $this->subscriptionPlanFeatureService = $subscriptionPlanFeatureService;
    }

    public function model(): string
    {
        return SubscriptionPlan::class;
    }

    public function getActiveSubscriptionPlans()
    {
        $subscriptionPlans = $this->model()::with('subscriptionPlanFeatures')->where('is_active', true)->get();
        $subscriptionPlansMonthly = [];
        $subscriptionPlansYearly = [];
        foreach ($subscriptionPlans as $plan) {
            if ($plan['plan_type'] === Constants::MONTHLY) {
                $subscriptionPlansMonthly[] = $plan;
            } else if($plan['plan_type'] === Constants::YEARLY) {
                $subscriptionPlansYearly[] = $plan;
            }
        }
        $subscriptionPlanData = [
            "subscriptionPlansMonthly" => $subscriptionPlansMonthly,
            "subscriptionPlansYearly" => $subscriptionPlansYearly
        ];
        return $subscriptionPlanData;
    }

    public function syncData($request)
    {
        try {
            $subscriptionPlans = $request->all();
            // Check if the data is a single object (associative array)
            if (isset($subscriptionPlans['id'])) {
                $subscriptionPlans = [$subscriptionPlans];
            }

            foreach ($subscriptionPlans as $subscriptionPlan) {
                $subscriptionPlan['central_id'] = $subscriptionPlan['id'];
                $newSubscriptionPlan = $this->model()::updateOrCreate(["central_id" => $subscriptionPlan["central_id"]], $subscriptionPlan);

                // Sync subscription plan features (many to many)
                if (isset($subscriptionPlan['subscription_plan_features'])) {
                    $centralFeatureIds = collect($subscriptionPlan['subscription_plan_features'])->pluck('id')->toArray();

                    // Need to map id of subscriptionPlanFeature of central admin to hrm-hrbee using central_id
                    $hrmFeatureIds = $this->subscriptionPlanFeatureService->getSubscriptionPlanFeatureIds($centralFeatureIds);
                    $newSubscriptionPlan->subscriptionPlanFeatures()->sync($hrmFeatureIds);
                }
            }
        } catch (Exception $e) {
            HelperService::captureException($e);
        }
    }
}
