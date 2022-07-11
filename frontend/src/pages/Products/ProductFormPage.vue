<template>
  <div class="full-width q-pa-lg items-start">
    <q-card>
      <q-card-section>
        <product-form />
      </q-card-section>
    </q-card>
  </div>
</template>

<script setup>
import ProductForm from '../../components/products/ProductsFormComponent.vue'
</script>

<style scoped>
  .full-width {
    width: 100%;
  }
</style>
