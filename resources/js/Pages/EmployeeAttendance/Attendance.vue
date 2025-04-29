<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">

        <EmployeeAttendanceHeader activeLink="Attendance"></EmployeeAttendanceHeader>

        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1">
                        <KTIcon icon-name="magnifier" icon-class="fs-1 position-absolute ms-6" />
                        <input type="text" v-model="search" @input="searchData()" class="form-control form-control-solid w-250px ps-15" :placeholder="$t('loggedInEmployeeAttendance.placeholder.searchAttendances')" />
                    </div>
                    <!--end::Search-->
                </div>
            </div>
            <div class="card-body pt-0">
                <Datatable @on-sort="sortData" :data="tableData" :header="tableHeader" :enable-items-per-page-dropdown="true" :checkbox-enabled="false">
                    <!-- Attendance Date -->
                    <template v-slot:attendance_date="{ row: employeeAttendance }">
                        {{ employeeAttendance.attendance_date }}
                    </template>

                    <!-- Check-in Time -->
                    <template v-slot:check_in="{ row: employeeAttendance }">
                        {{ employeeAttendance.check_in}}
                    </template>

                    <!-- Check-out Time -->
                    <template v-slot:check_out="{ row: employeeAttendance }">
                        {{ employeeAttendance.check_out}}
                    </template>

                    <!-- Worked Hours -->
                    <template v-slot:worked_hours="{ row: employeeAttendance }">
                        {{ employeeAttendance.worked_hours}}
                    </template>

                    <!-- Effective Hours -->
                    <template v-slot:effective_hours="{ row: employeeAttendance }">
                        {{ employeeAttendance.effective_hours}}
                    </template>
                    <!-- Work Location -->
                    <template v-slot:work_location_text="{ row: employeeAttendance }">
                        {{ employeeAttendance.work_location_text}}
                    </template>

                    <!-- Note -->
                    <template v-slot:note="{ row: employeeAttendance }">
                        {{ employeeAttendance.note}}
                    </template>
                </Datatable>
            </div>
        </div>
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
import i18n from '@/Core/plugins/i18n';
import { extractDate, extractTime } from '@/Core/helpers/Helper';

const { t } = i18n.global;

const props = defineProps({
    employeeAttendances: Object as() => IEmployeeAttendances[] | undefined,
    punchTypes: Object,
    breadcrumbs: Array as () => Breadcrumb[],
    pageTitle: String
});

interface Breadcrumb {
    url: string;
    title: string;
}

interface IEmployeeAttendances {
    id: number;
    attendance_date: string;
    check_in: string;
    check_out: string;
    worked_hours: string;
    effective_hours: string;
    work_location_text: string;
    note: string;
}

const tableHeader = ref([
    {
        columnName: t('loggedInEmployeeAttendance.label.attendanceDate'),
        columnLabel: "attendance_date",
        sortEnabled: true,
        columnWidth: 120
    },
    {
        columnName: t('loggedInEmployeeAttendance.label.checkInTime'),
        columnLabel: "check_in",
        sortEnabled: true,
        columnWidth: 120
    },
    {
        columnName: t('loggedInEmployeeAttendance.label.checkOutTime'),
        columnLabel: "check_out",
        sortEnabled: true,
        columnWidth: 120
    },
    {
        columnName: t('loggedInEmployeeAttendance.label.workedHours'),
        columnLabel: "worked_hours",
        sortEnabled: true,
        columnWidth: 120
    },
    {
        columnName: t('loggedInEmployeeAttendance.label.effectiveHours'),
        columnLabel: "effective_hours",
        sortEnabled: true,
        columnWidth: 120
    },
    {
        columnName: t('loggedInEmployeeAttendance.label.workLocation'),
        columnLabel: "work_location_text",
        sortEnabled: true,
        columnWidth: 120,
    },
    {
        columnName: t('loggedInEmployeeAttendance.label.note'),
        columnLabel: "note",
        sortEnabled: true,
        columnWidth: 120
    }
]);

const tableData = ref < IEmployeeAttendances[] > ([]);
const initEmployeeAttendances = ref < IEmployeeAttendances[] > ([]);

onMounted(() => {
    if (props.employeeAttendances) {
        initEmployeeAttendances.value = props.employeeAttendances.map(employeeAttendance => ({
            id: employeeAttendance.id,
            attendance_date: (employeeAttendance.attendance_date) ? extractDate(new Date(employeeAttendance.attendance_date)) : '---',
            check_in: (employeeAttendance.check_in) ? extractTime(new Date(employeeAttendance.check_in)) : '---',
            check_out: (employeeAttendance.check_out) ? extractTime(new Date(employeeAttendance.check_out)) : '---',
            worked_hours: employeeAttendance.worked_hours,
            effective_hours: employeeAttendance.effective_hours,
            work_location_text: employeeAttendance.work_location_text,
            note: employeeAttendance.note
        }));
        tableData.value = initEmployeeAttendances.value;
    }
});

const search = ref < string > ("");
const searchData = () => {
    tableData.value = [...initEmployeeAttendances.value];
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
