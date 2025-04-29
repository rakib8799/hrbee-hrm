<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">

        <LeaveTypeHeader activeLink="LeaveTypeAllocationInfo" :id="`${props.leaveType?.id}`"></LeaveTypeHeader>

        <div class="card mb-5 mb-xl-10" id="kt_profile_details_view">
            <!--begin::Card header-->
            <div class="card-header cursor-pointer">
                <div class="card-title">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1">
                        <KTIcon icon-name="magnifier" icon-class="fs-1 position-absolute ms-6" />
                        <input type="text" v-model="search" @input="searchData()" class="form-control form-control-solid w-250px ps-15" placeholder="Search leave allocations" />
                    </div>
                    <!--end::Search-->
                </div>
            </div>
            <!--end::Card header-->

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
                </Datatable>
            </div>
        </div>
    </AuthenticatedLayout>
</template>


<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import LeaveTypeHeader from '@/Pages/LeaveType/LeaveTypeHeader.vue';
import { onMounted, ref } from 'vue';
import Datatable from "@/Components/kt-datatable/KTDataTable.vue";
import type { Sort } from "@/Components/kt-datatable/table-partials/Models";
import { MenuComponent } from "@/Assets/ts/components";
import arraySort from "array-sort";
import { Link } from '@inertiajs/vue3';
import { checkPermission } from "@/Core/helpers/Permission";
import { getFullName, getInitials, caplitalize } from '@/Core/helpers/Helper';
import i18n from '@/Core/plugins/i18n';
import KTIcon from '@/Core/helpers/kt-icon/KTIcon.vue';

const { t } = i18n.global;

const props = defineProps({
    leaveType: Object,
    leaveAllocations: Object as() => ILeaveAllocations[] | undefined,
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
    }
]);

const tableData = ref < ILeaveAllocations[] > ([]);
const initLeaveAllocations = ref < ILeaveAllocations[] > ([]);

onMounted(() => {
    if (props.leaveAllocations) {
        initLeaveAllocations.value = props.leaveAllocations.map(leaveAllocation => ({
            id: leaveAllocation.id,
            holiday_type: leaveAllocation.holiday_type,
            name: leaveAllocation.name,
            number_of_days: leaveAllocation.number_of_days,
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
