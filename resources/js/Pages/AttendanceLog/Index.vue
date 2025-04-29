<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">

        <EmployeeHeader activeLink="AttendanceLog" :id="`${props.employee?.id}`" :employee="props?.employee" :departureReasons="props?.departureReasons"></EmployeeHeader>

        <div class="card">
            <div class="card-header border-0 pt-6">
                <div class="card-title">
                    <!--begin::Search-->
                    <div class="d-flex align-items-center position-relative my-1">
                        <KTIcon icon-name="magnifier" icon-class="fs-1 position-absolute ms-6" />
                        <input type="text" v-model="search" @input="searchData()" class="form-control form-control-solid w-250px ps-15" :placeholder="$t('attendanceLog.placeholder.searchAttendanceLogs')" />
                    </div>
                    <!--end::Search-->
                </div>

                <div class="card-toolbar">
                    <!--begin::Toolbar-->
                    <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                        <!--begin::Missing Punch-->
                        <!-- <button v-if="checkPermission('can-create-attendance-log')" type="button" class="btn btn-primary me-3" data-bs-toggle="modal" data-bs-target="#kt_modal_add_missing_punch">
                            <KTIcon icon-name="plus" icon-class="fs-2" />
                            {{ $t('attendanceLog.header.addMissingPunch') }}
                        </button> -->
                        <!--end::Missing Punch-->

                        <!--begin::Add Attendance Log-->
                        <!-- <button v-if="checkPermission('can-create-attendance-log')" type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_attendance_log">
                            <KTIcon icon-name="plus" icon-class="fs-2" />
                            {{ $t('attendanceLog.header.add') }}
                        </button> -->
                        <!--end::Add Attendance-->
                    </div>
                    <!--end::Toolbar-->
                </div>
            </div>
            <div class="card-body pt-0">
                <Datatable @on-sort="sortData" :data="tableData" :header="tableHeader" :enable-items-per-page-dropdown="true" :checkbox-enabled="false">
                    <!-- Punch Time -->
                    <template v-slot:punch_type="{ row: attendanceLog }">
                        {{ attendanceLog.punch_type }}
                    </template>

                    <!-- Punch Type -->
                    <template v-slot:punch_time="{ row: attendanceLog }">
                        {{ attendanceLog.punch_time }}
                    </template>

                    <!-- Note -->
                    <template v-slot:note="{ row: attendanceLog }">
                        {{ attendanceLog.note }}
                    </template>

                    <!-- Attendance Log Actions -->
                    <!-- <template v-slot:actions="{ row: attendanceLog }" v-if="checkPermission('can-edit-attendance-log') || checkPermission('can-delete-attendance-log')"> -->
                        <!-- Edit -->
                        <!-- <button v-if="checkPermission('can-edit-attendance-log')" type="button" class="btn btn-icon btn-flex btn-active-light-primary w-30px h-30px" :title="$t('tooltip.title.edit')" data-bs-toggle="modal" data-bs-target="#kt_modal_edit_attendance_log" @click="assignAttendanceLogData(attendanceLog)">
                            <KTIcon icon-name="pencil" icon-class="fs-3 text-primary" />
                        </button> -->

                        <!-- Delete -->
                        <!-- <DeleteConfirmationButton v-if="checkPermission('can-delete-attendance-log')" :attendanceId="attendanceLog?.id" :employee="props?.employee" confirmRoute="attendance-log.destroy" />
                    </template>
                    <template v-slot:actions="{ row: attendanceLog }" v-else>
                        {{ $t('confirmation.permissionDenied') }}
                    </template> -->
                </Datatable>
            </div>
        </div>

        <!-- <CreateAttendanceLogModal :employee="props?.employee" :punchTypes="punchTypes" :workLocations="workLocations"></CreateAttendanceLogModal>
        <EditAttendanceLogModal :employee="props?.employee" :attendanceLog="attendanceLogData" :punchTypes="punchTypes" :workLocations="workLocations"></EditAttendanceLogModal>
        <CreateMissingPunchModal :employee="props?.employee" :punchTypes="punchTypes"></CreateMissingPunchModal> -->
    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import EmployeeHeader from '@/Pages/Employee/EmployeeHeader.vue';
import DeleteConfirmationButton from '@/Components/Button/DeleteConfirmationButton.vue';
// import CreateAttendanceLogModal from '@/Pages/AttendanceLog/Modals/CreateAttendanceLogModal.vue';
// import EditAttendanceLogModal from '@/Pages/AttendanceLog/Modals/EditAttendanceLogModal.vue';
// import CreateMissingPunchModal from '@/Pages/AttendanceLog/Modals/CreateMissingPunchModal.vue';
import { ref, onMounted, defineProps } from 'vue';
import Datatable from "@/Components/kt-datatable/KTDataTable.vue";
import type { Sort } from "@/Components/kt-datatable/table-partials/Models";
import { MenuComponent } from "@/Assets/ts/components";
import arraySort from "array-sort";
import KTIcon from "@/Core/helpers/kt-icon/KTIcon.vue";
import { checkPermission } from "@/Core/helpers/Permission";
import i18n from '@/Core/plugins/i18n';

const { t } = i18n.global;

const props = defineProps({
    employee: Object,
    attendanceLogs: Object as() => IAttendanceLogs[] | undefined,
    breadcrumbs: Array as() => Breadcrumb[],
    punchTypes: Object,
    workLocations: Object,
    pageTitle: String,
    departureReasons: Object
});

interface Breadcrumb {
    url: string;
    title: string;
}

interface IAttendanceLogs {
    id: number;
    punch_time: string;
    punch_type: string;
    note: string;
}

const tableHeader = ref([
    {
        columnName: t('attendanceLog.label.punchType'),
        columnLabel: "punch_type",
        sortEnabled: true,
        columnWidth: 175
    },
    {
        columnName: t('attendanceLog.label.punchTime'),
        columnLabel: "punch_time",
        sortEnabled: true,
        columnWidth: 230
    },
    {
        columnName: t('attendanceLog.label.note'),
        columnLabel: "note",
        sortEnabled: true,
        columnWidth: 175
    },
    // {
    //     columnName: t('buttonValue.actions'),
    //     columnLabel: "actions",
    //     sortEnabled: false,
    //     columnWidth: 135
    // }
]);

const tableData = ref < IAttendanceLogs[] > ([]);
const initAttendanceLogs = ref < IAttendanceLogs[] > ([]);

onMounted(() => {
    if (props.attendanceLogs) {
        initAttendanceLogs.value = props.attendanceLogs.map(attendanceLog => ({
            id: attendanceLog.id,
            punch_type: attendanceLog.punch_type,
            punch_time: attendanceLog.punch_time,
            note: attendanceLog.note
        }));
        tableData.value = initAttendanceLogs.value;
    }
});

const search = ref < string > ("");
const searchData = () => {
    tableData.value = [...initAttendanceLogs.value];
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

interface AttendanceLogData {
    id: number | null;
    punchType: string | null;
    punchTime: string | null;
    workFrom: string | null;
}

const attendanceLogData = ref<AttendanceLogData>({
    id: null,
    punchType: null,
    punchTime: null,
    workFrom: null
});

// Need for updating Attendance Log
const assignAttendanceLogData = (attendanceLog: IAttendanceLogs) => {
    attendanceLogData.value.id = attendanceLog.id;
    attendanceLogData.value.punchType = attendanceLog.punch_type;
    attendanceLogData.value.punchTime = attendanceLog.punch_time;
    attendanceLogData.value.workFrom = attendanceLog.note;
};
</script>
