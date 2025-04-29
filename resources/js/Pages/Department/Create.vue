<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">

        <div class="card mb-5 mb-xl-10">
            <!--begin::Card header-->
            <div class="card-header border-0 cursor-pointer">
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">{{ props.department?.id ? $t('department.header.edit') : $t('department.header.add') }}</h3>
                </div>
            </div>
            <!--end::Card header-->

            <div class="show">
                <VForm @submit="submit()" class="form">
                    <!--begin::Card body-->
                    <div class="card-body border-top p-9">
                        <!-- Name -->
                        <div class="d-flex flex-column mb-5 fv-row">
                            <label class="required fs-5 fw-semibold mb-2">{{ $t('department.label.name') }}</label>
                            <Field type="text" class="form-control form-control-lg form-control-solid" :placeholder="$t('department.placeholder.name')" name="name" v-model="formData.name"/>
                            <ErrorMessage :errorMessage="formData.errors.name" />
                        </div>

                        <!-- Parent Department -->
                        <div lass="mb-5 fv-row">
                            <label class="fs-5 fw-semibold mt-5 mb-2">{{ $t('department.label.parentDepartment') }}</label>
                            <Multiselect
                                :placeholder = "$t('department.placeholder.parentDepartment')"
                                v-model="formData.parent_id"
                                :searchable="true"
                                :options="allParentDepartments"
                                label="name"
                                trackBy="name"
                            />
                        </div>
                    </div>
                    <!--end::Card body-->

                    <!-- Submit Button-->
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <SubmitButton :id="props.department?.id"/>
                    </div>
                </VForm>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script setup lang="ts">
import { useForm } from '@inertiajs/vue3';
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Field, Form as VForm } from "vee-validate";
import ErrorMessage from "@/Components/Message/ErrorMessage.vue";
import SubmitButton from "@/Components/Button/SubmitButton.vue";
import Multiselect from '@vueform/multiselect';
import { ref } from 'vue';

const props = defineProps({
    parentDepartments: Object,
    department: Object,
    breadcrumbs: Array as() => Breadcrumb[],
    pageTitle: String,
});

interface Breadcrumb {
    url: string;
    title: string;
}

// assign all the parent departments from props to allParentDepartments variable.
const allParentDepartments = ref<Array<any>>([]);
if (Array.isArray(props.parentDepartments) && props.parentDepartments.length > 0) {
    allParentDepartments.value = props.parentDepartments.map(department => ({value: department.id, name: department.name}));
}

const formData = useForm({
    name: props.department?.name || '',
    parent_id: props.department?.parent_id || ''
});

const submit = () => {
    if (props.department?.id) {
        // for updating department
        formData.put(route('departments.update', props.department?.id), {
            preserveScroll: true,
        });
    } else {
        // for adding new department
        formData.post(route('departments.store'), {
            preserveScroll: true,
        });
    }
};
</script>
