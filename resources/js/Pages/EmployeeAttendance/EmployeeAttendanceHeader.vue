<template>
    <!--begin::Navbar-->
    <div class="card mb-9">
        <div class="card-body pb-0">
            <!--begin::Navs-->
            <div class="d-flex overflow-auto h-55px">
                <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold flex-nowrap">
                    <li class="nav-item" v-if="checkPermission('can-view-employee-attendance')">
                        <Link :href="route('employee-attendances.index')" :class="{ active: isActive('Attendance') }"
                            class="nav-link text-active-primary me-6">
                            {{ $t('loggedInEmployeeAttendance.header.attendance.title') }}
                        </Link>
                    </li>
                    <li class="nav-item" v-if="checkPermission('can-view-employee-attendance-request')">
                        <Link :href="route('employee-attendance-requests.index')" :class="{ active: isActive('AttendanceRequest') }"
                            class="nav-link text-active-primary me-6">{{ $t('loggedInEmployeeAttendance.header.attendanceRequest.title') }}
                        </Link>
                    </li>
                    <li class="nav-item" v-if="checkPermission('can-view-employee-attendance-log')">
                        <Link :href="route('employee-attendance-logs.index')" :class="{ active: isActive('AttendanceLog') }"
                            class="nav-link text-active-primary me-6">{{ $t('loggedInEmployeeAttendance.header.attendanceLog.title') }}
                        </Link>
                    </li>
                </ul>
            </div>
            <!--end::Navs-->
        </div>
    </div>
    <slot></slot>
    <!--end::Navbar-->
</template>

<script lang="ts" setup>
import {Link} from '@inertiajs/vue3';
import { checkPermission } from "@/Core/helpers/Permission";

const props = defineProps({
    activeLink: String
});

const headerActiveLink = props.activeLink;

const isActive = (linkName: String) => {
    return headerActiveLink == linkName;
};
</script>
