<template>
    <div class="modal fade" id="kt_modal_add_missing_punch" ref="addMissingPunchModalRef" tabindex="-1" aria-hidden="true">
        <!--begin::Modal dialog-->
        <div class="modal-dialog modal-dialog-centered mw-650px">
            <div class="modal-content">
                <!--begin::Modal header-->
                <div class="modal-header" id="kt_modal_add_missing_punch_header">
                    <!--begin::Modal title-->
                    <h2 class="fw-bold">{{ $t('attendanceLog.header.addMissingPunch') }}</h2>
                    <!--end::Modal title-->

                    <!--begin::Close-->
                    <div id="kt_modal_add_missing_punch_close" data-bs-dismiss="modal" class="btn btn-icon btn-sm btn-active-icon-primary">
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
                        <div class="scroll-y me-n7 pe-7" id="kt_modal_add_missing_punch_scroll" data-kt-scroll="true" data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto" data-kt-scroll-dependencies="#kt_modal_add_missing_punch_header" data-kt-scroll-wrappers="#kt_modal_add_missing_punch_scroll" data-kt-scroll-offset="300px">
                            <!--Punch Type-->
                            <div class="fv-row mb-7">
                                <label class="required fs-6 fw-semibold mb-2">{{ $t('attendanceLog.label.punchType') }}</label>
                                <Multiselect
                                    :placeholder = "$t('attendanceLog.label.punchType')"
                                    v-model="formData.punch_type"
                                    :searchable="true"
                                    :options="allPunchTypes"
                                    label="text"
                                    trackBy="text"
                                />
                                <ErrorMessage :errorMessage="formData.errors.punch_type" />
                            </div>

                            <!--Punch Time-->
                            <div class="fv-row mb-7">
                                <label class="required fs-6 fw-semibold mb-2">{{ $t('attendanceLog.label.punchTime') }}</label>
                                <Field type="datetime-local" class="form-control form-control-lg form-control-solid" name="punch_time" v-model="formData.punch_time"/>
                                <ErrorMessage :errorMessage="formData.errors.punch_time" />
                            </div>
                        </div>
                        <!--end::Scroll-->
                    </div>
                    <!--end::Modal body-->

                    <!--begin::Modal footer-->
                    <div class="modal-footer flex-center">
                        <!--Discard Button-->
                        <button type="reset" id="kt_modal_add_missing_punch_cancel" class="btn btn-light me-3">
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
import { ref } from "vue";
import { hideModal } from "@/Core/helpers/Modal";
import KTIcon from "@/Core/helpers/kt-icon/KTIcon.vue";
import { Field, Form as VForm } from "vee-validate";
import { useForm, usePage, router } from '@inertiajs/vue3';
import toastr from 'toastr';
import 'toastr/toastr.scss';
import Multiselect from "@vueform/multiselect";

const formRef = ref < null | HTMLFormElement > (null);
const addMissingPunchModalRef = ref < null | HTMLElement > (null);

const props = defineProps({
    employee: Object,
    punchTypes: Object
});

const formData = useForm({
    staff_id: props.employee?.staff_id,
    punch_type: '',
    punch_time: ''
});

// assign only 'out' punchType from props to allPunchTypes variable.
const allPunchTypes = ref<Array<any>>([]);
if (Array.isArray(props.punchTypes) && props.punchTypes.length > 0) {
    const outPunchType = props.punchTypes.filter(punchType => punchType.value === 'out');
    allPunchTypes.value = outPunchType.map(punchType => ({ value: punchType.value, text: punchType.text }));
}

const submit = async () => {
    formData.post(route('attendance-log.addmissingPunch', props.employee?.id ), {
        preserveScroll: true,
        onSuccess: () => {
            hideModal(addMissingPunchModalRef.value);
            router.visit(route('attendance-log.index', props.employee?.id), { replace: true });
            const flash = usePage().props.flash;
            let {success} = flash as any;

            if (flash && success) {
                toastr.success(success);
            }
        },
    });
}
</script>
