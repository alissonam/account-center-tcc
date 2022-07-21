<template>
  <q-page padding class="q-gutter-y-md">
    <div v-if="loggedUser.role !== 'member'" class="q-pa-md">
      <img
        src="~assets/logo.jpeg"
        style="max-width:90%;"
        class="absolute-center"
      >
    </div>
    <div v-else class="q-pa-lg items-start">
      <client-products />
    </div>
  </q-page>
<!--  <q-page padding class="q-gutter-y-md">-->
<!--    <div class="row q-gutter-x-md hidden">-->
<!--      <div class="col">-->
<!--        <widget-info-box/>-->
<!--      </div>-->
<!--      <div class="col">-->
<!--        <widget-info-box/>-->
<!--      </div>-->
<!--      <div class="col">-->
<!--        <widget-info-box/>-->
<!--      </div>-->
<!--      <div class="col">-->
<!--        <widget-info-box/>-->
<!--      </div>-->
<!--    </div>-->
<!--    <div class="row q-gutter-x-md">-->
<!--      <div class="col">-->
<!--        <ChartJsBar/>-->
<!--      </div>-->
<!--      <div class="col">-->
<!--        <ChartJsLine/>-->
<!--      </div>-->
<!--    </div>-->
<!--    <div class="row q-gutter-x-md">-->
<!--      <div class="col">-->
<!--        <ChartJsRadar/>-->
<!--      </div>-->
<!--      <div class="col-8">-->
<!--        <PolarArea/>-->
<!--      </div>-->
<!--    </div>-->
<!--  </q-page>-->
</template>

<script setup>
import { loggedUser } from 'boot/user'
import ClientProducts from "components/client-products/ClientProducts";

</script>
