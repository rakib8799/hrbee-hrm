<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">

        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1">
                        <KTIcon icon-name="magnifier" icon-class="fs-1 position-absolute ms-6" />
                        <input type="text" v-model="search" @input="searchData()" class="form-control form-control-solid w-250px ps-15" placeholder="Search leave requests" />
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
                    </div>
                    <!--end::Toolbar-->
                </div>
            </div>
            <div class="card-body pt-0">
                <Datatable @on-sort="sortData" :data="tableData" :header="tableHeader" :enable-items-per-page-dropdown="true" :checkbox-enabled="false">
                    <!-- Leave Type -->
                    <template v-slot:leave_type="{ row: leave }">
                        {{ leave.leave_type ? leave.leave_type.name : ''  }}
                    </template>

                    <!-- Leave Description -->
                    <template v-slot:name="{ row: leave }">
                        {{ leave.name }}
                    </template>

                    <!-- Holiday Type -->
                    <template v-slot:holiday_type="{ row: leave }">
                        {{ leave.holiday_type }}
                    </template>

                    <!-- Number of Days -->
                    <template v-slot:number_of_days="{ row: leave }">
                        {{ leave.number_of_days }}
                    </template>

                    <!-- Leave validation type -->
                    <template v-slot:leave_validation_type="{ row: leave }">
                        {{ leave.leave_validation_type }}
                    </template>

                    <!-- Leave Request Approval Status -->
                    <template v-slot:status="{ row: leave }">
                        <b :class="{
                            'text-success': leave.status === APPROVED,
                            'text-info': leave.status === APPROVED_FIRST,
                            'text-warning': leave.status === PENDING,
                            'text-danger': leave.status === DECLINED
                            }">
                            {{ leave.status }}
                        </b>
                    </template>
                </Datatable>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, onMounted,defineProps } from 'vue';
import Datatable from "@/Components/kt-datatable/KTDataTable.vue";
import type { Sort } from "@/Components/kt-datatable/table-partials/Models";
import { MenuComponent } from "@/Assets/ts/components";
import arraySort from "array-sort";
import KTIcon from "@/Core/helpers/kt-icon/KTIcon.vue";
import Multiselect from '@vueform/multiselect';
import {PENDING, APPROVED, DECLINED, APPROVED_FIRST} from "@/Core/constants/Constant";
import i18n from '@/Core/plugins/i18n';

const { t } = i18n.global;

const props = defineProps({
    leaves: Object as() => ILeaves[] | undefined,
    requestStatuses: Object,
    breadcrumbs: Array as() => Breadcrumb[],
    pageTitle: String
});

interface Breadcrumb {
    url: string;
    title: string;
}

interface ILeaves {
    id: number;
    manager_id: number;
    first_approver_id: number;
    last_approver_id: number;
    holiday_type: string;
    leave_type?: {
        id: number;
        name: string;
        leave_validation_type: string;
    };
    name: string;
    number_of_days: number;
    is_active: string;
    status: string;
}

const tableHeader = ref([
    {
        columnName: 'Leave Type',
        columnLabel: "leave_type",
        sortEnabled: true,
        columnWidth: 120
    },
    {
        columnName: 'Description',
        columnLabel: "name",
        sortEnabled: true,
        columnWidth: 120
    },
    {
        columnName: 'Holiday Type',
        columnLabel: "holiday_type",
        sortEnabled: true,
        columnWidth: 120
    },
    {
        columnName: 'Days',
        columnLabel: "number_of_days",
        sortEnabled: true,
        columnWidth: 120
    },
    {
        columnName: 'Validation Type',
        columnLabel: "leave_validation_type",
        sortEnabled: true,
        columnWidth: 120
    },
    {
        columnName: 'Status',
        columnLabel: "status",
        sortEnabled: true,
        columnWidth: 120
    }
]);

const selectedRequestStatus = ref<string>('');
const tableData = ref < ILeaves[] > ([]);
const initLeaves = ref < ILeaves[] > ([]);

onMounted(() => {
    if (props.leaves) {
        initLeaves.value = props.leaves.map(leave => ({
            id: leave.id,
            manager_id: leave.manager_id,
            first_approver_id: leave.first_approver_id,
            last_approver_id: leave.last_approver_id,
            name: leave.name,
            holiday_type: leave.holiday_type,
            number_of_days: leave.number_of_days,
            leave_type: leave.leave_type,
            leave_validation_type: leave.leave_type?.leave_validation_type,
            is_active: leave.is_active,
            status: leave.status
        }));
        tableData.value = initLeaves.value;
    }
});

const search = ref < string > ("");
const searchData = () => {
    tableData.value = [...initLeaves.value];
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
        tableData.value = initLeaves.value;
    } else {
        // Filter requests based on selected status
        tableData.value = initLeaves.value.filter(attendanceRequest => attendanceRequest.status === statusFilter);
    }
};

const applyReset = () => {
    selectedRequestStatus.value = '';
    tableData.value = initLeaves.value;
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
