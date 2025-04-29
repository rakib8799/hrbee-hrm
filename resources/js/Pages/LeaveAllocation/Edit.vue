<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">
        <div class="card mb-5 mb-xl-10">
            <!--begin::Card header-->
            <div class="card-header border-0 cursor-pointer">
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">Edit Leave Allocation</h3>
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
                                <label class="required fs-5 fw-semibold mb-2">Name</label>
                                <Field type="text" class="form-control form-control-lg form-control-solid" placeholder="Name" name="name" v-model="formData.name"/>
                                <ErrorMessage :errorMessage="formData.errors.name" />
                             </div>
                            <!-- Leave Type -->
                            <div class="col-md-6 fv-row">
                                <label class="required fs-5 fw-semibold mb-2">Leave Type</label>
                                <Multiselect
                                    placeholder="Leave Type"
                                    v-model="formData.leave_type_id"
                                    :searchable="true"
                                    :options="allLeaveTypes"
                                    label="name"
                                    trackBy="name"
                                />
                                <ErrorMessage :errorMessage="formData.errors.leave_type_id" />
                            </div>
                        </div>

                        <!-- Allocation Mode, Employees and Department -->
                        <div class="row mb-5 g-4">
                            <!-- Allocation Mode -->
                             <div class = "col-md-6 fv-row">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label class="required fs-5 fw-semibold mb-2">Allocation Mode</label>
                                    </div>
                                    <div class="col-sm-8">
                                        <div v-for="(mode, index) in allocationModes" :key="index" class="col-md-12 mb-2 fv-row">
                                            <!-- If previous selected holiday_type = employee we can not change allocation mode -->
                                            <div v-if="props.leaveAllocation?.holiday_type==='employee'">
                                                <!-- Selected Allocation Mode -->
                                                <input v-if="mode.value == props.leaveAllocation?.holiday_type" class="form-check-input me-3" :name="mode.value" type="radio" v-model="formData.holiday_type" :value="mode.value"/>

                                                <!-- Other Allocation Modes -->
                                                <input v-else disabled class="form-check-input me-3" :name="mode.value" type="radio" :value="mode.value"/>
                                                <label class="fs-5 fw-semibold mb-2">{{ mode.text }}</label>
                                            </div>

                                            <!-- If previous selected holiday_type === company or department, we can change allocation mode -->
                                            <div v-else>
                                                <input class="form-check-input me-3" :name="mode.value" type="radio" v-model="formData.holiday_type" :value="mode.value" @change="handleAllocationModeChange" />
                                                <label class="fs-5 fw-semibold mb-2">{{ mode.text }}</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <ErrorMessage :errorMessage="formData.errors.holiday_type" />
                             </div>

                            <!-- Employees and Department -->
                            <div class="col-md-6 fv-row">
                                <!-- Employees -->
                                <div>
                                    <label class="fs-5 fw-semibold mb-2">Employee</label>

                                    <!-- If previous selected holiday_type = employee we can not change employee -->
                                    <input v-if="props.leaveAllocation?.employee && props.leaveAllocation.holiday_type==='employee'" type="text" class="form-control form-control-lg form-control-solid" readonly :value="getFullName(props.leaveAllocation.employee)">

                                    <!-- If previous selected holiday_type = company or department, we can select employees -->
                                    <Multiselect
                                        v-else
                                        placeholder="Employee"
                                        mode="tags"
                                        v-model="formData.employee_id"
                                        :searchable="true"
                                        :options="filteredEmployees"
                                        label="name"
                                        trackBy="name"
                                    />
                                    <ErrorMessage :errorMessage="formData.errors.employee_id" />
                                </div>

                                <!-- Departments -->
                                <!-- Show only when previous selected holiday_type is company or department -->
                                <div class = "mt-3" v-if="props.leaveAllocation?.holiday_type==='company' || props.leaveAllocation?.holiday_type==='department'">
                                    <label class="fs-5 fw-semibold mb-2">Department</label>
                                    <Multiselect
                                        placeholder="Department"
                                        v-model="formData.department_id"
                                        :searchable="true"
                                        :options="filteredDepartments"
                                        label="name"
                                        trackBy="name"
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
                                        <label class="required fs-5 fw-semibold mb-2">Allocation Type</label>
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
                                <label class="required fs-5 fw-semibold mb-2">Allocation Days</label>
                                <Field type="number" class="form-control form-control-lg form-control-solid" placeholder="Allocation Days" name="number_of_days" v-model="formData.number_of_days"/>
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

<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Field, Form as VForm } from "vee-validate";
import ErrorMessage from "@/Components/Message/ErrorMessage.vue";
import SubmitButton from "@/Components/Button/SubmitButton.vue";
import Multiselect from '@vueform/multiselect';
import { ref, computed } from 'vue';
import { getFullName } from '@/Core/helpers/Helper';

const props = defineProps({
    leaveAllocation: Object,
    employees: Object,
    leaveTypes: Object,
    departments: Object,
    allocationTypes: Object,
    allocationModes: Object,
    breadcrumbs: Array as() => Breadcrumb[],
    pageTitle: String,
});

interface Breadcrumb {
    url: string;
    title: string;
}

// assign all the leave types from props to allLeaveTypes variable.
const allLeaveTypes = ref<Array<any>>([]);
if (Array.isArray(props.leaveTypes) && props.leaveTypes.length > 0) {
    allLeaveTypes.value = props.leaveTypes.map(leaveType => ({value: leaveType.id, name: leaveType.name}));
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

// assign all the allotcation types from props to allAllocationTypes variable.
const allAllocationTypes = ref<Array<any>>([]);
if (Array.isArray(props.allocationTypes) && props.allocationTypes.length > 0) {
    allAllocationTypes.value = props.allocationTypes.map(allocationType => ({value: allocationType.value, name: allocationType.text}));
}

// assign all the departments from props to allDepartments variable.
const allDepartments = ref<Array<any>>([]);
if (Array.isArray(props.departments) && props.departments.length > 0) {
    allDepartments.value = props.departments.map(department => ({value: department.id, name: department.name}));
}

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

// Store previous department_id
const previousDepartmentId = props.leaveAllocation?.department_id;

// Function to handle allocation mode change
const handleAllocationModeChange = (event: any) => {
    if (event.target.value === 'department') {
        formData.department_id = previousDepartmentId;
    }
};

const formData = useForm({
    name: props.leaveAllocation?.name || '',
    leave_type_id: props.leaveAllocation?.leave_type_id || '',
    holiday_type: props.leaveAllocation?.holiday_type || '',
    employee_id: props.leaveAllocation?.employee_id ? [props.leaveAllocation.employee_id] : [],
    department_id: props.leaveAllocation?.department_id || '',
    allocation_type: props.leaveAllocation?.allocation_type || 'regular',
    number_of_days: props.leaveAllocation?.number_of_days || '',
    date_from: props.leaveAllocation?.date_from || '',
    date_to: props.leaveAllocation?.date_to || '',
    notes: props.leaveAllocation?.notes || ''
});

const submit = () => {
    formData.patch(route('leave-allocations.update', props.leaveAllocation?.id), {
        preserveScroll: true,
    });
};
</script>
