<template>
    <!--begin::Navbar-->
    <div class="card mb-9">
        <div class="card-body pb-0">

            <!--begin::Navs-->
            <div class="d-flex overflow-auto h-55px">
                <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold flex-nowrap" v-if="isShowOrEditRoute">
                    <li class="nav-item" v-if="checkPermission('can-view-details-leave-type')">
                        <Link :href="route('leave-types.show', props.id)" :class="{ active: isActive('Details') }"
                            class="nav-link text-active-primary me-6">{{ $t('leave.header.leaveType.details') }}
                        </Link>
                    </li>
                    <li class="nav-item" v-if="checkPermission('can-edit-leave-type')">
                        <Link :href="route('leave-types.edit', props.id)" :class="{ active: isActive('EditLeaveType') }"
                            class="nav-link text-active-primary me-6">{{ $t('leave.header.leaveType.leaveTypeInfo.title') }}
                        </Link>
                    </li>
                    <li class="nav-item" v-if="checkPermission('can-view-leave-allocation')">
                        <Link :href="route('leave-types.leaveTypeAllocation', props.id)" :class="{ active: isActive('LeaveTypeAllocationInfo') }"
                            class="nav-link text-active-primary me-6">{{ $t('leave.header.leaveType.leaveTypeAllocationInfo.title') }}
                        </Link>
                    </li>
                </ul>
                <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold flex-nowrap" v-else>
                    <li class="nav-item" v-if="checkPermission('can-create-leave-type')">
                        <Link :href="route('leave-types.create')" :class="{ active: isActive('CreateLeaveType') }"
                            class="nav-link text-active-primary me-6">{{ $t('leave.header.leaveType.leaveTypeInfo.title') }}
                        </Link>
                    </li>
                    <li class="nav-item" v-if="checkPermission('can-create-leave-allocation')">
                        <Link v-if="props?.id" :href="route('leave-types.createLeaveTypeAllocation', props?.id)" :class="{ active: isActive('CreateLeaveTypeAllocation') }"
                            class="nav-link text-active-primary me-6">{{ $t('leave.header.leaveType.leaveTypeAllocationInfo.title') }}
                        </Link>
                        <p v-else :class="{ active: isActive('CreateLeaveTypeAllocation') }"  class="nav-link text-active-primary me-6">{{ $t('leave.header.leaveType.leaveTypeAllocationInfo.title') }}</p>
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
import {Link, usePage} from '@inertiajs/vue3';
import { checkPermission } from "@/Core/helpers/Permission";
import { computed } from 'vue';

const props = defineProps({
    activeLink: String,
    id: String
});

const headerActiveLink = props.activeLink;

const {url} = usePage();

const isShowOrEditRoute = computed(() => {
    return props?.id && !url.includes(`leave-types/${props.id}/allocation`) || (url.includes(`leave-types/${props.id}`) || url.includes(`leave-types/${props.id}/edit`)) && url.includes(`leave-types/${props.id}/allocation-info`);
});

const isActive = (linkName: String) => {
    return headerActiveLink == linkName;
};
</script>
