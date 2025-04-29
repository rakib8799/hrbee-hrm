<template>

    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">

        <LeaveTypeHeader activeLink="Details" :id="`${props.leaveType?.id}`"></LeaveTypeHeader>

        <!--begin::Leave Type Info-->
        <div class="card mb-5 mb-xl-10">
            <!--begin::Card header-->
            <div class="card-header cursor-pointer">
                <!--begin::Card title-->
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">{{ $t('leave.header.leaveType.leaveTypeInfo.description') }}</h3>
                </div>
                <!--end::Card title-->
                <!-- Edit -->
                <Link v-if="checkPermission('can-edit-leave-type')" :href="route('leave-types.edit', props?.leaveType?.id)" class="btn btn-icon btn-flex btn-active-light-primary w-30px h-30px align-self-center" data-bs-toggle="tooltip" :title="$t('tooltip.title.edit')">
                    <KTIcon icon-name="pencil" icon-class="fs-3 text-primary" />
                </Link>
            </div>
            <!--end::Card header-->

            <!--begin::Card body-->
            <div class="card-body p-9">
                <!-- Name -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('leave.label.name') }}</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-900">{{ props.leaveType?.name }}</span>
                    </div>
                </div>

                <!-- Color -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('leave.label.color') }}</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-900">{{ props.leaveType?.color }}</span>
                    </div>
                </div>

                <!-- Sequence -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('leave.label.sequence') }}</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-900">{{ props.leaveType?.sequence }}</span>
                    </div>
                </div>

                <!-- Leave Validation Type -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('leave.label.leaveValidationType') }}</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-900">{{ props.leaveType?.leave_validation_type }}</span>
                    </div>
                </div>

                <!-- Request Unit -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('leave.label.requestUnit') }}</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-900">{{ props.leaveType?.request_unit }}</span>
                    </div>
                </div>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Leave Type Info-->
    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import LeaveTypeHeader from '@/Pages/LeaveType/LeaveTypeHeader.vue';
import KTIcon from "@/Core/helpers/kt-icon/KTIcon.vue";
import { Link } from '@inertiajs/vue3';
import { checkPermission } from "@/Core/helpers/Permission";

const props = defineProps({
    leaveType: Object,
    breadcrumbs: Array as() => Breadcrumb[],
    pageTitle: String,
});

interface Breadcrumb {
    url: string;
    title: string;
}
</script>
