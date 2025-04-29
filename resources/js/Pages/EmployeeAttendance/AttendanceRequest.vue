<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">

        <EmployeeAttendanceHeader activeLink="AttendanceRequest"></EmployeeAttendanceHeader>

        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1">
                        <KTIcon icon-name="magnifier" icon-class="fs-1 position-absolute ms-6" />
                        <input type="text" v-model="search" @input="searchData()" class="form-control form-control-solid w-255px ps-15" :placeholder="$t('loggedInEmployeeAttendance.placeholder.searchAttendanceRequests')" />
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
                                <div class="fs-5 text-gray-900 fw-bold">{{ $t('filterOptions.subtitle') }}</div>
                            </div>
                            <!--end::Header-->

                            <!--begin::Separator-->
                            <div class="separator border-gray-200"></div>
                            <!--end::Separator-->

                            <!--begin::Content-->
                            <div class="px-7 py-5" data-kt-user-table-filter="form">
                                <!-- Start: Status filter -->
                                <div class="mb-10">
                                    <label class="form-label fs-6 fw-semibold">{{ $t('filterOptions.loggedInEmployeeAttendance.subtitle.label.status') }}:</label>
                                    <Multiselect
                                        :placeholder = "$t('filterOptions.loggedInEmployeeAttendance.subtitle.placeholder.status')"
                                        v-model="selectedRequestStatus"
                                        :searchable="true"
                                        :options="allRequestStatuses"
                                        label="text"
                                        trackBy="text"
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
                        <!--begin::Missing Punch-->
                        <button v-if="checkPermission('can-create-employee-attendance-request')" type="button" class="btn btn-primary me-3" data-bs-toggle="modal" data-bs-target="#kt_modal_missing_attendance">
                            <KTIcon icon-name="plus" icon-class="fs-2" />
                            {{ $t('loggedInEmployeeAttendance.header.attendanceRequest.addMissingAttendance') }}
                        </button>
                        <!--end::Missing Punch-->
                    </div>
                    <!--end::Toolbar-->
                </div>
            </div>
            <div class="card-body pt-0">
                <Datatable @on-sort="sortData" :data="tableData" :header="tableHeader" :enable-items-per-page-dropdown="true" :checkbox-enabled="false">
                    <!-- Manager name -->
                    <template v-slot:manager_name="{ row: employeeAttendanceRequest }">
                        {{ employeeAttendanceRequest.manager_name }}
                    </template>

                    <!-- Attendance Date -->
                    <template v-slot:attendance_date="{ row: employeeAttendanceRequest }">
                        {{ employeeAttendanceRequest.attendance_date }}
                    </template>

                    <!-- Check in time -->
                    <template v-slot:check_in="{ row: employeeAttendanceRequest }">
                        {{ employeeAttendanceRequest.check_in }}
                    </template>

                    <!-- Check out time -->
                    <template v-slot:check_out="{ row: employeeAttendanceRequest }">
                        {{ employeeAttendanceRequest.check_out }}
                    </template>

                    <!-- Work Location -->
                    <template v-slot:work_location_text="{ row: employeeAttendanceRequest }">
                        {{ employeeAttendanceRequest.work_location_text }}
                    </template>

                    <!-- Status -->
                    <template v-slot:status="{ row: employeeAttendanceRequest }">
                        <b :class="{
                            'text-success': employeeAttendanceRequest.status === 'approved',
                            'text-warning': employeeAttendanceRequest.status === 'pending',
                            'text-danger': employeeAttendanceRequest.status === 'declined'
                            }">
                            {{ employeeAttendanceRequest.status }}
                        </b>
                    </template>
                </Datatable>
            </div>
        </div>
        <CreateAttendanceRequestModal :employeeJoiningDate="props?.employeeJoiningDate" :workLocations="props?.workLocations"></CreateAttendanceRequestModal>
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
import { extractDate, extractTime, getFullName } from '@/Core/helpers/Helper';
import { checkPermission } from "@/Core/helpers/Permission";
import CreateAttendanceRequestModal from '@/Pages/EmployeeAttendance/Modals/CreateAttendanceRequestModal.vue';
import Multiselect from '@vueform/multiselect';
import i18n from '@/Core/plugins/i18n';

const { t } = i18n.global;

const props = defineProps({
    employeeAttendanceRequests: Object as() => IEmployeeAttendanceRequests[] | undefined,
    employeeJoiningDate: String || null,
    requestStatuses: Object,
    workLocations: Object,
    breadcrumbs: Array as () => Breadcrumb[],
    pageTitle: String
});

interface Breadcrumb {
    url: string;
    title: string;
}

interface IEmployeeAttendanceRequests {
    id: number;
    manager_id: number | null,
    manager: IManager,
    attendance_date: string,
    check_in: string,
    check_out: string,
    work_location_text: string,
    status: string
}

interface IManager {
}

const tableHeader = ref([
    {
        columnName: t('loggedInEmployeeAttendance.label.manager'),
        columnLabel: "manager_name",
        sortEnabled: true,
        columnWidth: 140
    },
    {
        columnName: t('loggedInEmployeeAttendance.label.attendanceDate'),
        columnLabel: "attendance_date",
        sortEnabled: true,
        columnWidth: 140
    },
    {
        columnName: t('loggedInEmployeeAttendance.label.checkInTime'),
        columnLabel: "check_in",
        sortEnabled: true,
        columnWidth: 140
    },
    {
        columnName: t('loggedInEmployeeAttendance.label.checkOutTime'),
        columnLabel: "check_out",
        sortEnabled: true,
        columnWidth: 140
    },
    {
        columnName: t('loggedInEmployeeAttendance.label.workLocation'),
        columnLabel: "work_location_text",
        sortEnabled: true,
        columnWidth: 140
    },
    {
        columnName: t('loggedInEmployeeAttendance.label.status'),
        columnLabel: "status",
        sortEnabled: true,
        columnWidth: 75
    }
]);

const selectedRequestStatus = ref<string>('');
const tableData = ref < IEmployeeAttendanceRequests[] > ([]);
const initEmployeeAttendanceRequests = ref < IEmployeeAttendanceRequests[] > ([]);

onMounted(() => {
    if (props.employeeAttendanceRequests) {
        initEmployeeAttendanceRequests.value = props.employeeAttendanceRequests.map(employeeAttendanceRequest => ({
            id: employeeAttendanceRequest.id,
            manager_id: employeeAttendanceRequest.manager_id !== null ? employeeAttendanceRequest.manager_id : null,
            manager: {},
            manager_name: (employeeAttendanceRequest.manager ? getFullName(employeeAttendanceRequest.manager) : ''),
            attendance_date: (employeeAttendanceRequest.attendance_date) ? extractDate(new Date(employeeAttendanceRequest.attendance_date)) : '---',
            check_in: (employeeAttendanceRequest.check_in) ? extractTime(new Date(employeeAttendanceRequest.check_in)) : '---',
            check_out: (employeeAttendanceRequest.check_out) ? extractTime(new Date(employeeAttendanceRequest.check_out)) : '---',
            work_location_text: employeeAttendanceRequest.work_location_text,
            status: employeeAttendanceRequest.status
        }));
        tableData.value = initEmployeeAttendanceRequests.value;
    }
});

const search = ref < string > ("");
const searchData = () => {
    tableData.value = [...initEmployeeAttendanceRequests.value];
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

// assign all the requestStatuses from props to allRequestStatuses variable.
const allRequestStatuses = ref<Array<any>>([]);
if (Array.isArray(props.requestStatuses) && props.requestStatuses.length > 0) {
    allRequestStatuses.value = props.requestStatuses.map(status => ({value: status.value, text:status.text}));
}

const applyFilter = () => {
    const statusFilter = selectedRequestStatus.value;
    if (statusFilter.length == 0) {
        // If nothing is selected, show all requests
        tableData.value = initEmployeeAttendanceRequests.value;
    } else {
        // Filter requests based on selected status
        tableData.value = initEmployeeAttendanceRequests.value.filter(attendanceRequest => attendanceRequest.status === statusFilter);
    }
};

const applyReset = () => {
    selectedRequestStatus.value = '';
    tableData.value = initEmployeeAttendanceRequests.value;
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
