<template>
    <Head :title="`${props?.pageTitle}`" />
    <div class="login-confirmation-page p-10 d-flex justify-content-center align-items-center" style="background-image: url('/media/auth/bg10.jpeg');">
        <!--begin::Wrapper-->
        <div class="text-center my-10">
            <!--begin:Logo-->
            <div class="mb-11">
                <Link href="/" rel="noopener" target="_blank">
                    <img alt="Logo" :src="getAssetPath('media/logos/hrbee-logo.svg')" class="h-60px h-lg-75px">
                </Link>
            </div>
            <!--end:Logo-->

            <!--begin::Title-->
            <div class="fs-2x fw-bold mb-5">
                <h2>
                    Recover Account
                </h2>
                <!--end::Title-->
            </div>

            <!--begin::Description-->
            <p class="text-gray-500 fs-4 fw-semibold mb-5">
                We have sent an email to the address registered with this account containing further instructions to reset your password.
                <br>
                Need help with your account? Click <a href="https://hrbee.mkrdev.xyz/contact-us">here</a> to check out our help center or login <Link :href="route('login')">here</Link>.
            </p>
            <!--end::Description-->
        </div>
        <!--end::Wrapper-->
</div>
<!--end::Card-->
</template>

<script lang="ts" setup>
import { Head, Link } from '@inertiajs/vue3';
import {getAssetPath} from "@/Core/helpers/Assets";

const props = defineProps({
    pageTitle: String
});
</script>

<style>
#app, .login-confirmation-page {
    height: 100%;
}
</style>
