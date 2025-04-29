<template>
    <div class="modal fade" id="kt_modal_add_employee_attendance_log" ref="addEmployeeAttendanceLogModalRef" tabindex="-1" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header" id="kt_modal_add_employee_attendance_log_header">
                    <!--begin::Modal title-->
                    <h2 class="fw-bold">{{ $t('loggedInEmployeeAttendance.header.attendanceLog.addAttendance') }}</h2>
                    <!--end::Modal title-->

                    <!--begin::Close-->
                    <div id="kt_modal_add_employee_attendance_log_close" data-bs-dismiss="modal" class="btn btn-icon btn-sm btn-active-icon-primary">
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
                        <div class="scroll-y me-n7 pe-7" id="kt_modal_add_employee_attendance_log_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_employee_attendance_log_header" data-kt-scroll-wrappers="#kt_modal_add_employee_attendance_log_scroll" data-kt-scroll-offset="300px">
                            <div class="fv-row mb-7">
                                <h5>
                                    {{ $t('loggedInEmployeeAttendance.label.currentDateTime') }}: <span class="text-success">{{ formattedCurrentDateTime }}</span>
                                </h5>
                            </div>

                            <!-- Punch Type -->
                            <ErrorMessage class="mb-7" :errorMessage="formData.errors.punch_type" />

                            <!--Work Location-->
                            <div class="fv-row mb-7" v-if="isFirstPunchOfDay">
                                <label class="required fs-6 fw-semibold mb-2">{{ $t('loggedInEmployeeAttendance.label.workLocation') }}</label>
                                <Multiselect
                                    :placeholder = "$t('loggedInEmployeeAttendance.placeholder.workLocation')"
                                    v-model="formData.work_from"
                                    :searchable="true"
                                    :options="allWorkLocations"
                                    label="text"
                                    trackBy="text"
                                />
                                <ErrorMessage :errorMessage="formData.errors.work_from" />
                            </div>

                            <!--Note-->
                            <div class="fv-row mb-7">
                                <label class="fs-6 fw-semibold mb-2">{{ $t('loggedInEmployeeAttendance.label.note') }}</label>
                                <Field type="text" class="form-control form-control-lg form-control-solid" name="note" v-model="formData.note" :placeholder = "$t('loggedInEmployeeAttendance.placeholder.note')"/>
                                <ErrorMessage :errorMessage="formData.errors.note" />
                            </div>
                        </div>
                        <!--end::Scroll-->
                    </div>
                    <!--end::Modal body-->

                    <!--begin::Modal footer-->
                    <div class="modal-footer flex-center">
                        <!--Discard Button-->
                        <button type="reset" id="kt_modal_add_employee_attendance_log_cancel" class="btn btn-light me-3">
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
import { ref, computed, onMounted, onUnmounted, watch } from "vue";
import { hideModal } from "@/Core/helpers/Modal";
import KTIcon from "@/Core/helpers/kt-icon/KTIcon.vue";
import { Field, Form as VForm } from "vee-validate";
import { useForm, usePage, router } from '@inertiajs/vue3';
import toastr from 'toastr';
import 'toastr/toastr.scss';
import Multiselect from "@vueform/multiselect";

const formRef = ref < null | HTMLFormElement > (null);
const addEmployeeAttendanceLogModalRef = ref < null | HTMLElement > (null);

const props = defineProps({
    punchTypes: Object,
    workLocations: Object,
    isFirstPunchOfDay: Boolean,
    modalType: String
});

const modalType = ref<string>(props?.modalType || '');

watch(() => props?.modalType, (newType) => {
    modalType.value = newType || '';
});

const formData = useForm({
    punch_type: '',
    punch_time: '',
    work_from: '',
    note: ''
});

// assign all the workLocations from props to allWorkLocations variable.
const allWorkLocations = ref<Array<any>>([]);
if (Array.isArray(props.workLocations) && props.workLocations.length > 0) {
    allWorkLocations.value = props.workLocations.map(workLocation => ({value: workLocation.value, text: workLocation.text}));
}

const currentTime = ref(new Date().toLocaleTimeString());
const formattedCurrentDateTime = computed(() => {
    const now = new Date();
    const date = now.toISOString().split('T')[0];
    const time = currentTime.value;
    return `${date} ${time}`;
});

const formatTime = (date: Date): string => {
    const hours = date.getHours();
    const minutes = String(date.getMinutes()).padStart(2, '0');
    const seconds = String(date.getSeconds()).padStart(2, '0');
    const period = hours >= 12 ? 'PM' : 'AM';
    const adjustedHours = ((hours + 11) % 12 + 1); 

    return `${String(adjustedHours).padStart(2, '0')}:${minutes}:${seconds} ${period}`;
};

const updateClock = () => {
    const now = new Date();
    currentTime.value = formatTime(now);
};

onMounted(() => {
    const intervalId = setInterval(updateClock, 1000);

    onUnmounted(() => {
        clearInterval(intervalId);
    });
});

const submit = async () => {
    formData.punch_type = modalType.value;
    formData.punch_time = new Date().toISOString().split('T')[0] + ' ' + new Date().toTimeString().split(' ')[0];

    formData.post(route('employee-attendance-logs.store'), {
        preserveScroll: true,
        onSuccess: () => {
            hideModal(addEmployeeAttendanceLogModalRef.value);
            router.visit(route('employee-attendance-logs.index'), { replace: true });
            const flash = usePage().props.flash;
            let {success} = flash as any;

            if (flash && success) {
                toastr.success(success);
            }
        },
    });
}
</script>
