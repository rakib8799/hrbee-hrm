<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">

        <LeaveTypeHeader activeLink="CreateLeaveTypeAllocation" :id="props.leaveType?.id"></LeaveTypeHeader>

        <div class="card mb-5 mb-xl-10">
            <!--begin::Card header-->
            <div class="card-header border-0 cursor-pointer">
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">{{ $t('leave.header.leaveType.leaveTypeAllocationInfo.add') }}</h3>
                </div>
            </div>
            <!--end::Card header-->

            <div class="show">
                <VForm @submit="submit()" class="form">
                    <!--begin::Card body-->
                    <div class="card-body border-top p-9">
                        <!-- Name and Leave Type -->
                        <div class="row mb-5 g-4">
                            <!-- Name -->
                            <div class = "col-md-6 fv-row">
                                <label class="required fs-5 fw-semibold mb-2">{{ $t('leave.label.name') }}</label>
                                <Field type="text" class="form-control form-control-lg form-control-solid" :placeholder="$t('leave.placeholder.name')" name="name" v-model="formData.name"/>
                                <ErrorMessage :errorMessage="formData.errors.name" />
                            </div>
                            <!-- Leave Type -->
                            <div class="col-md-6 fv-row">
                                <label class="required fs-5 fw-semibold mb-2">{{ $t('leave.label.leaveType') }}</label>
                                <input type="readonly" class="form-control form-control-lg form-control-solid" :placeholder="$t('leave.placeholder.leaveType')" :value="props.leaveType?.name" disabled/>
                                <Field type="hidden" name="leave_type_id"/>
                                <ErrorMessage :errorMessage="formData.errors.leave_type_id" />
                            </div>
                        </div>

                        <!-- Allocation Mode, Employees and Department -->
                        <div class="row mb-5 g-4">
                            <!-- Allocation Mode -->
                            <div class = "col-md-6 fv-row">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label class="required fs-5 fw-semibold mb-2">{{ $t('leave.label.allocationMode') }}</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <div v-for="(mode, index) in allocationModes" :key="index" class="col-md-12 mb-2 fv-row">
                                            <input class="form-check-input me-3" :name="mode.value" type="radio" v-model="formData.holiday_type" :value="mode.value"/>
                                            <label class="fs-5 fw-semibold mb-2">{{ mode.text }}</label>
                                        </div>
                                    </div>
                                </div>
                                <ErrorMessage :errorMessage="formData.errors.holiday_type" />
                            </div>

                            <!-- Employees -->
                            <div class="col-md-6 fv-row">
                                <div>
                                    <label class="fs-5 fw-semibold mb-2">{{ $t('leave.label.employee') }}</label>
                                    <Multiselect
                                        :placeholder="$t('leave.placeholder.employee')"
                                        mode="tags"
                                        v-model="formData.employee_id"
                                        :searchable="true"
                                        :options="filteredEmployees"
                                        label="name"
                                        trackBy="name"
                                        :disabled="formData.holiday_type === 'company' || formData.holiday_type === 'department'"
                                    />
                                    <ErrorMessage :errorMessage="formData.errors.employee_id" />
                                </div>

                                <!-- Department -->
                                <div class = "mt-3">
                                    <label class="fs-5 fw-semibold mb-2">{{ $t('leave.label.department') }}</label>
                                    <Multiselect
                                        :placeholder="$t('leave.placeholder.department')"
                                        v-model="formData.department_id"
                                        :searchable="true"
                                        :options="filteredDepartments"
                                        label="name"
                                        trackBy="name"
                                        :disabled="formData.holiday_type === 'company' || formData.holiday_type === 'employee'"
                                    />
                                    <ErrorMessage :errorMessage="formData.errors.department_id" />
                                </div>
                            </div>
                        </div>

                        <!-- Allocation Type and Total Allocation Days -->
                        <div class="row mb-5 g-4">
                            <!-- Allocation Type -->
                            <div class = "col-md-6 fv-row">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label class="required fs-5 fw-semibold mb-2">{{ $t('leave.label.allocationType') }}</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <div v-for="(type, index) in allocationTypes" :key="index" class="col-md-12 mb-2 fv-row">
                                            <input class="form-check-input me-3" :name="type.value" type="radio" v-model="formData.allocation_type" :value="type.value"/>
                                            <label class="fs-5 fw-semibold mb-2">{{ type.text }}</label>
                                        </div>
                                    </div>
                                </div>
                                <ErrorMessage :errorMessage="formData.errors.allocation_type" />
                            </div>

                            <!-- Total Allocation Days -->
                            <div class = "col-md-6 fv-row">
                                <label class="required fs-5 fw-semibold mb-2">{{ $t('leave.label.allocationDays') }}</label>
                                <Field type="number" class="form-control form-control-lg form-control-solid" :placeholder="$t('leave.placeholder.allocationDays')" name="number_of_days" v-model="formData.number_of_days"/>
                                <ErrorMessage :errorMessage="formData.errors.number_of_days" />
                            </div>
                        </div>

                        <!-- Validity Period -->
                        <div class="row mb-5 g-4">
                            <label class="required fs-5 fw-semibold mb-2">Validity Period</label>
                            <!-- Start Date -->
                            <div class="col-md-6 fv-row">
                                    <label class="fs-5 fw-semibold mb-2">Start Date:</label>
                                    <Field type="date" class="form-control form-control-lg form-control-solid" name="date_from" v-model="formData.date_from"/>
                                    <ErrorMessage :errorMessage="formData.errors.date_from" />
                                </div>

                            <!-- End Date -->
                            <div class="col-md-6 fv-row">
                                    <label class="fs-5 fw-semibold mb-2">End Date:</label>
                                    <Field type="date" class="form-control form-control-lg form-control-solid" name="date_to" v-model="formData.date_to"/>
                                    <ErrorMessage :errorMessage="formData.errors.date_to" />
                            </div>
                            <ErrorMessage :errorMessage="formData.errors.allocation_type" />
                        </div>

                        <!-- Note -->
                        <div class = "row mb-5 g-4">
                            <label class="fs-5 fw-semibold mb-2">Note</label>
                            <Field type="text" class="form-control form-control-lg form-control-solid" placeholder="Note" name="notes" v-model="formData.notes"/>
                            <ErrorMessage :errorMessage="formData.errors.notes" />
                        </div>
                    </div>
                    <!--end::Card body-->

                    <!-- Submit Button-->
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <SubmitButton />
                    </div>
                </VForm>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script lang="ts" setup>
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Field, Form as VForm } from "vee-validate";
import ErrorMessage from "@/Components/Message/ErrorMessage.vue";
import SubmitButton from "@/Components/Button/SubmitButton.vue";
import LeaveTypeHeader from '@/Pages/LeaveType/LeaveTypeHeader.vue';
import { computed, ref, onMounted } from 'vue';
import Multiselect from '@vueform/multiselect';
import { getFullName, getCurrentYearRange } from '@/Core/helpers/Helper';

const props = defineProps({
    employees: Object,
    departments: Object,
    leaveType: Object,
    allocationTypes: Object,
    allocationModes: Object,
    breadcrumbs: Array as() => Breadcrumb[],
    pageTitle: String
});

interface Breadcrumb {
    url: string;
    title: string;
}

// assign all the allocation modes from props to allAllocationModes variable.
const allAllocationModes = ref<Array<any>>([]);
if (Array.isArray(props.allocationModes) && props.allocationModes.length > 0) {
    allAllocationModes.value = props.allocationModes.map(allocationMode => ({value: allocationMode.value, name: allocationMode.text}));
}

// assign all the employees from props to allEmployees variable.
const allEmployees = ref<Array<any>>([]);
if (Array.isArray(props.employees) && props.employees.length > 0) {
    allEmployees.value = props.employees.map(employee => ({value: employee.id, name: getFullName(employee)}));
}

// assign all the departments from props to allDepartments variable.
const allDepartments = ref<Array<any>>([]);
if (Array.isArray(props.departments) && props.departments.length > 0) {
    allDepartments.value = props.departments.map(department => ({value: department.id, name: department.name}));
}

// assign all the allotcation types from props to allAllocationTypes variable.
const allAllocationTypes = ref<Array<any>>([]);
if (Array.isArray(props.allocationTypes) && props.allocationTypes.length > 0) {
    allAllocationTypes.value = props.allocationTypes.map(allocationType => ({value: allocationType.value, name: allocationType.text}));
}

const formData = useForm({
    name: '',
    leave_type_id: props.leaveType?.id,
    holiday_type: '',
    employee_id: [],
    department_id: '',
    allocation_type: 'regular',
    number_of_days: '',
    date_from: '',
    date_to: '',
    notes: ''
});

// Computed property to determine if the Employee list should be shown
const filteredEmployees = computed(() => {
    if(formData.holiday_type === 'employee') {
        return allEmployees.value;
    } else {
        formData.employee_id = []
        return [];
    }
});

// Computed property to determine if the Department list should be shown
const filteredDepartments = computed(() => {
    if(formData.holiday_type === 'department') {
        return allDepartments.value;
    } else {
        formData.department_id = ''
        return [];
    }
});

onMounted(() => {
    const { firstDay, lastDay } = getCurrentYearRange();
    formData.date_from = firstDay;
    formData.date_to = lastDay;
});

const submit = () => {
    formData.post(route('leave-types.storeLeaveTypeAllocation', props.leaveType?.id), {
        preserveScroll: true,
    });
};
</script>

