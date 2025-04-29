<template>
    <div class="modal fade" id="kt_modal_add_leave" ref="addLeaveModalRef" tabindex="-1" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-750px">
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header" id="kt_modal_add_leave_header">
                    <!--begin::Modal title-->
                    <h2 class="fw-bold">Request Leave</h2>
                    <!--end::Modal title-->

                    <!--begin::Close-->
                    <div id="kt_modal_add_leave_close" data-bs-dismiss="modal" class="btn btn-icon btn-sm btn-active-icon-primary">
                        <KTIcon icon-name="cross" icon-class="fs-1" />
                    </div>
                    <!--end::Close-->
                </div>
                <!--end::Modal header-->

                <!--begin::Form-->
                <VForm @submit="submit()" :model="formData" ref="formRef">
                    <!--begin::Modal body-->
                    <div class="modal-body py-10 px-lg-17">
                        <!--begin::Scroll-->
                        <div class="scroll-y me-n7 pe-7" id="kt_modal_add_leave_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_leave_header" data-kt-scroll-wrappers="#kt_modal_add_leave_scroll" data-kt-scroll-offset="300px">

                            <!-- Leave Type -->
                            <div class="fv-row mb-7">
                                <label class="required fs-6 fw-semibold mb-2">Leave Type</label>
                                <Multiselect
                                    placeholder="Leave Type"
                                    v-model="formData.leave_type_id"
                                    :searchable="true"
                                    :options="allLeaveTypes"
                                    label="name"
                                    trackBy="name"
                                    @input="updateLeaveInformation"
                                />
                                <ErrorMessage :errorMessage="formData.errors.leave_type_id" />
                                <input type="hidden" name="date_from" v-model="formData.date_from">
                                <input type="hidden" name="date_to" v-model="formData.date_to">
                                <input type="hidden" name="number_of_days" v-model="formData.number_of_days">
                            </div>

                            <!-- Request date from -->
                            <div class="fv-row mb-7">
                                <label class="required fs-6 fw-semibold mb-2">From</label>
                                <Field type="date" class="form-control form-control-lg form-control-solid" name="formData.request_date_from" v-model="formData.request_date_from"/>
                                <ErrorMessage :errorMessage="formData.errors.request_date_from" />
                            </div>

                            <!-- Request date to -->
                            <div class="fv-row mb-7">
                                <label class="required fs-6 fw-semibold mb-2">To</label>
                                <Field type="date" class="form-control form-control-lg form-control-solid" name="formData.request_date_to" v-model="formData.request_date_to"/>
                                <ErrorMessage :errorMessage="formData.errors.request_date_to" />
                            </div>

                            <!-- Number of days -->
                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold mb-2">Duration</label>
                                <div>
                                    {{ totalDays }} Days
                                </div>

                            </div>

                            <!-- Description -->
                            <div class="fv-row mb-7">
                                <label class="required fs-6 fw-semibold mb-2">Description</label>
                                <Field type="text" placeholder="Description" class="form-control form-control-lg form-control-solid" name="name" v-model="formData.name"/>
                                <ErrorMessage :errorMessage="formData.errors.name" />
                            </div>

                            <!-- Note -->
                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold mb-2">Note</label>
                                <Field type="text" placeholder="Note" class="form-control form-control-lg form-control-solid" name="note" v-model="formData.note"/>
                                <ErrorMessage :errorMessage="formData.errors.note" />
                            </div>
                        </div>
                        <!--end::Scroll-->
                    </div>
                    <!--end::Modal body-->

                    <!--begin::Modal footer-->
                    <div class="modal-footer flex-center">
                        <!--Discard Button-->
                        <button type="reset" id="kt_modal_add_leave_cancel" class="btn btn-light me-3">
                            {{ $t('buttonValue.discard') }}
                        </button>

                        <!-- Submit Button -->
                        <SubmitButton/>
                    </div>
                    <!--end::Modal footer-->
                </VForm>
                <!--end::Form-->
            </div>
        </div>
    </div>
</template>

<script lang="ts" setup>
import ErrorMessage from "@/Components/Message/ErrorMessage.vue";
import SubmitButton from "@/Components/Button/SubmitButton.vue";
import { computed, ref } from "vue";
import { hideModal } from "@/Core/helpers/Modal";
import KTIcon from "@/Core/helpers/kt-icon/KTIcon.vue";
import { Field, Form as VForm } from "vee-validate";
import { useForm, usePage, router } from '@inertiajs/vue3';
import toastr from 'toastr';
import 'toastr/toastr.scss';
import Multiselect from '@vueform/multiselect';


const formRef = ref < null | HTMLFormElement > (null);
const addLeaveModalRef = ref < null | HTMLElement > (null);

const props = defineProps({
    employeeleaveAllocations: Object,
    leaveRemainingDays: Object
})

// assign all the leave types from props to allLeaveTypes variable.
const allLeaveTypes = ref<Array<any>>([]);
if (Array.isArray(props.employeeleaveAllocations) && props.employeeleaveAllocations.length > 0) {
    allLeaveTypes.value = props.employeeleaveAllocations.map((employeeleaveAllocation, index) => ({
        value: employeeleaveAllocation.leave_type.id,
        name: `${employeeleaveAllocation.leave_type.name} (${props?.leaveRemainingDays && props?.leaveRemainingDays[index]} remaining out of ${employeeleaveAllocation.number_of_days} days)`,
        date_from: employeeleaveAllocation.date_from,
        date_to: employeeleaveAllocation.date_to,
        number_of_days:employeeleaveAllocation.number_of_days
    }));
}

const totalDays = computed(() => {
    if(formData.request_date_from && formData.request_date_to) {
        const dateFrom = new Date(formData.request_date_from);
        const dateTo = new Date(formData.request_date_to);
        const timeDiff = dateTo.getTime() - dateFrom.getTime();
        const daysDiff = timeDiff / (1000 * 3600 * 24) + 1; // +1 to include the starting day
        return daysDiff >= 0 ? daysDiff : 0;
    }
    return 0;
});

const updateLeaveInformation = (selectedLeaveType: any) => {
    const leaveType = allLeaveTypes.value.find(leaveType => leaveType.value === selectedLeaveType);
    if (leaveType) {
        formData.date_from = leaveType.date_from;
        formData.date_to = leaveType.date_to;
        formData.number_of_days = leaveType.number_of_days;
    }
}

const formData = useForm({
    name: '',
    leave_type_id: '',
    date_from: '',
    date_to: '',
    number_of_days: '',
    request_date_from: '',
    request_date_to: '',
    note: ''
});

const submit = async () => {
    formData.post(route('leaves.storeEmployeeLeave'), {
        preserveScroll: true,
        onSuccess: () => {
            hideModal(addLeaveModalRef.value);
            router.visit(route('leaves.employeeLeaves'), {replace: true});
            const flash = usePage().props.flash;
            let {success} = flash as any;

            if (flash && success) {
                toastr.success(success);
            }
        },
    });
}
</script>
