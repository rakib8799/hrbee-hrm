<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">

        <ConfigurationHeader activeLink="Details" :configuration="props?.configuration"></ConfigurationHeader>

        <!--begin::Basic Info-->
        <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
            <!--begin::Card header-->
            <div class="card-header cursor-pointer">
                <!--begin::Card title-->
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">{{ $t('configuration.header.show.basicInformation') }}</h3>
                </div>
                <!--end::Card title-->
                <!-- Edit -->
                <Link v-if="checkPermission('can-edit-configuration')" :href="route('configurations.basicInfo')" class="btn btn-icon btn-flex btn-active-light-primary w-30px h-30px align-self-center" data-bs-toggle="tooltip" :title="$t('tooltip.title.edit')">
                    <KTIcon icon-name="pencil" icon-class="fs-3 text-primary" />
                </Link>
            </div>
            <!--end::Card header-->

            <!--begin::Card body-->
            <div class="card-body p-9">
                <!-- Company Name -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('configuration.label.companyName') }}</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-900">{{ props.configuration?.name }}</span>
                    </div>
                </div>

                <!-- Industry Name -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('configuration.label.industryName') }}</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-900">{{ props.configuration?.industry }}</span>
                    </div>
                </div>

                <!-- Organization Number -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('configuration.label.organizationNumber') }}</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-900">{{ props.configuration?.organization_number }}</span>
                    </div>
                </div>

                <!-- Country -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">
                        {{ $t('configuration.label.country') }}
                        <i class="fas fa-exclamation-circle ms-1 fs-7" v-tooltip :title="$t('configuration.header.title.tooltip')"></i>
                    </label>
                    <div class="col-lg-8">
                        <span class="fw-semibold fs-6 text-gray-900">{{ props.configuration?.country_name}}</span>
                    </div>
                </div>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Basic Info-->

        <!--begin::Additional Info-->
        <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
            <!--begin::Card header-->
            <div class="card-header cursor-pointer">
                <!--begin::Card title-->
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">{{ $t('configuration.header.show.additionalInformation') }}</h3>
                </div>
                <!--end::Card title-->
                <Link v-if="checkPermission('can-edit-configuration')" :href="route('configurations.additionalInfo')" class="btn btn-icon btn-flex btn-active-light-primary w-30px h-30px align-self-center" data-bs-toggle="tooltip" :title="$t('tooltip.title.edit')">
                    <KTIcon icon-name="pencil" icon-class="fs-3 text-primary" />
                </Link>
            </div>
            <!--begin::Card header-->

            <!--begin::Card body-->
            <div class="card-body p-9">
                <!-- Other Language -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('configuration.label.otherLanguage') }}</label>
                    <div class="col-lg-8">
                        <span v-if="props.configuration?.other_language_id !== ''">
                            <span v-for="language in props?.languages" :key="language.id" class="fw-semibold fs-6 text-gray-900">
                                <span v-if="language.id == props.configuration?.other_language_id">{{ language.name }}</span>
                                <span v-else>{{ '' }}</span>
                            </span>
                        </span>
                    </div>
                </div>

                <!-- Date Format -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('configuration.label.dateFormat') }}</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-900">{{ props.configuration?.date_format }}</span>
                    </div>
                </div>

                <!-- Week Start Day -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('configuration.label.weekStartDay') }}</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-900">{{ capitalizeString(props.configuration?.week_start_day ?? '') }}</span>
                    </div>
                </div>

                <!-- Weekends -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('configuration.label.weekends') }}</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-900">
                            {{ capitalizeArrayOfStrings(props.configuration?.weekends)?.join(', ') }}
                        </span>
                    </div>
                </div>

                <!-- Lunch Break Time -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('configuration.label.lunchBreakTime') }}</label>
                    <div class="col-lg-8">
                        <span class="fw-bold fs-6 text-gray-900">{{ props.configuration?.lunch_break_time }}</span>
                    </div>
                </div>

                <!-- Is Roster -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('configuration.label.isRoster') }}</label>
                    <div class="col-lg-8 fv-row">
                        <span class="badge" :class="{ 'badge-success': props.configuration?.is_roster, 'badge-danger': !props.configuration?.is_roster }"> {{ props.configuration?.is_roster ? 'Active' : 'Inactive' }}</span>
                    </div>
                </div>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Additional Info-->

        <!--begin::Contact Info-->
        <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
            <!--begin::Card header-->
            <div class="card-header cursor-pointer">
                <!--begin::Card title-->
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">{{ $t('configuration.header.show.contactInformation') }}</h3>
                </div>
                <!--end::Card title-->
                <Link v-if="checkPermission('can-edit-configuration')" :href="route('configurations.contactInfo')" class="btn btn-icon btn-flex btn-active-light-primary w-30px h-30px align-self-center" data-bs-toggle="tooltip" :title="$t('tooltip.title.edit')">
                    <KTIcon icon-name="pencil" icon-class="fs-3 text-primary" />
                </Link>
            </div>
            <!--begin::Card header-->

            <!--begin::Card body-->
            <div class="card-body p-9">
                <!-- Email -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('configuration.label.email') }}</label>
                    <div class="col-lg-8 fv-row">
                        <span class="fw-bold fs-6">{{ props.configuration?.email }}</span>
                    </div>
                </div>

                <!-- Admin Email -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('configuration.label.adminEmail') }}</label>
                    <div class="col-lg-8 fv-row">
                        <span class="fw-bold fs-6">{{ props.configuration?.admin_email }}</span>
                    </div>
                </div>

                <!-- Website -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('configuration.label.website') }}</label>
                    <div class="col-lg-8 fv-row">
                        <span class="fw-bold fs-6">{{ props.configuration?.website }}</span>
                    </div>
                </div>

                <!-- Telephone Number -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('configuration.label.telephoneNumber') }}</label>
                    <div class="col-lg-8 fv-row">
                        <span class="fw-bold fs-6">{{ props.configuration?.telephone }}</span>
                    </div>
                </div>

                <!-- Address -->
                <div class="row mb-7">
                    <label class="col-lg-4 fw-semibold text-muted">{{ $t('configuration.label.address') }}</label>
                    <div class="col-lg-8">
                        <span class="fw-semibold fs-6 text-gray-900" v-if="props.configuration?.address_line_one || props.configuration?.address_line_two || props.configuration?.floor || props.configuration?.city || props.configuration?.state || props.configuration?.zip_code">Address-1: {{ props.configuration?.address_line_one }}, Address-2: {{ props.configuration?.address_line_two }}, Floor: {{ props.configuration?.floor }}, City: {{ props.configuration?.city }}, State: {{ props.configuration?.state }}, ZIP: {{ props.configuration?.zip_code }}</span>
                    </div>
                </div>
            </div>
            <!--end::Card body-->
        </div>
        <!--end::Additional Info-->
    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfigurationHeader from '@/Pages/Configuration/ConfigurationHeader.vue';
import KTIcon from "@/Core/helpers/kt-icon/KTIcon.vue";
import { Link } from '@inertiajs/vue3';
import { checkPermission } from "@/Core/helpers/Permission";

const props = defineProps({
    configuration: Object,
    languages: Object,
    breadcrumbs: Array as() => Breadcrumb[],
    pageTitle: String,
});

interface Breadcrumb {
    url: string;
    title: string;
}

const capitalizeString = (str: string) => str?.charAt(0).toUpperCase() + str?.slice(1);
const capitalizeArrayOfStrings = (str: string[]) => str?.map((str: string) => str?.charAt(0).toUpperCase() + str?.slice(1));
</script>
