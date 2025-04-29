<template>
    <div class="card">
        <div class="card-header border-0 pt-6">
            <div class="card-title">
                <!--begin::Search-->
                <div class="d-flex align-items-center position-relative my-1">
                    <KTIcon icon-name="magnifier" icon-class="fs-1 position-absolute ms-6" />
                    <input type="text" v-model="search" @input="searchData()" class="form-control form-control-solid w-250px ps-15" :placeholder="$t('commonComponents.employeeComponent.placeholder.searchEmployees')" />
                </div>
                <!--end::Search-->
            </div>

            <div class="card-toolbar">
                <!--begin::Toolbar-->
                <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                <!-- Start: Filter -->
                <button type="button" class="btn btn-light-primary me-3" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
                        <i class="ki-duotone ki-filter fs-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                        </i>{{ $t('buttonValue.filter') }}
                    </button>
                    <div class="menu menu-sub menu-sub-dropdown w-300px w-md-325px" data-kt-menu="true">
                        <!--begin::Header-->
                        <div class="px-7 py-5">
                            <div class="fs-5 text-gray-900 fw-bold">{{ $t('filterOptions.title') }}</div>
                        </div>
                        <!--end::Header-->

                        <!--begin::Separator-->
                        <div class="separator border-gray-200"></div>
                        <!--end::Separator-->

                        <!--begin::Content-->
                        <div class="px-7 py-5" data-kt-user-table-filter="form">
                            <!-- Start: Status filter -->
                            <div class="mb-10">
                                <label class="form-label fs-6 fw-semibold">{{ $t('filterOptions.employee.subtitle.label.status') }}:</label>
                                <Multiselect
                                    :placeholder="$t('filterOptions.employee.subtitle.placeholder.status')"
                                    v-model="selectedStatus"
                                    :searchable="true"
                                    :options="allStatus"
                                />
                            </div>
                            <!-- End: Status filter -->

                            <!--begin::Actions-->
                            <div class="d-flex justify-content-end">
                                <button type="reset" class="btn btn-light btn-active-light-primary fw-semibold me-2 px-6" data-kt-menu-dismiss="true" data-kt-user-table-filter="reset" @click="applyReset()">{{ $t('buttonValue.reset') }}</button>
                                <button class="btn btn-primary fw-semibold px-6" data-kt-menu-dismiss="true" data-kt-user-table-filter="filter" @click="applyFilter()">{{ $t('buttonValue.apply') }}</button>
                            </div>
                            <!--end::Actions-->
                        </div>
                        <!--end::Content-->
                    </div>
                    <!--End: Filter-->
                </div>
                <!--end::Toolbar-->
            </div>
        </div>
        <div class="card-body pt-0">
            <Datatable @on-sort="sortData" :data="tableData" :header="tableHeader" :enable-items-per-page-dropdown="true" :checkbox-enabled="false">
                <!-- Employee Name -->
                <template v-slot:first_name="{ row: employee }" >
                    <div class="d-flex align-items-center">
                        <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                            <Link v-if="checkPermission('can-view-details-employee')" :href="route('employees.show', employee.id)">
                                <div class="symbol-label">
                                    <!-- <img :src="getAssetPath('media/avatars/300-1.jpg')" alt="Emma Smith" class="w-100" /> -->
                                    <div class="symbol-label fs-3 bg-light-danger text-danger">{{ getInitials(employee) }}</div>
                                </div>
                            </Link>
                            <div v-else class="symbol-label">
                                <!-- <img :src="getAssetPath('media/avatars/300-1.jpg')" alt="Emma Smith" class="w-100" /> -->
                                <div class="symbol-label fs-3 bg-light-danger text-danger">{{ getInitials(employee) }}</div>
                            </div>
                        </div>
                        <div class="d-flex flex-column">
                            <Link v-if="checkPermission('can-view-details-employee')" :href="route('employees.show', employee.id)" class="text-gray-800 text-hover-primary mb-1">
                                {{ getFullName(employee) }}
                            </Link>
                            <span v-else>{{ getFullName(employee) }}</span>
                            <span class = "text-gray-600">{{ employee.email }}</span>
                        </div>
                    </div>
                </template>

                <!-- Attendance Date -->
                <template v-slot:attendance_date="{ row: employee }">
                    {{ employee.attendance_date }}
                </template>

                <!-- Check In Time -->
                <template v-slot:check_in="{ row: employee }">
                    {{ employee.check_in }}
                </template>

                <!-- Employee Attendance Status -->
                <template v-slot:attendance_status="{ row: employee }">
                    <span :style="{ color: getAttendanceColor(employee.id) }">
                        {{ employee.attendance_status }}
                    </span>
                </template>

            </Datatable>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, defineProps } from 'vue';
import Datatable from "@/Components/kt-datatable/KTDataTable.vue";
import type { Sort } from "@/Components/kt-datatable/table-partials/Models";
import { MenuComponent } from "@/Assets/ts/components";
import arraySort from "array-sort";
import { Link } from '@inertiajs/vue3';
import KTIcon from "@/Core/helpers/kt-icon/KTIcon.vue";
import { checkPermission } from "@/Core/helpers/Permission";
import { extractDate, extractTime, getFullName, getInitials } from '@/Core/helpers/Helper';
import Multiselect from '@vueform/multiselect';
import i18n from '@/Core/plugins/i18n';

const { t } = i18n.global;

const props = defineProps({
    employees: Object as() => IEmployee[] | undefined,
    attendances: Object,
    breadcrumbs: Array as() => Breadcrumb[],
    pageTitle: String,
});

interface Breadcrumb {
    url: string;
    title: string;
}

interface IEmployee {
    id: number;
    first_name: string;
    last_name: string;
    email: string;
    attendance_date: string;
    check_in: string;
    attendance_status: string;
}

const tableHeader = ref([{
        columnName: t('commonComponents.employeeComponent.label.name'),
        columnLabel: "first_name",
        sortEnabled: true,
        columnWidth: 175
    },
    {
        columnName: t('attendance.label.attendanceDate'),
        columnLabel: "attendance_date",
        sortEnabled: true,
        columnWidth: 130
    },
    {
        columnName: t('attendance.label.checkIn'),
        columnLabel: "check_in",
        sortEnabled: true,
        columnWidth: 130
    },
    {
        columnName: t('attendance.label.attendanceStatus'),
        columnLabel: "attendance_status",
        sortEnabled: true,
        columnWidth: 130
    }
]);

const tableData = ref < IEmployee[] > ([]);
const initEmployees = ref < IEmployee[] > ([]);

onMounted(() => {
    if (props.employees) {
        initEmployees.value = props.employees.map(employee => ({
            id: employee.id,
            first_name: employee.first_name,
            last_name: employee.last_name,
            email: employee.email,
            attendance_date: extractDate(props.attendances?.attendanceDate),
            check_in: getCheckInTime(employee.id) ? extractTime(new Date(getCheckInTime(employee.id))) : '---',
            attendance_status: getAttendanceStatus(employee.id)
        }));
        tableData.value = initEmployees.value;
    }
});

const getAttendanceStatus = (employeeId: number) => {
    return props.attendances?.formattedAttendanceData[employeeId]?.status;
};

const getCheckInTime = (employeeId: number) => {
    return props.attendances?.formattedAttendanceData[employeeId]?.checkInTime;
};

const getAttendanceColor = (employeeId: number) => {
    const colors = props.attendances?.attendanceColor;
    return props.attendances?.formattedAttendanceData[employeeId]?.status === 'present' 
        ? colors.present
        : colors.absent;
};

const search = ref < string > ("");
const searchData = () => {
    tableData.value = [...initEmployees.value];
    if (search.value !== "") {
        tableData.value = tableData.value.filter(item => searchingFunc(item, search.value));
    }
    MenuComponent.reinitialization();
};

const searchingFunc = (obj: any, value: string): boolean => {
    for (let key in obj) {
        if (!Number.isInteger(obj[key]) && !(typeof obj[key] === "object")) {
            if (obj[key].includes(value)) {
                return true;
            }
        }
    }
    return false;
};

const selectedStatus = ref<string>('');

// all Status
const allStatus = ['present', 'absent'];

const applyFilter = () => {
    let filteredData = tableData.value;

    if (selectedStatus.value) {
        filteredData = filteredData.filter((item: any) => selectedStatus.value === item.attendance_status);
    }

    tableData.value = filteredData;
};

const applyReset = () => {
    selectedStatus.value = '';
    tableData.value = initEmployees.value;
}

const sortData = (sort: Sort) => {
    const reverse: boolean = sort.order === "asc";
    if (sort.label) {
        arraySort(tableData.value, sort.label, {
            reverse
        });
    }
};
</script>
