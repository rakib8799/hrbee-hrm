<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">
        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1">
                        <KTIcon icon-name="magnifier" icon-class="fs-1 position-absolute ms-6" />
                        <input type="text" v-model="search" @input="searchData()" class="form-control form-control-solid w-255px ps-15" :placeholder="$t('attendanceRequest.placeholder.searchAttendanceRequests')" />
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
                                    <label class="form-label fs-6 fw-semibold">{{ $t('filterOptions.attendanceRequest.subtitle.label.status') }}:</label>
                                    <Multiselect
                                        :placeholder = "$t('filterOptions.attendanceRequest.subtitle.placeholder.status')"
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
                    </div>
                    <!--end::Toolbar-->
                </div>
            </div>
            <div class="card-body pt-0">
                <Datatable @on-sort="sortData" :data="tableData" :header="tableHeader" :enable-items-per-page-dropdown="true" :checkbox-enabled="false">
                    <!-- Employee name -->
                    <template v-slot:employee_name="{ row: attendanceRequest }">
                        {{ attendanceRequest.employee_name }}
                    </template>

                    <!-- Manager name -->
                    <template v-slot:manager_name="{ row: attendanceRequest }">
                        {{ attendanceRequest.manager_name }}
                    </template>

                    <!-- Attendance date -->
                    <template v-slot:attendance_date="{ row: attendanceRequest }">
                        {{ attendanceRequest.attendance_date }}
                    </template>

                    <!-- Check in time -->
                    <template v-slot:check_in="{ row: attendanceRequest }">
                        {{ attendanceRequest.check_in }}
                    </template>

                    <!-- Check out time -->
                    <template v-slot:check_out="{ row: attendanceRequest }">
                        {{ attendanceRequest.check_out }}
                    </template>

                    <!-- Status -->
                    <template v-slot:status="{ row: attendanceRequest }">
                        <b :class="{
                            'text-success': attendanceRequest.status === 'approved',
                            'text-warning': attendanceRequest.status === 'pending',
                            'text-danger': attendanceRequest.status === 'declined'
                            }">
                            {{ attendanceRequest.status }}
                        </b>
                    </template>

                    <!-- Actions -->
                    <template v-slot:actions="{ row: attendanceRequest }" v-if="checkPermission('can-approve-attendance-request') || checkPermission('can-decline-attendance-request')">
                        <!-- Approve -->
                        <ApproveConfirmationButton v-if="checkPermission('can-approve-attendance-request')"  
                        :obj="attendanceRequest" 
                        confirmRoute="attendance-requests.approve" 
                        :title="`${t('confirmation.approve.main')} ${attendanceRequest?.employee_name} ${t('confirmation.approve.sub')} ${attendanceRequest?.attendance_date}?`"/>

                        <!-- Decline -->
                        <DeclineConfirmationButton v-if="checkPermission('can-decline-attendance-request')" 
                        :obj="attendanceRequest" 
                        confirmRoute="attendance-requests.decline" 
                        :title="`${t('confirmation.decline.main')} ${attendanceRequest?.employee_name} ${t('confirmation.decline.sub')} ${attendanceRequest?.attendance_date}?`"/>
                    </template>
                    <template v-slot:actions="{ row: attendanceRequest }" v-else>
                        Permission Denied
                    </template>
                </Datatable>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, onMounted, computed } from 'vue';
import Datatable from "@/Components/kt-datatable/KTDataTable.vue";
import type { Sort } from "@/Components/kt-datatable/table-partials/Models";
import { MenuComponent } from "@/Assets/ts/components";
import arraySort from "array-sort";
import KTIcon from "@/Core/helpers/kt-icon/KTIcon.vue";
import { extractDate, extractTime, getFullName } from '@/Core/helpers/Helper'
import ApproveConfirmationButton from '@/Components/Button/ApproveConfirmationButton.vue';
import DeclineConfirmationButton from '@/Components/Button/DeclineConfirmationButton.vue';
import { checkPermission } from "@/Core/helpers/Permission";
import Multiselect from '@vueform/multiselect';
import i18n from '@/Core/plugins/i18n';

const { t } = i18n.global;

const props = defineProps({
    attendanceRequests: Object as() => IAttendanceRequest[] | undefined,
    isLineManagerOrHR: Boolean,
    requestStatuses: Object,
    breadcrumbs: Array as() => Breadcrumb[],
    pageTitle: String
});

interface Breadcrumb {
    url: string;
    title: string;
}

interface IAttendanceRequest {
    id: number;
    employee_id: number,
    employee: IEmployee,
    manager_id: number | null,
    manager: IManager,
    attendance_date: string,
    check_in: string,
    check_out: string,
    status: string
}

interface IEmployee {
}

interface IManager {
}

const tableHeader = computed(() => {
    const headers = [
        {
            columnName: t('attendanceRequest.label.employee'),
            columnLabel: "employee_name",
            sortEnabled: true,
            columnWidth: 140
        },
        {
            columnName: t('attendanceRequest.label.attendanceDate'),
            columnLabel: "attendance_date",
            sortEnabled: true,
            columnWidth: 140
        },
        {
            columnName: t('attendanceRequest.label.checkIn'),
            columnLabel: "check_in",
            sortEnabled: true,
            columnWidth: 140
        },
        {
            columnName: t('attendanceRequest.label.checkOut'),
            columnLabel: "check_out",
            sortEnabled: true,
            columnWidth: 140
        },
        {
            columnName: t('attendanceRequest.label.status'),
            columnLabel: "status",
            sortEnabled: true,
            columnWidth: 140
        }
    ];

    if (!props?.isLineManagerOrHR) {
        headers.splice(1, 0, {
            columnName: t('attendanceRequest.label.manager'),
            columnLabel: "manager_name",
            sortEnabled: true,
            columnWidth: 140
        });
    }

    const hasPendingRequests = tableData.value.some(pendingRequest => pendingRequest.status === 'pending');
    if (hasPendingRequests) {
        headers.push({
            columnName: t('buttonValue.actions'),
            columnLabel: "actions",
            sortEnabled: false,
            columnWidth: 75
        });
    }

    return headers;
});

const selectedRequestStatus = ref<string>('');
const tableData = ref < IAttendanceRequest[] > ([]);
const initAttendanceRequests = ref < IAttendanceRequest[] > ([]);

onMounted(() => {
    if (props.attendanceRequests) {
        initAttendanceRequests.value = props.attendanceRequests.map(attendanceRequest => ({
            id: attendanceRequest.id,
            employee_id: attendanceRequest.employee_id,
            employee: {},
            manager_id: attendanceRequest.manager_id !== null ? attendanceRequest.manager_id : null,
            manager: {},
            employee_name: getFullName(attendanceRequest.employee),
            manager_name: (attendanceRequest.manager ? getFullName(attendanceRequest.manager) : ''),
            attendance_date: (attendanceRequest.attendance_date) ? extractDate(new Date(attendanceRequest.attendance_date)) : '---',
            check_in: (attendanceRequest.check_in) ? extractTime(new Date(attendanceRequest.check_in)) : '---',
            check_out: (attendanceRequest.check_out) ? extractTime(new Date(attendanceRequest.check_out)) : '---',
            status: attendanceRequest.status,
        }));
        tableData.value = initAttendanceRequests.value.filter(attendanceRequest => attendanceRequest.status === 'pending');
    }
});

const search = ref < string > ("");
const searchData = () => {
    tableData.value = [...initAttendanceRequests.value];
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
        tableData.value = initAttendanceRequests.value;
    } else {
        // Filter requests based on selected status
        tableData.value = initAttendanceRequests.value.filter(attendanceRequest => attendanceRequest.status === statusFilter);
    }
};

const applyReset = () => {
    selectedRequestStatus.value = '';
    tableData.value = initAttendanceRequests.value.filter(attendanceRequest => attendanceRequest.status === 'pending');
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
