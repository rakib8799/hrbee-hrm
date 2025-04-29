<template>
  <!--begin::Footer-->
  <div v-if="footerDisplay" id="kt_app_footer" class="app-footer">
    <!--begin::Footer container-->
    <div
      class="app-container d-flex flex-column flex-md-row flex-center flex-md-stack py-3"
      :class="{
        'container-fluid': footerWidthFluid,
        'container-xxl': !footerWidthFluid,
      }"
    >
      <!--begin::Copyright-->
      <div class="text-gray-900 order-2 order-md-1 mx-auto">
        <span class="text-muted fw-semibold">2024©</span>
        <a
          href="https://hrbee.mkrdev.xyz"
          target="_blank"
          class="text-gray-800 text-hover-primary"
          >HR BEE</a
        >
      </div>
      <!--end::Copyright-->
    </div>
    <!--end::Footer container-->
  </div>
  <!--end::Footer-->
</template>

<script setup lang="ts">
import { ref } from "vue";
// import { footerDisplay, footerWidthFluid } from "@/layouts/default-layout/config/helper";
const footerWidthFluid = ref(true);
const footerDisplay = ref(true);
</script>
