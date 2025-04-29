<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">

        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1">
                        <KTIcon icon-name="magnifier" icon-class="fs-1 position-absolute ms-6" />
                        <input type="text" v-model="search" @input="searchData()" class="form-control form-control-solid w-250px ps-15" :placeholder="$t('leave.placeholder.searchLeaveTypes')" />
                    </div>
                    <!--end::Search-->
                </div>

                <div class="card-toolbar">
                    <!--begin::Toolbar-->
                    <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                        <!--begin::Add Leave Type-->
                        <Link :href="route('leave-types.create')" class="btn btn-primary" v-if="checkPermission('can-create-leave-type')">
                            <KTIcon icon-name="plus" icon-class="fs-2" />
                                {{ $t('leave.header.leaveType.add') }}
                        </Link>
                        <!--end::Add Leave Type-->
                    </div>
                    <!--end::Toolbar-->
                </div>
            </div>
            <div class="card-body pt-0">
                <Datatable @on-sort="sortData" :data="tableData" :header="tableHeader" :enable-items-per-page-dropdown="true" :checkbox-enabled="false">
                    <!-- Leave Type Name -->
                    <template v-slot:name="{ row: leaveType }">
                        <Link v-if="checkPermission('can-view-details-leave-type')" :href="route('leave-types.show',
                        leaveType.id)" class="text-gray-800 text-hover-primary mb-1">
                            {{ leaveType.name }}
                        </Link>
                        <span v-else>{{ leaveType.name }}</span>
                    </template>

                    <!-- Leave Type Color -->
                    <template v-slot:color="{ row: leaveType }">
                        <input type="color" :value="leaveType.color" disabled>
                    </template>

                    <!-- Leave Type Sequence -->
                    <template v-slot:sequence="{ row: leaveType }">
                        {{ leaveType.sequence }}
                    </template>

                    <!-- Leave Validation Type -->
                    <template v-slot:leave_validation_text="{ row: leaveType }">
                        {{ leaveType.leave_validation_text }}
                    </template>

                    <!-- Leave Type Request Unit -->
                    <template v-slot:request_unit="{ row: leaveType }">
                        {{ caplitalize(leaveType.request_unit) }}
                    </template>

                    <!-- Leave Type Status -->
                    <template v-slot:is_active="{ row: leaveType }" v-if="checkPermission('can-edit-leave-type')">
                        <div class="form-check form-check-solid form-switch">
                            <ChangeStatusButton :obj="leaveType" confirmRoute="leave-types.changeStatus" cancelRoute="leave-types.index" />
                        </div>
                    </template>
                </Datatable>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ChangeStatusButton from '@/Components/Button/ChangeStatusButton.vue';
import { ref, onMounted,defineProps } from 'vue';
import Datatable from "@/Components/kt-datatable/KTDataTable.vue";
import type { Sort } from "@/Components/kt-datatable/table-partials/Models";
import { MenuComponent } from "@/Assets/ts/components";
import arraySort from "array-sort";
import KTIcon from "@/Core/helpers/kt-icon/KTIcon.vue";
import { checkPermission } from "@/Core/helpers/Permission";
import i18n from '@/Core/plugins/i18n';
import { caplitalize } from '@/Core/helpers/Helper';
import { Link } from '@inertiajs/vue3';

const { t } = i18n.global;

const props = defineProps({
    leaveTypes: Array as() => ILeaveTypes[] | undefined,
    breadcrumbs: Array as() => Breadcrumb[],
    pageTitle: String
});

interface Breadcrumb {
    url: string;
    title: string;
}

interface ILeaveTypes {
    id: number;
    name: string;
    color: string;
    sequence: number;
    leave_validation_text: string;
    request_unit: string;
    is_active: boolean;
}

const tableHeader = ref([{
        columnName: t('leave.label.name'),
        columnLabel: "name",
        sortEnabled: true,
        columnWidth: 135
    },
    {
        columnName: t('leave.label.color'),
        columnLabel: "color",
        sortEnabled: true,
        columnWidth: 135
    },
    {
        columnName: t('leave.label.sequence'),
        columnLabel: "sequence",
        sortEnabled: true,
        columnWidth: 135
    },
    {
        columnName: t('leave.label.leaveValidationType'),
        columnLabel: "leave_validation_text",
        sortEnabled: true,
        columnWidth: 135
    },
    {
        columnName: t('leave.label.requestUnit'),
        columnLabel: "request_unit",
        sortEnabled: true,
        columnWidth: 135
    },
    {
        columnName: t('leave.label.status'),
        columnLabel: "is_active",
        sortEnabled: false,
        columnWidth: 75
    }
]);

const tableData = ref < ILeaveTypes[] > ([]);
const initLeaveTypes = ref < ILeaveTypes[] > ([]);

onMounted(() => {
    if (props.leaveTypes) {
        initLeaveTypes.value = props.leaveTypes.map(leaveType => ({
            id: leaveType.id,
            name: leaveType.name,
            color: leaveType.color,
            sequence: leaveType.sequence,
            leave_validation_text: leaveType.leave_validation_text,
            request_unit: leaveType.request_unit,
            is_active: leaveType.is_active
        }));
        tableData.value = initLeaveTypes.value;
    }
});

const search = ref < string > ("");
const searchData = () => {
    tableData.value = [...initLeaveTypes.value];
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
