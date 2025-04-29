<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">

        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1">
                        <KTIcon icon-name="magnifier" icon-class="fs-1 position-absolute ms-6" />
                        <input type="text" v-model="search" @input="searchData()" class="form-control form-control-solid w-250px ps-15" placeholder="Search leave allocations" />
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

                        <!--begin::Add Leave Allocation-->
                        <Link :href="route('leave-allocations.create')" class="btn btn-primary" v-if="checkPermission('can-create-leave-allocation')">
                            <KTIcon icon-name="plus" icon-class="fs-2" />
                            Add Leave Allocation
                        </Link>
                        <!--end::Add Leave Allocation-->
                    </div>
                    <!--end::Toolbar-->
                </div>
            </div>
            <div class="card-body pt-0">
                <Datatable @on-sort="sortData" :data="tableData" :header="tableHeader" :enable-items-per-page-dropdown="true" :checkbox-enabled="false">
                    <!-- Employee Name -->
                    <template v-slot:employee="{ row: leaveAllocation }">
                        <Link v-if="checkPermission('can-edit-leave-allocation') && leaveAllocation.status=='pending'" :href="route('leave-allocations.edit', leaveAllocation.id)">
                            <div v-if="leaveAllocation.employee" class="d-flex align-items-center">
                                <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                    <div class="symbol-label">
                                        <!-- <img :src="getAssetPath('media/avatars/300-1.jpg')" alt="Emma Smith" class="w-100" /> -->
                                        <div class="symbol-label fs-3 bg-light-danger text-danger">{{ getInitials(leaveAllocation.employee) }}</div>
                                    </div>
                                </div>
                                <div class="d-flex flex-column">
                                    
                                    <span>{{ getFullName(leaveAllocation.employee) }}</span>
                                </div>
                            </div>
                            <div v-else-if="leaveAllocation.department">
                                {{ leaveAllocation.department.name }}
                            </div>
                            <div v-else>
                                {{ caplitalize(leaveAllocation.holiday_type) }}
                            </div>
                        </Link>
                        <div v-else>
                            <div v-if="leaveAllocation.employee" class="d-flex align-items-center">
                                <div class="symbol symbol-circle symbol-50px overflow-hidden me-3">
                                    <div class="symbol-label">
                                        <!-- <img :src="getAssetPath('media/avatars/300-1.jpg')" alt="Emma Smith" class="w-100" /> -->
                                        <div class="symbol-label fs-3 bg-light-danger text-danger">{{ getInitials(leaveAllocation.employee) }}</div>
                                    </div>
                                </div>
                                <div class="d-flex flex-column">
                                    
                                    <span>{{ getFullName(leaveAllocation.employee) }}</span>
                                </div>
                            </div>
                            <div v-else-if="leaveAllocation.department">
                                {{ leaveAllocation.department.name }}
                            </div>
                            <div v-else>
                                {{ caplitalize(leaveAllocation.holiday_type) }}
                            </div>
                        </div>
                    </template>
                    
                    <!-- Leave Type -->
                    <template v-slot:leave_type="{ row: leaveAllocation }">
                        {{ leaveAllocation.leave_type ? leaveAllocation.leave_type.name : ''  }}
                    </template>

                    <!-- Leave Allocation Name -->
                    <template v-slot:name="{ row: leaveAllocation }">            
                        {{ leaveAllocation.name }}
                    </template>

                    <!-- Leave Allocation Type -->
                    <template v-slot:allocation_type="{ row: leaveAllocation }">
                        {{ leaveAllocation.allocation_type }}
                    </template>

                    <!-- Leave Allocation Days -->
                    <template v-slot:number_of_days="{ row: leaveAllocation }">
                        {{ leaveAllocation.number_of_days }}
                    </template>

                    <!-- Leave Allocation Approval Status -->
                    <template v-slot:status="{ row: leaveAllocation }">
                        <b :class="{
                            'text-success': leaveAllocation.status === 'approved',
                            'text-warning': leaveAllocation.status === 'pending',
                            'text-danger': leaveAllocation.status === 'declined'
                            }">
                            {{ leaveAllocation.status }}
                        </b>
                    </template>

                    <!-- Leave Allocation Actions -->
                    <template v-slot:actions="{ row: leaveAllocation }" v-if="checkPermission('can-approve-leave-allocation')">
                        <div v-if="leaveAllocation?.status==='pending'">
                            <!-- Approve -->
                            <ApproveConfirmationButton  
                            :obj="leaveAllocation" 
                            confirmRoute="leave-allocations.approve" 
                            :title="`Are you sure want to approve leave allocation for ${leaveAllocation?.leave_type.name}?`"
                            />

                            <!-- Decline -->
                            <DeclineConfirmationButton  
                            :obj="leaveAllocation" 
                            confirmRoute="leave-allocations.decline" 
                            :title="`Are you sure want to decline leave allocation for ${leaveAllocation?.leave_type.name}?`"
                            />
                        </div>
                        <div v-else>
                            
                        </div>
                    </template>

                    <template v-slot:actions="{ row: leaveAllocation }" v-else>
                        Permission Denied
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
import { Link } from '@inertiajs/vue3';
import KTIcon from "@/Core/helpers/kt-icon/KTIcon.vue";
import { checkPermission } from "@/Core/helpers/Permission";
import ApproveConfirmationButton from '@/Components/Button/ApproveConfirmationButton.vue';
import DeclineConfirmationButton from '@/Components/Button/DeclineConfirmationButton.vue';
import Multiselect from '@vueform/multiselect';
import { getFullName, getInitials, caplitalize } from '@/Core/helpers/Helper';
import i18n from '@/Core/plugins/i18n';

const { t } = i18n.global;

const props = defineProps({
    leaveAllocations: Object as() => ILeaveAllocations[] | undefined,
    requestStatuses: Object,
    breadcrumbs: Array as() => Breadcrumb[],
    pageTitle: String
});

interface Breadcrumb {
    url: string;
    title: string;
}

interface ILeaveAllocations {
    id: number;
    holiday_type: string;
    employee?: {
        id: number;
        first_name: string;
        last_name: string;
    };
    department?:{
        id: number;
        name: string;
    }
    leave_type?: {
        id: number;
        name: string;
    };
    name: string;
    allocation_type: string;
    number_of_days: number;
    is_active: string;
    status: string;
}

const tableHeader = ref([{
        columnName: 'Employee',
        columnLabel: "employee",
        sortEnabled: true,
        columnWidth: 150
    },
    {
        columnName: 'Leave Type',
        columnLabel: "leave_type",
        sortEnabled: true,
        columnWidth: 120
    },
    {
        columnName: 'Name',
        columnLabel: "name",
        sortEnabled: true,
        columnWidth: 120
    },
    {
        columnName: 'Allocation',
        columnLabel: "allocation_type",
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
        columnName: 'Approval Status',
        columnLabel: "status",
        sortEnabled: true,
        columnWidth: 120
    },
    {
        columnName: t('buttonValue.actions'),
        columnLabel: "actions",
        sortEnabled: false,
        columnWidth: 75
    }
]);

const selectedRequestStatus = ref<string>('');
const tableData = ref < ILeaveAllocations[] > ([]);
const initLeaveAllocations = ref < ILeaveAllocations[] > ([]);

onMounted(() => {
    if (props.leaveAllocations) {
        initLeaveAllocations.value = props.leaveAllocations.map(leaveAllocation => ({
            id: leaveAllocation.id,
            holiday_type: leaveAllocation.holiday_type,
            name: leaveAllocation.name,
            number_of_days: leaveAllocation.number_of_days,
            leave_type: leaveAllocation.leave_type,
            employee: leaveAllocation.employee,
            department: leaveAllocation.department,
            allocation_type: leaveAllocation.allocation_type,
            is_active: leaveAllocation.is_active,
            status: leaveAllocation.status
        }));
        tableData.value = initLeaveAllocations.value;
    }
});

const search = ref < string > ("");
const searchData = () => {
    tableData.value = [...initLeaveAllocations.value];
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

// assign all the requestStatuses from props to allRequestStatuses variable.
const allRequestStatuses = ref<Array<any>>([]);
if (Array.isArray(props.requestStatuses) && props.requestStatuses.length > 0) {
    allRequestStatuses.value = props.requestStatuses.map(status => ({value: status.value, text:status.text}));
}

const applyFilter = () => {
    const statusFilter = selectedRequestStatus.value;
    if (statusFilter.length == 0) {
        // If nothing is selected, show all requests
        tableData.value = initLeaveAllocations.value;
    } else {
        // Filter requests based on selected status
        tableData.value = initLeaveAllocations.value.filter(leaveAllocation => leaveAllocation.status === statusFilter);
    }
};

const applyReset = () => {
    selectedRequestStatus.value = '';
    tableData.value = initLeaveAllocations.value;
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
