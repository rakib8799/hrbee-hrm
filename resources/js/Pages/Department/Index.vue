<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">

        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1">
                        <KTIcon icon-name="magnifier" icon-class="fs-1 position-absolute ms-6" />
                        <input type="text" v-model="search" @input="searchData()" class="form-control form-control-solid w-250px ps-15" :placeholder="$t('department.placeholder.searchDepartments')" />
                    </div>
                    <!--end::Search-->
                </div>

                <div class="card-toolbar">
                    <!--begin::Toolbar-->
                    <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                        <!--begin::Add Department-->
                        <Link :href="route('departments.create')" class="btn btn-primary" v-if="checkPermission('can-create-department')">
                            <KTIcon icon-name="plus" icon-class="fs-2" />
                                {{ $t('department.header.add') }}
                        </Link>
                        <!--end::Add Department-->
                    </div>
                    <!--end::Toolbar-->
                </div>
            </div>
            <div class="card-body pt-0">
                <Datatable @on-sort="sortData" :data="tableData" :header="tableHeader" :enable-items-per-page-dropdown="true" :checkbox-enabled="false">
                    <!-- Department Name -->
                    <template v-slot:name="{ row: department }">
                        <Link v-if="checkPermission('can-view-details-department')" :href="route('departments.show', department.id)">
                            {{ department.name }}
                        </Link>
                    </template>

                    <!-- Slug -->
                    <template v-slot:slug="{ row: department }">
                        {{ department.slug }}
                    </template>

                    <!-- Parent Department -->
                    <template v-slot:parent_id="{ row: department }">
                        <span v-if="department.parent_id !== null">
                            <span v-for="parentDepartment in parentDepartments" :key="parentDepartment.id">
                                <span v-if="parentDepartment.id == department.parent_id">{{ parentDepartment.name }}</span>
                                <span v-else>{{ '' }}</span>
                            </span>
                        </span>
                        <span v-else>
                            ---
                        </span>
                    </template>

                    <!-- Department Actions -->
                    <template v-slot:actions="{ row: department }" v-if="checkPermission('can-edit-department') || checkPermission('can-delete-department')">
                        <div class="d-flex align-items-center justify-content-end">
                            <!-- Active Status -->
                            <div class="form-check form-check-solid form-switch">
                                <ChangeStatusButton v-if="checkPermission('can-edit-department')" :obj="department" confirmRoute="departments.changeStatus" cancelRoute="departments.index" />
                            </div>
                            <!-- Edit -->
                            <Link v-if="checkPermission('can-edit-department')" :href="route('departments.edit', department.id)" class="btn btn-icon btn-flex btn-active-light-primary w-30px h-30px" data-bs-toggle="tooltip" :title="$t('tooltip.title.edit')">
                                <KTIcon icon-name="pencil" icon-class="fs-3 text-primary" />
                            </Link>
                        </div>
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
import ChangeStatusButton from '@/Components/Button/ChangeStatusButton.vue';
import i18n from '@/Core/plugins/i18n';

const { t } = i18n.global;

const props = defineProps({
    parentDepartments: Object,
    departments: Object as() => IDepartment[] | undefined,
    breadcrumbs: Array as() => Breadcrumb[],
    pageTitle: String
});

interface Breadcrumb {
    url: string;
    title: string;
}

interface IDepartment {
    id: number;
    name: string;
    slug: string;
    parent_id: any;
    is_active: boolean;
}

const tableHeader = ref([{
        columnName: t('department.label.name'),
        columnLabel: "name",
        sortEnabled: true,
        columnWidth: 175
    },
    {
        columnName: t('department.label.slug'),
        columnLabel: "slug",
        sortEnabled: true,
        columnWidth: 175
    },
    {
        columnName: t('department.label.parentDepartment'),
        columnLabel: "parent_id",
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

const tableData = ref < IDepartment[] > ([]);
const initDepartments = ref < IDepartment[] > ([]);

onMounted(() => {
    if (props.departments) {
        initDepartments.value = props.departments.map(department => ({
            id: department.id,
            name: department.name,
            slug: department.slug,
            parent_id: department.parent_id,
            is_active: department.is_active
        }));
        tableData.value = initDepartments.value;
    }
});

const search = ref < string > ("");
const searchData = () => {
    tableData.value = [...initDepartments.value];
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
