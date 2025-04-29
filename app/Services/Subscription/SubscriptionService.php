<?php

namespace App\Services\Subscription;

use App\Constants\Constants;
use App\Models\Subscription\Subscription;
use App\Services\Core\BaseModelService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Exception;


class SubscriptionService extends BaseModelService
{
    protected SubscriptionHistoryService $subscriptionHistoryService;
    protected SubscriptionPlanService $subscriptionPlanService;

    public function __construct(SubscriptionHistoryService $subscriptionHistoryService, SubscriptionPlanService $subscriptionPlanService)
    {
        $this->subscriptionHistoryService = $subscriptionHistoryService;
        $this->subscriptionPlanService = $subscriptionPlanService;
    }

    public function model(): string
    {
        return Subscription::class;
    }

    public function getSubscription()
    {
        $result = DB::transaction(function () {
            $subscription = $this->model()::with('subscriptionPlan')->first();

            if ($subscription) {
                $this->updateSubscriptionStatus($subscription);
            }

            return $subscription;
        });
        return $result;
    }

    public function updateSubscriptionStatus(Subscription $subscription)
    {
        $endDate = Carbon::parse($subscription->end_date);
        $currentDate = Carbon::now();

        if ($currentDate->greaterThan($endDate)) {
            $subscription->is_active = 0;
            $subscription->save();
        } else {
            $subscription->is_active = 1;
            $subscription->save();
        }
    }

    /**
     * Subscription plan change and renewal in one method
     */
    public function updateSubscription(Subscription $subscription, $validatedData)
    {
        $result = DB::transaction(function () use ($subscription, $validatedData) {

            if ($subscription) {
                $subscriptionPlan = $this->subscriptionPlanService->find($validatedData['subscription_plan_id']);

                // If the subscription that we want to purchase is deactivated it will return false.
                if ($subscriptionPlan->is_active == false) {
                    return false;
                }

                // if subscription renewal then start_date will be same as previous.
                if ($subscription->subscription_plan_id === $validatedData['subscription_plan_id']) {
                    $startDate = $subscription->start_date;
                    $endDate = $this->calculateEndDate(Carbon::now(), $subscription->subscriptionPlan);
                } else {
                    $startDate = Carbon::now();
                    $endDate = $this->calculateEndDate(Carbon::now(), $subscriptionPlan);
                }

                // if trial is running (is_trial_taken = 0 means trial is running) and want to purchase new package then we have to end the trial period and current date will be the trial_end_date
                if ($subscription->is_trial_taken == false) {
                    $validatedData['trial_end_date'] = Carbon::now();
                    $validatedData['is_trial_taken'] = 1;
                }

                $validatedData = array_merge($validatedData, [
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'price' => $subscriptionPlan->price,
                    'discount_amount' => $subscriptionPlan->discount_amount,
                    'final_price' => $subscriptionPlan->price - $subscriptionPlan->discount_amount,
                ]);

                $isUpdated = $this->update($subscription, $validatedData);
                if ($isUpdated) {
                    if (isset($validatedData['trial_end_date'])) {
                        unset($validatedData['trial_end_date']);
                    }

                    if (isset($validatedData['is_trial_taken'])) {
                        unset($validatedData['is_trial_taken']);
                    }
                    
                    $validatedData['subscription_id'] = $subscription->id;
                    $this->subscriptionHistoryService->create($validatedData);
                }
            }
            return $subscription;
        });

        return $result;
    }

    private function calculateEndDate($startDate, $plan)
    {
        $parsedStartDate = Carbon::parse($startDate);
        return $parsedStartDate->addMonths($plan->duration);
    }
    
    public function syncData($request)
    {
        $subscription = $request->all();
        return $this->model()::create($subscription);
    }

    public static function formatSubscriptionData(Subscription $subscription)
    {
        $subscriptionArray = $subscription->toArray();
        $subscriptionArray['start_date'] = Carbon::parse($subscription->start_date)->toDateString();
        $subscriptionArray['end_date'] = Carbon::parse($subscription->end_date)->toDateString();
        $subscriptionArray['trial_start_date'] = Carbon::parse($subscription->trial_start_date)->toDateString();
        $subscriptionArray['trial_end_date'] = Carbon::parse($subscription->trial_end_date)->toDateString();

        // we have mapped the supscription_plan_id to central_id because here central_id = central-admin subscription_plans table id
        $subscriptionArray['subscription_plan_id'] = $subscription->subscriptionPlan->central_id;
        return $subscriptionArray;
    }
}
