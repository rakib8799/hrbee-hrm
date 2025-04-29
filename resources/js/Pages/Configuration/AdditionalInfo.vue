<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">

        <ConfigurationHeader activeLink="AdditionalInfo" :configuration="props?.configuration"></ConfigurationHeader>

        <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">

            <VForm @submit="submit()" class="form">
                <!--begin::Card header-->
                <div class="card-header cursor-pointer">
                        <!--begin::Card title-->
                        <div class="card-title m-0">
                            <h3 class="fw-bold m-0">{{ $t('configuration.header.title.additionalInformation') }}</h3>
                        </div>
                        <!--end::Card title-->
                    </div>
                    <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body border-top p-9">
                    <div>
                        <!-- Other Language -->
                        <div class="fv-row mb-7">
                            <label class="fs-5 fw-semibold">{{ $t('configuration.label.otherLanguage') }}</label>
                            <Multiselect
                                :placeholder="$t('configuration.placeholder.otherLanguage')"
                                v-model="formData.other_language_id"
                                :searchable="true"
                                :options="allLanguages"
                                label="text"
                                trackBy="text"
                            />
                        </div>

                        <!-- Timezone -->
                        <div class="fv-row mb-7">
                            <label class="fs-5 fw-semibold">{{ $t('configuration.label.timezone') }}</label>
                            <Multiselect
                                :placeholder="$t('configuration.placeholder.timezone')"
                                v-model="formData.timezone_id"
                                :searchable="true"
                                :options="allTimezones"
                                label="text"
                                trackBy="text"
                            />
                        </div>

                        <!-- Date Format -->
                        <div class="fv-row mb-7">
                            <label class="fs-5 fw-semibold">{{ $t('configuration.label.dateFormat') }}</label>
                            <Multiselect
                                :placeholder="$t('configuration.placeholder.dateFormat')"
                                v-model="formData.date_format"
                                :searchable="true"
                                :options="dateFormats"
                                label="text"
                                trackBy="text"
                            />
                        </div>

                        <!-- Week Start Day -->
                        <div class="fv-row mb-7">
                            <label class="fs-5 fw-semibold">{{ $t('configuration.label.weekStartDay') }}</label>
                            <Multiselect
                                :placeholder="$t('configuration.placeholder.weekStartDay')"
                                v-model="formData.week_start_day"
                                :searchable="true"
                                :options="weekDays"
                                label="text"
                                trackBy="text"
                            />
                        </div>

                        <!-- Weekends -->
                        <div class="fv-row mb-7">
                            <label class="fs-5 fw-semibold">{{ $t('configuration.label.weekends') }}</label>
                            <Multiselect
                                :placeholder="$t('configuration.placeholder.weekends')"
                                mode="tags"
                                v-model="formData.weekends"
                                :searchable="true"
                                :options="weekDays"
                                label="text"
                                trackBy="text"
                            />
                        </div>

                        <!-- Lunch Break Time -->
                        <div class="fv-row mb-7">
                            <label class="fs-5 fw-semibold">{{ $t('configuration.label.lunchBreakTime') }}</label>
                            <Field type="number" :placeholder="$t('configuration.placeholder.lunchBreakTime')" name="name" class="form-control form-control-lg form-control-solid" v-model="formData.lunch_break_time"/>
                            <ErrorMessage :errorMessage="formData.errors.lunch_break_time"/>
                        </div>

                        <!-- Is Roster -->
                        <div class="fv-row mb-7">
                            <input class="form-check-input me-3" name="is_roster" type="checkbox" v-model="formData.is_roster" :checked="formData.is_roster" id="is_roster"/>
                            <label class="fs-5 fw-semibold mb-2" for="is_roster">{{ $t('configuration.label.isRoster') }}</label>
                        </div>
                    </div>
                </div>
                <!--end::Card body-->

                <!-- Submit Button-->
                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <SubmitButton :buttonValue="$t('buttonValue.update')" />
                </div>
            </VForm>
        </div>
    </AuthenticatedLayout>
</template>

<script lang="ts" setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ConfigurationHeader from '@/Pages/Configuration/ConfigurationHeader.vue';
import {Field, Form as VForm} from "vee-validate";
import {useForm} from '@inertiajs/vue3';
import SubmitButton from "@/Components/Button/SubmitButton.vue";
import Multiselect from '@vueform/multiselect';
import { ref } from 'vue';

const props = defineProps({
    languages: Object,
    timezones: Object,
    dateFormats: Object,
    weekDays: Object,
    configuration: Object,
    breadcrumbs: Array as () => Breadcrumb[],
    pageTitle: String,
});

interface Breadcrumb {
    url: string;
    title: string;
}

// assign all the languages from props to allLanguages variable.
const allLanguages = ref<Array<any>>([]);
if (Array.isArray(props.languages) && props.languages.length > 0) {
    allLanguages.value = props.languages.map((otherLanguage: { id: any; name: any; }) => ({value: otherLanguage.id, text:otherLanguage.name}));
}

// assign all the timezones from props to allTimezones variable.
const allTimezones = ref<Array<any>>([]);
if (Array.isArray(props.timezones) && props.timezones.length > 0) {
    allTimezones.value = props.timezones.map((timezone: { id: any; name: any; }) => ({value: timezone.id, text:timezone.name}));
}

const formData = useForm({
    other_language_id: props.configuration?.other_language_id || '',
    timezone_id: props.configuration?.timezone_id || '',
    date_format: props.configuration?.date_format || '',
    week_start_day: props.configuration?.week_start_day || '',
    weekends: props.configuration?.weekends || [],
    lunch_break_time: props.configuration?.lunch_break_time || '',
    is_roster: props.configuration?.is_roster ? true : false
});

const submit = () => {
    formData.patch(route('configurations.updateAdditionalInfo', props.configuration?.id), {
        preserveScroll: true,
    });
};
</script>
