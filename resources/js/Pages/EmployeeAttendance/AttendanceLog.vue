<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">

        <EmployeeAttendanceHeader activeLink="AttendanceLog"></EmployeeAttendanceHeader>

        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1">
                        <KTIcon icon-name="magnifier" icon-class="fs-1 position-absolute ms-6" />
                        <input type="text" v-model="search" @input="searchData()" class="form-control form-control-solid w-250px ps-15" :placeholder="$t('loggedInEmployeeAttendance.placeholder.searchAttendanceLogs')" />
                    </div>
                    <!--end::Search-->
                </div>
                <div class="card-toolbar">
                    <!--begin::Toolbar-->
                    <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                        <!--begin::Add Attendance-->
                        <span v-if="checkPermission('can-create-employee-attendance-log')">
                            <span v-if="props?.hasPendingCheckout">
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_employee_checkout">
                                    <i class="fa-solid fa-right-to-bracket"></i>
                                    Check In
                                </button>
                            </span>
                            <span v-else>
                                <button type="button" class="btn btn-primary" @click="canPunch('in')" v-if="props?.canPunch?.in" data-bs-toggle="modal" data-bs-target="#kt_modal_add_employee_attendance_log">
                                    <i class="fa-solid fa-right-to-bracket"></i>
                                    Check In
                                </button>
                                <button type="button" class="btn btn-warning ms-2" @click="canPunch('break')" v-if="props?.canPunch?.break" data-bs-toggle="modal" data-bs-target="#kt_modal_add_employee_attendance_log">
                                    <i class="fa-solid fa-arrow-up-from-bracket"></i>
                                    Take a Break
                                </button>
                                <button type="button" class="btn bg-primary text-white ms-2" @click="canPunch('out')" v-if="props?.canPunch?.out" data-bs-toggle="modal" data-bs-target="#kt_modal_add_employee_attendance_log">
                                    <i class="fa-solid fa-right-from-bracket text-white"></i>
                                    Check Out
                                </button>
                            </span>
                        </span>
                        <!--end::Add Attendance-->
                    </div>
                    <!--end::Toolbar-->
                </div>
            </div>
            <div class="card-body pt-0">
                <Datatable @on-sort="sortData" :data="tableData" :header="tableHeader" :enable-items-per-page-dropdown="true" :checkbox-enabled="false">
                    <!-- Punch Type -->
                    <template v-slot:punch_type="{ row: employeeAttendanceLog }">
                        {{ employeeAttendanceLog.punch_type }}
                    </template>

                    <!-- Punch Time -->
                    <template v-slot:punch_time="{ row: employeeAttendanceLog }">
                        {{ employeeAttendanceLog.punch_time }}
                    </template>

                    <!-- Note -->
                    <template v-slot:note="{ row: employeeAttendanceLog }">
                        <template v-if="employeeAttendanceLog.note">
                            {{ employeeAttendanceLog.note }}
                        </template>
                        <template v-else>
                            ---
                        </template>
                    </template>
                </Datatable>
            </div>
        </div>
        <CreateEmployeeAttendanceLogModal :punchTypes="props?.punchTypes" :workLocations="props?.workLocations" :isFirstPunchOfDay="props?.isFirstPunchOfDay" :modalType="modalType"></CreateEmployeeAttendanceLogModal>
        <CreateEmployeeCheckoutModal :employeeLastAttendance="props?.employeeLastAttendance"></CreateEmployeeCheckoutModal>
    </AuthenticatedLayout>
</template>

<script lang="ts" setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import EmployeeAttendanceHeader from '@/Pages/EmployeeAttendance/EmployeeAttendanceHeader.vue';
import { ref, onMounted } from 'vue';
import type { Sort } from "@/Components/kt-datatable/table-partials/Models";
import { MenuComponent } from "@/Assets/ts/components";
import arraySort from "array-sort";
import KTIcon from "@/Core/helpers/kt-icon/KTIcon.vue";
import Datatable from "@/Components/kt-datatable/KTDataTable.vue";
import CreateEmployeeAttendanceLogModal from '@/Pages/EmployeeAttendance/Modals/CreateEmployeeAttendanceLogModal.vue';
import CreateEmployeeCheckoutModal from '@/Pages/EmployeeAttendance/Modals/CreateEmployeeCheckoutModal.vue';
import { checkPermission } from "@/Core/helpers/Permission";
import i18n from '@/Core/plugins/i18n';
import { extractDateTime } from '@/Core/helpers/Helper';

const { t } = i18n.global;

const props = defineProps({
    employeeLastAttendance: String,
    employeeAttendanceLogs: Object as() => IEmployeeAttendanceLogs[] | undefined,
    punchTypes: Object,
    canPunch: Object,
    workLocations: Object,
    hasPendingCheckout: Boolean,
    isFirstPunchOfDay: Boolean,
    breadcrumbs: Array as() => Breadcrumb[],
    pageTitle: String
});

interface Breadcrumb {
    url: string;
    title: string;
}

interface IEmployeeAttendanceLogs {
    id: number;
    punch_type: string;
    punch_time: string;
    work_from: string;
    note: string;
}

const modalType = ref('');

const canPunch = (type: string) => {
    modalType.value = type;
};

const tableHeader = ref([
    {
        columnName: t('loggedInEmployeeAttendance.label.punchType'),
        columnLabel: "punch_type",
        sortEnabled: true,
        columnWidth: 175
    },
    {
        columnName: t('loggedInEmployeeAttendance.label.punchTime'),
        columnLabel: "punch_time",
        sortEnabled: true,
        columnWidth: 230
    },
    {
        columnName: t('loggedInEmployeeAttendance.label.note'),
        columnLabel: "note",
        sortEnabled: true,
        columnWidth: 230
    }
]);

const tableData = ref < IEmployeeAttendanceLogs[] > ([]);
const initEmployeeAttendanceLogs = ref < IEmployeeAttendanceLogs[] > ([]);

onMounted(() => {
    if (props.employeeAttendanceLogs) {
        initEmployeeAttendanceLogs.value = props.employeeAttendanceLogs.map(employeeAttendanceLog => ({
            id: employeeAttendanceLog.id,
            punch_type: employeeAttendanceLog.punch_type,
            punch_time: extractDateTime(new Date(employeeAttendanceLog.punch_time)),
            work_from: employeeAttendanceLog.work_from,
            note: employeeAttendanceLog.note
        }));
        tableData.value = initEmployeeAttendanceLogs.value;
    }
});

const search = ref < string > ("");
const searchData = () => {
    tableData.value = [...initEmployeeAttendanceLogs.value];
    if (search.value !== "") {
        tableData.value = tableData.value.filter(item => searchingFunc(item, search.value));
    }
    MenuComponent.reinitialization();
};

const searchingFunc = (obj: any, value: string): boolean => {
    for (let key in obj) {
        if (!Number.isInteger(obj[key]) && !(typeof obj[key] === "object")) {
            if (obj[key]?.includes(value)) {
                return true;
            }
        }
    }
    return false;
};

const sortData = (sort: Sort) => {
    const reverse: boolean = sort.order === "asc";
    if (sort.label) {
        arraySort(tableData.value, sort.label, {
            reverse
        });
    }
};
</script>
