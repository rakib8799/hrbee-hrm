<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">

        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1">
                        <KTIcon icon-name="magnifier" icon-class="fs-1 position-absolute ms-6" />
                        <input type="text" v-model="search" @input="searchData()" class="form-control form-control-solid w-250px ps-15" :placeholder="$t('branch.placeholder.searchBranches')" />
                    </div>
                    <!--end::Search-->
                </div>

                <div class="card-toolbar">
                    <!--begin::Toolbar-->
                    <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                        <!--begin::Add Branch-->
                        <Link :href="route('branches.create')" class="btn btn-primary" v-if="checkPermission('can-create-branch')">
                            <KTIcon icon-name="plus" icon-class="fs-2" />
                                {{ $t('branch.header.add') }}
                        </Link>
                        <!--end::Add Branch-->
                    </div>
                    <!--end::Toolbar-->
                </div>
            </div>
            <div class="card-body pt-0">
                <Datatable @on-sort="sortData" :data="tableData" :header="tableHeader" :enable-items-per-page-dropdown="true" :checkbox-enabled="false">
                    <!-- Branch Name -->
                    <template v-slot:name="{ row: branch }">
                        <Link v-if="checkPermission('can-view-details-branch')" :href="route('branches.show', branch.id)">
                            {{ branch.name }}
                        </Link>
                        <span v-else>{{ branch.name }}</span>
                    </template>

                    <!-- Branch Manager -->
                    <template v-slot:manager_id="{ row: branch }">
                        <span v-if="branch.manager_id !== ''">
                            <span v-for="employee in props?.employees" :key="employee.id">
                                <span v-if="employee.id == branch.manager_id">{{ getFullName(employee) }}</span>
                                <span v-else>{{ '' }}</span>
                            </span>
                        </span>
                    </template>

                    <!-- Branch Code -->
                    <template v-slot:branch_code="{ row: branch }">
                        {{ branch.branch_code }}
                    </template>

                    <!-- Branch Phone Number -->
                    <template v-slot:phone_number="{ row: branch }">
                        {{ branch.phone_number }}
                    </template>

                    <!-- Branch Actions -->
                    <template v-slot:actions="{ row: branch }" v-if="checkPermission('can-edit-branch') || checkPermission('can-delete-branch')">
                        <div class="d-flex align-items-center justify-content-end">
                            <!-- Active Status -->
                            <div class="form-check form-check-solid form-switch">
                                <ChangeStatusButton v-if="checkPermission('can-edit-branch')" :obj="branch" confirmRoute="branches.changeStatus" cancelRoute="branches.index" />
                            </div>
                            <!-- Edit -->
                            <Link v-if="checkPermission('can-edit-branch')" :href="route('branches.edit', branch.id)" class="btn btn-icon btn-flex btn-active-light-primary w-30px h-30px" data-bs-toggle="tooltip" :title="$t('tooltip.title.edit')">
                                <KTIcon icon-name="pencil" icon-class="fs-3 text-primary" />
                            </Link>
                        </div>
                    </template>
                    <template v-slot:actions="{ row: branch }" v-else>
                        {{ $t('confirmation.permissionDenied') }}
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
import { getFullName } from '@/Core/helpers/Helper';
import ChangeStatusButton from '@/Components/Button/ChangeStatusButton.vue';
import i18n from '@/Core/plugins/i18n';

const { t } = i18n.global;

const props = defineProps({
    employees: Object,
    branches: Object as() => IBranch[] | undefined,
    breadcrumbs: Array as() => Breadcrumb[],
    pageTitle: String
});

interface Breadcrumb {
    url: string;
    title: string;
}

interface IBranch {
    id: number;
    name: string;
    manager_id: string;
    branch_code: string;
    phone_number: string;
    is_active: boolean;
}

const tableHeader = ref([{
        columnName: t('branch.label.name'),
        columnLabel: "name",
        sortEnabled: true,
        columnWidth: 175
    },
    {
        columnName: t('branch.label.branchManager'),
        columnLabel: "manager_id",
        sortEnabled: true,
        columnWidth: 230
    },
    {
        columnName: t('branch.label.branchCode'),
        columnLabel: "branch_code",
        sortEnabled: true,
        columnWidth: 175
    },
    {
        columnName: t('branch.label.phoneNumber'),
        columnLabel: "phone_number",
        sortEnabled: true,
        columnWidth: 175
    },
    {
        columnName: t('buttonValue.actions'),
        columnLabel: "actions",
        sortEnabled: false,
        columnWidth: 75
    }
]);

const tableData = ref < IBranch[] > ([]);
const initBranches = ref < IBranch[] > ([]);

onMounted(() => {
    if (props.branches) {
        initBranches.value = props.branches.map(branch => ({
            id: branch.id,
            manager_id: branch.manager_id,
            name: branch.name,
            branch_code: branch.branch_code,
            phone_number: branch.phone_number,
            is_active: branch.is_active
        }));
        tableData.value = initBranches.value;
    }
});

const search = ref < string > ("");
const searchData = () => {
    tableData.value = [...initBranches.value];
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
