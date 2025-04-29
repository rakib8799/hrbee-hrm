<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">
        <!--begin::Navbar-->
        <div class="row">
            <div v-for="(leaveAllocation, index)  in props?.employeeleaveAllocations" :key="leaveAllocation.id" class="col-lg-2 col-md-4 col-sm-4">
                <div class="card mb-9 text-center h-75">
                    <div class="card-body d-flex flex-column align-items-center justify-content-center">
                        <h6>{{ leaveAllocation.leave_type.name }}</h6>
                        <span>Total: {{ leaveAllocation.number_of_days }}</span>
                        <span>Remaining: {{ props?.leaveRemainingDays && props?.leaveRemainingDays[index] }} </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-toolbar">
            <!--begin::Toolbar-->
            <div class="d-flex justify-content-end" data-kt-customer-table-toolbar="base">
                <button v-if="checkPermission('can-create-leave')" type="button" class="btn btn-primary me-3" data-bs-toggle="modal" data-bs-target="#kt_modal_add_leave">
                    <KTIcon icon-name="plus" icon-class="fs-2" />
                    Request Leave
                </button>
            </div>
            <!--end::Toolbar-->
        </div>

        <!-- Calender View -->
        <div class = "mt-3">
            <FullCalendar :options="calendarOptions" />
        </div>

        <CreateLeaveModal :employeeleaveAllocations="props?.employeeleaveAllocations" :leaveRemainingDays="props?.leaveRemainingDays"></CreateLeaveModal>
    </AuthenticatedLayout>

</template>

<script lang="ts" setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import FullCalendar from '@fullcalendar/vue3';
import multiMonthPlugin from '@fullcalendar/multimonth';
import { ref, defineProps } from 'vue';
import { checkPermission } from '@/Core/helpers/Permission';
import KTIcon from '@/Core/helpers/kt-icon/KTIcon.vue';
import { addOneDayToEndDate, getDayNumberFromName } from '@/Core/helpers/Helper';
import CreateLeaveModal from '@/Pages/Leave/Modals/CreateLeaveModal.vue';

const props = defineProps({
    calendarLeaves: Object as() => IPublicHoliday[] | undefined,
    weekends: Object,
    breadcrumbs: Array as () => Breadcrumb[],
    employeeleaveAllocations: Object,
    leaveRemainingDays: Object,
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
    time_type: string;
    leave: {
        leave_type: {
            color: string;
        }
    }
}

const publicHolidayGrayColor = '#9e9e9e';
const weekendsGrayColor = '#D3D3D3';

const calendarEvents = (props.calendarLeaves || [])?.map(
    calendarLeave => {
        const color = calendarLeave.time_type === 'public-holiday' ? publicHolidayGrayColor : calendarLeave.leave.leave_type.color;

        return {
            title: calendarLeave.name,
            start: calendarLeave.date_from,
            end: addOneDayToEndDate(calendarLeave.date_to),
            color: color
        }
    }
);

const weekend = (props.weekends || [])?.map(((weekend: any) => getDayNumberFromName(weekend)));

const weekendEvent = {
    daysOfWeek: weekend,
    display:"background",
    color: weekendsGrayColor,
    overLap: false,
    allDay: true
};

const allEvents = [...calendarEvents, weekendEvent];

const calendarOptions = ref({
    plugins: [multiMonthPlugin],
    initialView: 'multiMonthYear',
    multiMonthMaxColumns: 2,        
    multiMonthMaxRows: 1,           
    fixedWeekCount: false,          
    events: allEvents,              
    height: 'auto',                 
    dayMaxEventRows: true
});
</script>
