<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">

        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1">
                        <KTIcon icon-name="magnifier" icon-class="fs-1 position-absolute ms-6" />
                        <input type="text" v-model="search" @input="searchData()" class="form-control form-control-solid w-250px ps-15" placeholder="Search public holidays" />
                    </div>
                    <!--end::Search-->
                </div>

                <div class="card-toolbar">
                    <!--begin::Toolbar-->
                    <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                        <!--begin::Add Public Holiday-->
                        <Link :href="route('public-holidays.create')" class="btn btn-primary" v-if="checkPermission('can-create-public-holiday')">
                            <KTIcon icon-name="plus" icon-class="fs-2" />
                                Add
                        </Link>
                        <!--end::Add Public Holiday-->
                    </div>
                    <!--end::Toolbar-->
                </div>
            </div>
            <div class="card-body pt-0">
                <Datatable @on-sort="sortData" :data="tableData" :header="tableHeader" :enable-items-per-page-dropdown="true" :checkbox-enabled="false">
                    <!-- Name -->
                    <template v-slot:name="{ row: publicHoliday }">
                        {{ publicHoliday.name }}
                    </template>

                    <!-- Start date -->
                    <template v-slot:date_from="{ row: publicHoliday }">
                        {{ publicHoliday.date_from }}
                    </template>

                    <!-- End date -->
                    <template v-slot:date_to="{ row: publicHoliday }">
                        {{ publicHoliday.date_to }}
                    </template>

                    <!-- Actions -->
                    <template v-slot:actions="{ row: publicHoliday }">
                        <div class="d-flex align-items-center justify-content-end">
                            <!-- Edit -->
                            <Link v-if="checkPermission('can-edit-public-holiday')" :href="route('public-holidays.edit', publicHoliday.id)" class="btn btn-icon btn-flex btn-active-light-primary w-30px h-30px" data-bs-toggle="tooltip" :title="$t('tooltip.title.edit')">
                                <KTIcon icon-name="pencil" icon-class="fs-3 text-primary" />
                            </Link>

                            <!-- Delete -->
                            <DeleteConfirmationButton v-if="checkPermission('can-delete-public-holiday')" :obj="publicHoliday" confirmRoute="public-holidays.destroy" />
                            <span v-else>Permission denied</span>
                        </div>
                    </template>
                </Datatable>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, onMounted } from 'vue';
import Datatable from "@/Components/kt-datatable/KTDataTable.vue";
import type { Sort } from "@/Components/kt-datatable/table-partials/Models";
import { MenuComponent } from "@/Assets/ts/components";
import arraySort from "array-sort";
import { Link } from '@inertiajs/vue3';
import KTIcon from "@/Core/helpers/kt-icon/KTIcon.vue";
import { checkPermission } from "@/Core/helpers/Permission";
import { extractDate } from '@/Core/helpers/Helper';
import DeleteConfirmationButton from '@/Components/Button/DeleteConfirmationButton.vue';
// import i18n from '@/Core/plugins/i18n';

// const { t } = i18n.global;

const props = defineProps({
    publicHolidays: Object as() => IPublicHoliday[] | undefined,
    breadcrumbs: Array as() => Breadcrumb[],
    pageTitle: String
});

interface Breadcrumb {
    url: string;
    title: string;
}

interface IPublicHoliday {
    id: number;
    name: string;
    date_from: string;
    date_to: string;
}

const tableHeader = ref([{
        columnName: 'Name',
        columnLabel: "name",
        sortEnabled: true,
        columnWidth: 175
    },
    {
        columnName: 'Start date',
        columnLabel: "date_from",
        sortEnabled: true,
        columnWidth: 175
    },
    {
        columnName: 'End date',
        columnLabel: "date_to",
        sortEnabled: true,
        columnWidth: 175
    },
    {
        columnName: 'Actions',
        columnLabel: "actions",
        sortEnabled: false,
        columnWidth: 75
    }
]);

const tableData = ref < IPublicHoliday[] > ([]);
const initPublicHolidays = ref < IPublicHoliday[] > ([]);

onMounted(() => {
    if (props.publicHolidays) {
        initPublicHolidays.value = props.publicHolidays.map(publicHoliday => ({
            id: publicHoliday.id,
            name: publicHoliday.name,
            date_from: (publicHoliday.date_from) ? extractDate(new Date(publicHoliday.date_from)) : '---',
            date_to: (publicHoliday.date_to) ? extractDate(new Date(publicHoliday.date_to)) : '---'            
        }));
        tableData.value = initPublicHolidays.value;
    }
});

const search = ref < string > ("");
const searchData = () => {
    tableData.value = [...initPublicHolidays.value];
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


