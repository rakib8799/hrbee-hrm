<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="tabTitle">

        <div class="card">
            <div class="card-header border-0 pt-6">
                <!--begin::Toolbar-->
                <div class="card-toolbar m-0">
                    <!--begin::Tab nav-->
                    <ul class="nav nav-stretch fs-5 fw-semibold nav-line-tabs nav-line-tabs-2x border-transparent" role="tablist" >
                        <li class="nav-item" role="presentation">
                            <a id="kt_referrals_department_details_tab" class="nav-link text-active-primary" data-bs-toggle="tab" role="tab" href="javascript:void(0)"  @click="activeTab = 1"
                            :class="{ 'active': activeTab === 1 }">
                                {{ $t('department.header.departmentDetails') }}
                            </a>
                        </li>

                        <li v-if="checkPermission('can-view-employee')" class="nav-item" role="presentation">
                            <a id="kt_referrals_employee_list_tab" class="nav-link text-active-primary" data-bs-toggle="tab" role="tab" href="javascript:void(0)" @click="activeTab = 2"
                            :class="{ 'active': activeTab === 2 }">
                                {{ $t('commonComponents.employeeComponent.header.employeeList') }}
                            </a>
                        </li>
                    </ul>
                    <!--end::Tab nav-->
                </div>
                <!--end::Toolbar-->

                <div class="card-title m-0" v-show="activeTab === 1">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1">
                        <!-- Active Status -->
                        <div class="form-check form-check-solid form-switch">
                            <ChangeStatusButton v-if="props.department?.id && checkPermission('can-edit-department')" :obj="props?.department" confirmRoute="departments.changeStatus"/>
                        </div>

                        <!-- Edit -->
                        <Link v-if="checkPermission('can-edit-department')" :href="route('departments.edit', props.department?.id)" class="btn btn-icon btn-flex btn-active-light-primary w-30px h-30px" data-bs-toggle="tooltip" title="Edit">
                            <KTIcon icon-name="pencil" icon-class="fs-3 text-primary" />
                        </Link>
                    </div>
                    <!--end::Search-->
                </div>

                <div class="card-title m-0" v-show="activeTab === 2">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1">
                        <KTIcon icon-name="magnifier" icon-class="fs-1 position-absolute ms-6" />
                        <input type="text" v-model="search" @input="searchData()" class="form-control form-control-solid w-250px ps-15" :placeholder="$t('commonComponents.employeeComponent.placeholder.searchEmployees')" />
                    </div>
                    <!--end::Search-->
                </div>
            </div>
            <div class="card-body pt-0">
                <div id="kt_referred_employees_tab_content" class="tab-content">
                    <!-- Department Details -->
                    <div id="department_details" class="py-4 tab-pane fade active show" role="tabpanel" v-show="activeTab === 1">
                        <!-- Name -->
                        <div class="row mb-7">
                            <label class="col-lg-4 fw-semibold text-muted">{{ $t('department.label.name') }}</label>
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-900">{{ props.department?.name }}</span>
                            </div>
                        </div>

                        <!-- Slug -->
                        <div class="row mb-7">
                            <label class="col-lg-4 fw-semibold text-muted">{{ $t('department.label.slug') }}</label>
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-900">{{ props.department?.slug }}</span>
                            </div>
                        </div>

                        <!-- Parent Id -->
                        <div class="row mb-7">
                            <label class="col-lg-4 fw-semibold text-muted">{{ $t('department.label.parentDepartment') }}</label>
                            <div class="col-lg-8">
                                <span class="fw-bold fs-6 text-gray-900">
                                    <span v-if="props.department?.parent_id !== null">
                                        <span v-for="parentDepartment in props?.parentDepartments" :key="parentDepartment.id">
                                            <span v-if="parentDepartment.id == props.department?.parent_id">{{ parentDepartment.name }}</span>
                                            <span v-else>{{ '' }}</span>
                                        </span>
                                    </span>
                                    <span v-else>
                                        ---
                                    </span>
                                </span>
                            </div>
                        </div>
                    </div>
                    <!-- Employee List -->
                    <div id="employee_list" class="py-0 tab-pane fade active show" role="tabpanel" v-show="activeTab === 2">
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
                                            <div class="symbol-label fs-3 bg-light-danger text-danger">{{ getFullName(employee) }}</div>
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

                            <!-- Employee ID -->
                            <template v-slot:staff_id="{ row: employee }">
                                {{ employee.staff_id }}
                            </template>

                            <!-- Employee Job Title -->
                            <template v-slot:job_title="{ row: employee }">
                                {{ employee.job_title }}
                            </template>

                            <!-- Employee Active Status -->
                            <template v-slot:joining_date="{ row: employee }">
                                {{ employee.joining_date }}
                            </template>

                            <!-- Employee Last Login -->
                            <template v-slot:last_login="{ row: employee }">
                                {{  }}
                            </template>
                        </Datatable>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, onMounted, defineProps, watch } from 'vue';
import Datatable from "@/Components/kt-datatable/KTDataTable.vue";
import type { Sort } from "@/Components/kt-datatable/table-partials/Models";
import { MenuComponent } from "@/Assets/ts/components";
import arraySort from "array-sort";
import { Link } from '@inertiajs/vue3'
import KTIcon from "@/Core/helpers/kt-icon/KTIcon.vue";
import { checkPermission } from "@/Core/helpers/Permission";
import { getFullName, getInitials } from '@/Core/helpers/Helper';
import ChangeStatusButton from '@/Components/Button/ChangeStatusButton.vue';
import i18n from '@/Core/plugins/i18n';

const { t } = i18n.global;

const activeTab = ref(1);

const props = defineProps({
    employees: Object as() => IEmployee[] | undefined,
    parentDepartments: Object,
    department: Object,
    breadcrumbs: Array as() => Breadcrumb[],
    pageTitle: String,
});

const tabTitle = ref(props?.pageTitle);
watch(activeTab, () => {
    tabTitle.value = activeTab.value === 1 ? t('department.header.departmentDetails') : t('commonComponents.employeeComponent.header.employeeList');
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
    user_id: string;
    staff_id: string;
    job_title: string;
    joining_date: string;
}

const tableHeader = ref([
    {
        columnName: t('commonComponents.employeeComponent.label.name'),
        columnLabel: "first_name",
        sortEnabled: true,
        columnWidth: 175
    },
    {
        columnName: t('commonComponents.employeeComponent.label.employeeId'),
        columnLabel: "staff_id",
        sortEnabled: true,
        columnWidth: 130
    },
    {
        columnName: t('commonComponents.employeeComponent.label.jobTitle'),
        columnLabel: "job_title",
        sortEnabled: true,
        columnWidth: 130
    },
    {
        columnName: t('commonComponents.employeeComponent.label.joiningDate'),
        columnLabel: "joining_date",
        sortEnabled: true,
        columnWidth: 130
    },
    {
        columnName: t('commonComponents.employeeComponent.label.lastLogin'),
        columnLabel: "last_login",
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
            user_id: employee.user_id,
            staff_id: employee.staff_id,
            job_title: employee.job_title,
            joining_date: employee.joining_date,
        }));
        tableData.value = initEmployees.value;
    }
});

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

const sortData = (sort: Sort) => {
    const reverse: boolean = sort.order === "asc";
    if (sort.label) {
        arraySort(tableData.value, sort.label, {
            reverse
        });
    }
};
</script>
