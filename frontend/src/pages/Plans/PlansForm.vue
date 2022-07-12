<template>
  <div class="full-width q-pa-lg items-start">
    <q-card>
      <q-card-section>
        <plans-form />
      </q-card-section>
    </q-card>
  </div>
</template>

<script setup>
import PlansForm from '../../components/plans/PlansFormComponent.vue'
</script>

<style scoped>
  .full-width {
    width: 100%;
  }
</style>
