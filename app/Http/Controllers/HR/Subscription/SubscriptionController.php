<?php

namespace App\Http\Controllers\HR\Subscription;

use Exception;
use Inertia\Inertia;
use App\Constants\Constants;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\Core\HelperService;
use App\Services\ConfigurationService;
use Diglactic\Breadcrumbs\Breadcrumbs;
use Illuminate\Support\Facades\Redirect;
use App\Models\Subscription\Subscription;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Services\CentralAdmin\CentralAdminService;
use App\Services\Subscription\SubscriptionService;
use App\Services\Subscription\SubscriptionPlanService;
use App\Services\Subscription\SubscriptionHistoryService;
use App\Http\Requests\Subscription\UpdateSubscriptionRequest;
use App\Services\Subscription\SubscriptionPlanFeatureService;

class SubscriptionController extends Controller implements HasMiddleware
{
    protected SubscriptionService $subscriptionService;
    protected SubscriptionPlanService $subscriptionPlanService;
    protected SubscriptionHistoryService $subscriptionHistoryService;
    protected ConfigurationService $configurationService;
    protected CentralAdminService $centralAdminService;
    protected SubscriptionPlanFeatureService $subscriptionPlanFeatureService;

    public function __construct(SubscriptionService $subscriptionService, SubscriptionPlanService $subscriptionPlanService, SubscriptionHistoryService $subscriptionHistoryService, ConfigurationService $configurationService, CentralAdminService $centralAdminService, SubscriptionPlanFeatureService $subscriptionPlanFeatureService)
    {
        $this->subscriptionService = $subscriptionService;
        $this->subscriptionPlanService = $subscriptionPlanService;
        $this->subscriptionHistoryService = $subscriptionHistoryService;
        $this->configurationService = $configurationService;
        $this->centralAdminService = $centralAdminService;
        $this->subscriptionPlanFeatureService = $subscriptionPlanFeatureService;
    }

    public static function middleware(): array
    {
        return [
            new Middleware('permission:can-view-subscription', only: ['index']),
            new Middleware('permission:can-edit-subscription', only: ['edit'])
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $breadcrumbs = Breadcrumbs::generate('subscriptions');
        $subscription = $this->subscriptionService->getSubscription();
        $subscriptionHistories = $this->subscriptionHistoryService->getSubscriptionHistories();
        $configuration = $this->configurationService->getCompanyPublicConfiguration();
        $responseData = [
            'subscription' => $subscription,
            'subscriptionHistories' => $subscriptionHistories,
            'configuration' => $configuration,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.subscription.index'),
        ];
        return Inertia::render('Subscription/Index', $responseData);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subscription $subscription)
    {
        $breadcrumbs = Breadcrumbs::generate('editSubscription', $subscription);
        $subscription = $this->subscriptionService->getSubscription();
        $subscriptionPlans = $this->subscriptionPlanService->getActiveSubscriptionPlans();
        $subscriptionPlanFeatures = $this->subscriptionPlanFeatureService->getSubscriptionPlanFeatures();
        $responseData = [
            'subscription' => $subscription,
            'subscriptionPlans' => $subscriptionPlans,
            'subscriptionPlanFeatures' => $subscriptionPlanFeatures,
            'breadcrumbs' => $breadcrumbs,
            'pageTitle' => __('pageTitle.custom.subscription.edit')
        ];
        return Inertia::render('Subscription/Edit', $responseData);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSubscriptionRequest $request, Subscription $subscription)
    {
        $validatedData = $request->validated();
        $updatedSubscription = $this->subscriptionService->updateSubscription($subscription, $validatedData);
        if ($updatedSubscription) {
            try {
                $subscriptionData = $this->subscriptionService->formatSubscriptionData($updatedSubscription);
                $this->centralAdminService->syncSubscription($subscriptionData);
            } catch (Exception $e) {
                HelperService::captureException($e);
            }
        }
        $status = $updatedSubscription ? Constants::SUCCESS : Constants::ERROR;
        $message = $updatedSubscription ? __('message.custom.subscription.update.success') : __('message.custom.subscription.update.error');
        return Redirect::route('subscriptions.index')->with($status, $message);
    }
}
