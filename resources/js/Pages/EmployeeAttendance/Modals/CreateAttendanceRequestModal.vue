<template>
    <div class="modal fade" id="kt_modal_missing_attendance" ref="missingAttendanceModalRef" tabindex="-1" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header" id="kt_modal_missing_attendance_header">
                    <!--begin::Modal title-->
                    <h2 class="fw-bold">{{ $t('loggedInEmployeeAttendance.header.attendanceRequest.addMissingAttendance') }}</h2>
                    <!--end::Modal title-->

                    <!--begin::Close-->
                    <div id="kt_modal_missing_attendance_close" data-bs-dismiss="modal" class="btn btn-icon btn-sm btn-active-icon-primary">
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
                        <div class="scroll-y me-n7 pe-7" id="kt_modal_missing_attendance_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_missing_attendance_header" data-kt-scroll-wrappers="#kt_modal_missing_attendance_scroll" data-kt-scroll-offset="300px">
                            <!--Attendance Date-->
                            <div class="row mb-7">
                                <label class="required fs-6 fw-semibold mb-2">{{ $t('loggedInEmployeeAttendance.label.attendanceDate') }}</label>
                                <Field type="date" class="form-control form-control-lg form-control-solid" name="attendance_date" v-model="formData.attendance_date" :min="minDate" :max="maxDate"/>
                                <ErrorMessage :errorMessage="formData.errors.attendance_date" />
                            </div>

                            <!-- Check in and out time -->
                            <div class="row mb-7">
                                <!--Check in time-->
                                <div class="col-md-6">
                                    <label class="required fs-6 fw-semibold mb-2">{{ $t('loggedInEmployeeAttendance.label.checkInTime') }}</label>
                                    <Field type="time" class="form-control form-control-lg form-control-solid" name="check_in" v-model="formData.check_in"/>
                                    <ErrorMessage :errorMessage="formData.errors.check_in" />
                                </div>

                                <!--Check out time-->
                                <div class="col-md-6">
                                    <label class="required fs-6 fw-semibold mb-2">{{ $t('loggedInEmployeeAttendance.label.checkOutTime') }}</label>
                                    <Field type="time" class="form-control form-control-lg form-control-solid" name="check_out" v-model="formData.check_out"/>
                                    <ErrorMessage :errorMessage="formData.errors.check_out" />
                                </div>
                            </div>

                            <!--Work Location-->
                            <div class="fv-row mb-7">
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
                        </div>
                        <!--end::Scroll-->
                    </div>
                    <!--end::Modal body-->

                    <!--begin::Modal footer-->
                    <div class="modal-footer flex-center">
                        <!--Discard Button-->
                        <button type="reset" id="kt_modal_missing_attendance_cancel" class="btn btn-light me-3">
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
import { ref, computed } from "vue";
import { hideModal } from "@/Core/helpers/Modal";
import KTIcon from "@/Core/helpers/kt-icon/KTIcon.vue";
import { Field, Form as VForm } from "vee-validate";
import { useForm, usePage, router } from '@inertiajs/vue3';
import Multiselect from "@vueform/multiselect";
import toastr from 'toastr';
import 'toastr/toastr.scss';

const formRef = ref < null | HTMLFormElement > (null);
const missingAttendanceModalRef = ref < null | HTMLElement > (null);

const props = defineProps({
    employeeJoiningDate: String || null,
    workLocations: Object
});

const minDate = computed(() => {
    if (props.employeeJoiningDate) {
        return new Date(props.employeeJoiningDate).toISOString().split('T')[0];
    }
    return maxDate.value;
});

const maxDate = computed(() => {
    const now = new Date();
    const yesterday = new Date(now);
    yesterday.setDate(yesterday.getDate() - 1);

    return yesterday.toISOString().split('T')[0];
});

const formData = useForm({
    attendance_date: '',
    check_in: '',
    check_out: '',
    work_from: ''
});

// assign all the workLocations from props to allWorkLocations variable.
const allWorkLocations = ref<Array<any>>([]);
if (Array.isArray(props.workLocations) && props.workLocations.length > 0) {
    allWorkLocations.value = props.workLocations.map(workLocation => ({value: workLocation.value, text: workLocation.text}));
}

const submit = async () => {
    formData.post(route('employee-attendance-requests.store'), {
        preserveScroll: true,
        onSuccess: () => {
            hideModal(missingAttendanceModalRef.value);
            router.visit(route('employee-attendance-requests.index'), { replace: true });
            const flash = usePage().props.flash;
            let {success} = flash as any;

            if (flash && success) {
                toastr.success(success);
            }
        }
    });
}
</script>
