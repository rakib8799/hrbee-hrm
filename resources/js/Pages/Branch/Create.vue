<template>
    <AuthenticatedLayout :breadcrumbs="props?.breadcrumbs" :pageTitle="props?.pageTitle">

        <div class="card mb-5 mb-xl-10">
            <!--begin::Card header-->
            <div class="card-header border-0 cursor-pointer">
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">{{ props.branch?.id ? $t('branch.header.edit') : $t('branch.header.add') }}</h3>
                </div>
            </div>
            <!--end::Card header-->

            <div class="show">
                <VForm @submit="submit()" class="form">
                    <!--begin::Card body-->
                    <div class="card-body border-top p-9">
                        <!-- Name -->
                        <div class="d-flex flex-column mb-5 fv-row">
                            <label class="required fs-5 fw-semibold mb-2">{{ $t('branch.label.name') }}</label>
                            <Field type="text" class="form-control form-control-lg form-control-solid" :placeholder="$t('branch.placeholder.name')" name="name" v-model="formData.name"/>
                            <ErrorMessage :errorMessage="formData.errors.name" />
                        </div>

                        <!-- Branch Manager -->
                        <div lass="mb-5 fv-row">
                            <label class="fs-5 fw-semibold mb-2">{{ $t('branch.label.branchManager') }}</label>
                            <Multiselect
                                :placeholder="$t('branch.placeholder.branchManager')"
                                v-model="formData.manager_id"
                                :searchable="true"
                                :options="allEmployees"
                                label="name"
                                trackBy="name"
                            />
                            <ErrorMessage :errorMessage="formData.errors.manager_id" />
                        </div>

                        <!-- Branch Code and Phone Number-->
                        <div class="row mt-2 mb-5 g-4">
                            <!-- Branch Code -->
                            <div class="col-md-6 fv-row">
                                <label class="fs-5 fw-semibold mb-2">{{ $t('branch.label.branchCode') }}</label>
                                <Field type="text" class="form-control form-control-lg form-control-solid" :placeholder="$t('branch.placeholder.branchCode')" name="branch_code" v-model="formData.branch_code"/>
                                <ErrorMessage :errorMessage="formData.errors.branch_code" />
                            </div>

                            <!-- Phone Number -->
                            <div class="col-md-6 fv-row">
                                <label class="fs-5 fw-semibold mb-2">{{ $t('branch.label.phoneNumber') }}</label>
                                <Field type="text" class="form-control form-control-lg form-control-solid" :placeholder="$t('branch.placeholder.phoneNumber')" name="phone_number" v-model="formData.phone_number"/>
                                <ErrorMessage :errorMessage="formData.errors.phone_number" />
                            </div>
                        </div>

                        <!-- Address -->
                        <Address
                            v-model:address_line_one="address_line_one"
                            v-model:address_line_two="address_line_two"
                            v-model:floor="floor"
                            v-model:city="city"
                            v-model:state="state"
                            v-model:zip_code="zip_code"
                            :errors="formData.errors"
                        ></Address>
                    </div>
                    <!--end::Card body-->

                    <!-- Submit Button-->
                    <div class="card-footer d-flex justify-content-end py-6 px-9">
                        <SubmitButton :id="props.branch?.id" />
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
import Address from "@/Components/Address.vue";
import { ref, watch } from 'vue';
import Multiselect from '@vueform/multiselect';

const props = defineProps({
    branch: Object,
    employees: Object,
    breadcrumbs: Array as() => Breadcrumb[],
    pageTitle: String,
});

interface Breadcrumb {
    url: string;
    title: string;
}

// Fields of Address.vue
const address_line_one = ref(props.branch?.address_line_one || '');
const address_line_two = ref(props.branch?.address_line_two || '');
const floor = ref(props.branch?.floor || '');
const city = ref(props.branch?.city || '');
const state = ref(props.branch?.state || '');
const zip_code = ref(props.branch?.zip_code || '');

const formData = useForm({
    name: props.branch?.name || '',
    manager_id: props.branch?.manager_id || '',
    branch_code: props.branch?.branch_code || '',
    phone_number: props.branch?.phone_number || '',
    address_line_one: address_line_one.value,
    address_line_two: address_line_two.value,
    floor: floor.value,
    city: city.value,
    state: state.value,
    zip_code: zip_code.value,
});

// watcher to update values received from Address.vue
watch([address_line_one, address_line_two, floor, city, state, zip_code], ([address_line_one, address_line_two, floor, city, state, zip_code]) => {
    formData.address_line_one = address_line_one;
    formData.address_line_two = address_line_two;
    formData.floor = floor;
    formData.city = city;
    formData.state = state;
    formData.zip_code = zip_code;
});

// get full name using first_name and last_name
const fullName = (first_name: string, last_name: string) => {
    return `${first_name} ${last_name}`;
}

// assign all the employees from props to allEmployees variable.
const allEmployees = ref<Array<any>>([]);
if (Array.isArray(props.employees) && props.employees.length > 0) {
    allEmployees.value = props.employees.map(employee => ({value: employee.id, name: fullName(employee.first_name, employee.last_name)}));
}

const submit = () => {
    if (props.branch?.id) {
        // for updating branch
        formData.put(route('branches.update', props.branch?.id), {
            preserveScroll: true,
        });
    } else {
        // for adding new branch
        formData.post(route('branches.store'), {
            preserveScroll: true,
        });
    }
};
</script>
