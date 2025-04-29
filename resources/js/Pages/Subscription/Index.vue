<template>
    <!--begin::Layout-->
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">

        <div class="d-flex flex-column flex-lg-row">
            <!--begin::Content-->
            <div class="flex-lg-row-fluid me-lg-15 order-2 order-lg-1 mb-10 mb-lg-0">
            <Details :subscription="props?.subscription" :subscriptionHistories="props?.subscriptionHistories" :configuration="props?.configuration"></Details>
            </div>
            <!--end::Content-->

            <!--begin::Sidebar-->
            <div
            class="flex-column flex-lg-row-auto w-lg-250px w-xl-300px mb-10 order-1 order-lg-2"
            >
            <CurrentSubscription :subscription="props?.subscription"></CurrentSubscription>
            </div>
            <!--end::Sidebar-->
        </div>

    </AuthenticatedLayout>
    <!--end::Layout-->
</template>

<script lang="ts" setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Details from "@/Pages/Subscription/Details.vue";
import CurrentSubscription from "@/Pages/Subscription/CurrentSubscription.vue";
import { defineProps } from "vue";
import { ISubscriptionHistory } from '@/Core/helpers/Interfaces';

const props = defineProps({
    subscription: Object,
    subscriptionHistories: Object as() => ISubscriptionHistory[] | undefined,
    configuration: Object,
    breadcrumbs: Array as() => Breadcrumb[],
    pageTitle: String
});

interface Breadcrumb {
    url: string;
    title: string;
}
</script>
