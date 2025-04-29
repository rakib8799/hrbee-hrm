<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">

        <div>
            <FullCalendar :options="calendarOptions" />
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref,defineProps } from 'vue';
import FullCalendar from '@fullcalendar/vue3';
import multiMonthPlugin from '@fullcalendar/multimonth';
import { addOneDayToEndDate, getDayNumberFromName } from '@/Core/helpers/Helper';

const props = defineProps({
    calendarLeaves: Object as() => IPublicHoliday[] | undefined,
    weekends: Object,
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
    time_type: string;
}

const weekendsGrayColor = '#D3D3D3';
const publicHolidayGrayColor = '#9e9e9e';
const publicHoliday = 'public-holiday';

const calendarEvents = (props.calendarLeaves || [])?.map(
    calendarLeave => {
        const timeType = calendarLeave.time_type;
        const color = timeType === publicHoliday ? publicHolidayGrayColor : weekendsGrayColor;
        const name = timeType === publicHoliday ? calendarLeave.name : '';
        const startDate = timeType === publicHoliday ? calendarLeave.date_from : '';
        const endDate = timeType === publicHoliday ? addOneDayToEndDate(calendarLeave.date_to) : '';

        return {
            title: name,
            start: startDate,
            end: endDate,
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
