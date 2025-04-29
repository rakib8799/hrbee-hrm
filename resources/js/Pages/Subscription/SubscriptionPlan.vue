<template>
    <!--begin::Pricing-->
    <div class="card card-flush pt-3 mb-5 mb-lg-10">
        <!--begin::Card header-->
        <div class="card-header">
            <!--begin::Card title-->
            <div class="card-title">
                <h2 class="fw-bold">{{ $t('subscription.header.subscriptionPlan') }}</h2>
            </div>
            <!--begin::Card title-->
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body pt-0">
            <!--begin::Pricing-->
            <div class="text-center" id="kt_pricing">
                <!--begin::Nav group-->
                <div
                    class="nav-group landing-dark-bg d-inline-flex mb-15"
                    data-kt-buttons="true"
                    style="border: 1px dashed #2b4666"
                >
                    <a
                    href="#"
                    class="btn btn-color-gray-600 btn-active btn-active-success px-6 py-3 me-2"
                    :class="{ active: planType === MONTHLY }"
                    @click.prevent="toggleTab(MONTHLY)"
                    >
                        {{ $t('subscription.label.monthly') }}
                    </a>
                    <a
                        href="#"
                        class="btn btn-color-gray-600 btn-active btn-active-success px-6 py-3"
                        :class="{ active: planType === YEARLY }"
                        @click.prevent="toggleTab(YEARLY)"
                    >
                        {{ $t('subscription.label.annual') }}
                    </a>
                </div>

                <div class="row g-10">
                    <!-- Monthly Subscription Plan -->
                    <div
                    v-for="plan in props?.subscriptionPlans?.subscriptionPlansMonthly"
                    :key="plan?.slug"
                    v-show="planType === MONTHLY"
                    class="col-xl-4 monthlyTabContent"
                    >
                    <div class="d-flex h-100 align-items-center">
                        <!--begin::Option-->
                        <div class="w-100 d-flex flex-column flex-center rounded-3 bg-body py-15 px-10">
                        <!--begin::Heading-->
                        <div class="mb-7 text-center">
                            <!--begin::Title-->
                            <h1 class="text-gray-900 mb-5 fw-boldest">{{ plan?.name }}</h1>
                            <!--end::Title-->
                            <!--begin::Description-->
                            <div class="text-gray-500 fw-semibold mb-5">{{ plan?.description }}</div>
                            <!--end::Description-->
                            <!--begin::Price-->
                            <div class="text-center">
                            <span class="mb-2 text-primary">$</span>
                            <span class="fs-3x fw-bold text-primary">{{ plan?.price }}</span>
                            <span class="fs-7 fw-semibold opacity-50" data-kt-plan-price-month="/ Mon">/ Mon</span>
                            </div>
                            <!--end::Price-->
                        </div>
                        <!--end::Heading-->
                        <!--begin::Features-->
                        <div class="w-100 mb-10">
                            <div class="d-flex align-items-center mb-5">    
                                <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">Up to {{ plan?.max_active_users }} active users</span>
                                <i class="ki-duotone ki-check-circle fs-1 text-success">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                            <div v-for="feature in props?.subscriptionPlanFeatures" :key="feature?.id" class="d-flex align-items-center mb-5"> 
                                <span :class="{'fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3': isFeatureIncluded(plan, feature),
                                'fw-semibold fs-6 text-gray-600 flex-grow-1': !isFeatureIncluded(plan, feature)
                                }">{{ feature?.title }}</span>
                                <i :class="{
                                    'ki-duotone ki-check-circle fs-1 text-success': isFeatureIncluded(plan, feature),
                                    'ki-duotone ki-cross-circle fs-1': !isFeatureIncluded(plan, feature)
                                }">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i> 
                            </div>
                        </div>
                        <!--end::Features-->
                        <!--begin::Select-->
                        <div class="card-footer d-flex justify-content-end py-6 px-9">
                            <SubmitButton v-if="!isCurrentSubscription(plan.id)" :buttonValue="$t('buttonValue.select')" @click.prevent="selectPlan(plan)"/>
                            <button v-else class="btn btn-secondary" disabled>Current</button>
                        </div>
                        <!--end::Select-->
                        </div>
                        <!--end::Option-->
                    </div>
                    </div>

                    <!-- Annual Subscription Plan -->
                    <div
                    v-for="plan in props?.subscriptionPlans?.subscriptionPlansYearly"
                    :key="plan?.slug"
                    v-show="planType === YEARLY"
                    class="col-xl-4 annualyTabContent"
                    >
                    <div class="d-flex h-100 align-items-center">
                        <!--begin::Option-->
                        <div class="w-100 d-flex flex-column flex-center rounded-3 bg-body py-15 px-10">
                        <!--begin::Heading-->
                        <div class="mb-7 text-center">
                            <!--begin::Title-->
                            <h1 class="text-gray-900 mb-5 fw-boldest">{{ plan?.name }}</h1>
                            <!--end::Title-->
                            <!--begin::Description-->
                            <div class="text-gray-500 fw-semibold mb-5">{{ plan?.description }}</div>
                            <!--end::Description-->
                            <!--begin::Price-->
                            <div class="text-center">
                            <span class="mb-2 text-primary">$</span>
                            <span class="fs-3x fw-bold text-primary">{{ plan?.price }}</span>
                            <span class="fs-7 fw-semibold opacity-50" data-kt-plan-price-annual="/ Ann">/ Ann</span>
                            </div>
                            <!--end::Price-->
                        </div>
                        <!--end::Heading-->
                        <!--begin::Features-->
                        <div class="w-100 mb-10">
                            <div class="d-flex align-items-center mb-5">    
                                <span class="fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3">Up to {{ plan?.max_active_users }} active users</span>
                                <i class="ki-duotone ki-check-circle fs-1 text-success">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                            <div v-for="feature in props?.subscriptionPlanFeatures" :key="feature?.id" class="d-flex align-items-center mb-5"> 
                                <span :class="{'fw-semibold fs-6 text-gray-800 flex-grow-1 pe-3': isFeatureIncluded(plan, feature),
                                'fw-semibold fs-6 text-gray-600 flex-grow-1': !isFeatureIncluded(plan, feature)
                                }">{{ feature?.title }}</span>
                                <i :class="{
                                    'ki-duotone ki-check-circle fs-1 text-success': isFeatureIncluded(plan, feature),
                                    'ki-duotone ki-cross-circle fs-1': !isFeatureIncluded(plan, feature)
                                }">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i> 
                            </div>
                        </div>
                        <!--end::Features-->
                        <!--begin::Select-->
                        <div class="card-footer d-flex justify-content-end py-6 px-9">
                            <SubmitButton v-if="checkPermission('can-edit-subscription') && !isCurrentSubscription(plan.id)" :buttonValue="$t('buttonValue.select')" @click.prevent="selectPlan(plan)"/>
                            <button v-else class="btn btn-secondary" disabled>Current</button>
                        </div>
                        <!--end::Select-->
                        </div>
                        <!--end::Option-->
                    </div>
                    </div>
                </div>
                <!--end::Row-->
            </div>
            <!--end::Pricing-->
        </div>
        <!--end::Card body-->
    </div>
    <!--end::Pricing-->
</template>

<script lang="ts" setup>
import { useForm } from "@inertiajs/vue3";
import { defineProps, onMounted, ref } from "vue";
import SubmitButton from "@/Components/Button/SubmitButton.vue";
import { checkPermission } from "@/Core/helpers/Permission";

const MONTHLY = 'monthly';
const YEARLY = 'yearly';
const planType = ref(MONTHLY);
const props = defineProps({
    subscription: Object,
    subscriptionPlans: Object,
    subscriptionPlanFeatures: Object
});

onMounted(() => {
    if (props.subscription) {
        const currentPlanId = props?.subscription?.subscription_plan_id;
        const isMonthlyPlan = props?.subscriptionPlans?.subscriptionPlansMonthly?.some((plan: { id: any; }) => plan.id === currentPlanId);
        planType.value = isMonthlyPlan ? MONTHLY : YEARLY;
    }
});

const toggleTab = (type: string) => {
    planType.value = type;
};

const isCurrentSubscription = (planId: number) => {
    return props.subscription?.subscription_plan_id === planId;
};

// Define the method to check if a feature is included in a plan
const isFeatureIncluded = (plan: any, feature: any): boolean => {
  return plan.subscription_plan_features.some(
    (planFeature: { id: number }) => planFeature.id === feature.id
  );
};

const selectPlan = (plan: any) => {
    const formData = useForm({
        subscription_plan_id: plan.id
    });

    formData.put(route('subscriptions.update', props?.subscription?.id), {
        preserveScroll: true,
    });
};
</script>
