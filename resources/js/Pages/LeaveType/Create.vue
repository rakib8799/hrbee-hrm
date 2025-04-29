<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">

        <LeaveTypeHeader :activeLink="isEditRoute ? 'EditLeaveType' : 'CreateLeaveType'" :id="isEditRoute ? props.leaveType?.id : null"></LeaveTypeHeader>

        <div class="card mb-5 mb-xl-10">
            <!--begin::Card header-->
            <div class="card-header border-0 cursor-pointer">
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">{{ props?.leaveType?.id ? $t('leave.header.leaveType.leaveTypeInfo.edit') :  $t('leave.header.leaveType.leaveTypeInfo.add') }}</h3>
                </div>
            </div>
            <!--end::Card header-->

            <div class="show">
                <VForm @submit="submit()" class="form">
                    <!--begin::Card body-->
                    <div class="card-body border-top p-9">
                        <!-- Name -->
                        <div class="row mb-5 g-4">
                            <div class="col-md-12 fv-row">
                                <label class="required fs-5 fw-semibold mb-2">{{ $t('leave.label.name') }}</label>
                                <Field type="text" :placeholder="$t('leave.placeholder.name')" class="form-control form-control-lg form-control-solid" name="name" v-model="formData.name"/>
                                <ErrorMessage :errorMessage="formData.errors.name" />
                            </div>
                        </div>

                        <!-- Color and Sequence-->
                        <div class="row mb-5 g-4">
                            <!-- Color -->
                            <div class="col-md-6 fv-row">
                                <label class="required fs-5 fw-semibold mb-2">{{ $t('leave.label.color') }}</label>
                                <Multiselect
                                    :placeholder="$t('leave.placeholder.color')"
                                    v-model="formData.color"
                                    :searchable="true"
                                    :options="allLeaveTypeColors"
                                    trackBy="value"
                                    label="text"
                                >
                                    <template #option="{ option }">
                                        <div class="d-flex align-items-center">
                                            <span class="me-2" style="min-width: 6vw;">{{ option.text }}</span>
                                            <input type="color" v-model="option.value" disabled />
                                        </div>
                                    </template>
                                </Multiselect>
                                <ErrorMessage :errorMessage="formData.errors.color" />
                            </div>

                            <!-- Sequence -->
                            <div class="col-md-6 fv-row">
                                <label class="required fs-5 fw-semibold mb-2">{{ $t('leave.label.sequence') }}</label>
                                <Field type="number" :placeholder="$t('leave.placeholder.sequence')" class="form-control form-control-lg form-control-solid" name="sequence" v-model="formData.sequence"/>
                                <ErrorMessage :errorMessage="formData.errors.sequence" />
                            </div>
                        </div>

                        <!-- Leave Validation Type and Request Unit-->
                        <div class="row mb-5 g-4">
                            <!-- Leave Validation Type -->
                            <div class="col-md-6 fv-row">
                                <label class="required fs-5 fw-semibold mb-2">{{ $t('leave.label.leaveValidationType') }}</label>
                                <Multiselect
                                    :placeholder="$t('leave.placeholder.leaveValidationType')"
                                    v-model="formData.leave_validation_type"
                                    :searchable="true"
                                    :options="allLeaveValidationTypes"
                                    label="text"
                                    trackBy="text"
                                />
                                <ErrorMessage :errorMessage="formData.errors.leave_validation_type" />
                            </div>

                            <!-- Request Unit -->
                            <div class="col-md-6 fv-row">
                                <label class="required fs-5 fw-semibold mb-2">{{ $t('leave.label.requestUnit') }}</label>
                                <Multiselect
                                    :placeholder="$t('leave.placeholder.requestUnit')"
                                    v-model="formData.request_unit"
                                    :searchable="true"
                                    :options="allRequestUnits"
                                    label="text"
                                    trackBy="text"
                                />
                                <ErrorMessage :errorMessage="formData.errors.request_unit" />
                            </div>
                        </div>
                    </div>
                    <!--end::Card body-->

                    <!-- Submit Button-->
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <SubmitButton :id="props.leaveType?.id" />
                    </div>
                </VForm>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script lang="ts" setup>
import { useForm, usePage } from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Field, Form as VForm } from "vee-validate";
import ErrorMessage from "@/Components/Message/ErrorMessage.vue";
import SubmitButton from "@/Components/Button/SubmitButton.vue";
import LeaveTypeHeader from '@/Pages/LeaveType/LeaveTypeHeader.vue';
import Multiselect from '@vueform/multiselect';
import { ref } from 'vue';

const props = defineProps({
    leaveType: Object,
    leaveTypeColors: Object,
    leaveValidationTypes: Object,
    requestUnits: Object,
    breadcrumbs: Array as() => Breadcrumb[],
    pageTitle: String
});

interface Breadcrumb {
    url: string;
    title: string;
}

const formData = useForm({
    name: props.leaveType?.name || '',
    color: props.leaveType?.color || '',
    sequence: props.leaveType?.sequence || '',
    leave_validation_type: props.leaveType?.leave_validation_type || '',
    request_unit: props.leaveType?.request_unit || ''
});

// assign all the leaveTypeColors from props to allLeaveTypeColors variable.
const allLeaveTypeColors = ref<Array<any>>([]);
if (Array.isArray(props.leaveTypeColors) && props.leaveTypeColors.length > 0) {
    allLeaveTypeColors.value = props.leaveTypeColors.map(leaveTypeColor => ({value: leaveTypeColor.value, text: leaveTypeColor.text}));
}

// assign all the leaveValidationTypes from props to allLeaveValidationTypes variable.
const allLeaveValidationTypes = ref<Array<any>>([]);
if (Array.isArray(props.leaveValidationTypes) && props.leaveValidationTypes.length > 0) {
    allLeaveValidationTypes.value = props.leaveValidationTypes.map(leaveValidationType => ({value: leaveValidationType.value, text: leaveValidationType.text}));
}

// assign all the requestUnits from props to allRequestUnits variable.
const allRequestUnits = ref<Array<any>>([]);
if (Array.isArray(props.requestUnits) && props.requestUnits.length > 0) {
    allRequestUnits.value = props.requestUnits.map(requestUnit => ({value: requestUnit.value, text: requestUnit.text}));
}

const {url} = usePage();

const isEditRoute = url.includes(`leave-types/${props.leaveType?.id}/edit`);

const submit = () => {
    if (props.leaveType?.id) {
        // for updating leave type
        formData.put(route('leave-types.update', props.leaveType?.id), {
            preserveScroll: true,
        });
    } else {
        // for adding new leave type
        formData.post(route('leave-types.store'), {
            preserveScroll: true,
        });
    }
};
</script>

