<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { usePage } from '@inertiajs/vue3';
import EmployeeTodayAttendance from '@/Pages/Attendance/EmployeeTodayAttendance.vue';
import Chart from '@/Pages/Chart/Index.vue';
import InitialAccountSetupModal from '@/Pages/InitialAccountSetup/InitialAccountSetupModal.vue';
import { IEmployee } from '@/Core/helpers/Interfaces';

const props = defineProps({
    breadcrumbs: Array as() => Breadcrumb[],
    pageTitle: String,
    isEmployee: Boolean,
    isSuperAdminOrHR: Boolean,
    totalBranch: Number,
    totalJobPosition: Number,
    employees: Object as() => IEmployee[] | undefined,
    attendances: Object
});

interface Breadcrumb {
    url: string;
    title: string;
}

const permissions = usePage().props.permissions;
if (permissions) {
    localStorage.setItem('permissions', JSON.stringify(permissions));
}
</script>

<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">
        <div v-if="props?.isSuperAdminOrHR">
            <EmployeeTodayAttendance :employees="props?.employees" :attendances="props?.attendances" />
        </div>
        <div v-if="props?.isEmployee" class="mt-5">
            <Chart />
        </div>

        <div v-if="props?.isSuperAdminOrHR">
            <InitialAccountSetupModal :totalBranch="props?.totalBranch" :totalJobPosition="props?.totalJobPosition" />
        </div>
    </AuthenticatedLayout>
</template>
