<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">

        <div class="card mb-5 mb-xl-10">
            <!--begin::Card header-->
            <div class="card-header border-0 cursor-pointer">
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">{{ props.publicHoliday?.id ? 'Edit' : 'Add' }}</h3>
                </div>
            </div>
            <!--end::Card header-->

            <div class="show">
                <VForm @submit="submit()" class="form">
                    <!--begin::Card body-->
                    <div class="card-body border-top p-9">
                        <!-- Name -->
                        <div class="d-flex flex-column mb-5 fv-row">
                            <label class="required fs-5 fw-semibold mb-2">Name</label>
                            <Field type="text" class="form-control form-control-lg form-control-solid" placeholder="Name" name="name" v-model="formData.name"/>
                            <ErrorMessage :errorMessage="formData.errors.name" />
                        </div>

                        <!-- Start date -->
                        <div class="d-flex flex-column mb-5 fv-row">
                            <label class="required fs-5 fw-semibold mb-2">Start date</label>
                            <Field type="date" class="form-control form-control-lg form-control-solid" name="date_from" v-model="formData.date_from"/>
                            <ErrorMessage :errorMessage="formData.errors.date_from" />
                        </div>

                        <!-- End date -->
                        <div class="d-flex flex-column mb-5 fv-row">
                            <label class="required fs-5 fw-semibold mb-2">End date</label>
                            <Field type="date" class="form-control form-control-lg form-control-solid" name="date_to" v-model="formData.date_to"/>
                            <ErrorMessage :errorMessage="formData.errors.date_to" />
                        </div>
                    </div>
                    <!--end::Card body-->

                    <!-- Submit Button-->
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <SubmitButton :id="props.publicHoliday?.id"/>
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

const props = defineProps({
    publicHoliday: Object,
    breadcrumbs: Array as() => Breadcrumb[],
    pageTitle: String
});

interface Breadcrumb {
    url: string;
    title: string;
}

const formData = useForm({
    name: props.publicHoliday?.name || '',
    date_from: props.publicHoliday?.date_from || '',
    date_to: props.publicHoliday?.date_to || ''
});

const submit = () => {
    if (props.publicHoliday?.id) {
        // for updating public holiday
        formData.put(route('public-holidays.update', props.publicHoliday?.id), {
            preserveScroll: true,
        });
    } else {
        // for adding new public holiday
        formData.post(route('public-holidays.store'), {
            preserveScroll: true,
        });
    }
};
</script>
