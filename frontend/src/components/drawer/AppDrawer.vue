<template>
  <q-drawer
    v-if="loggedUser.role === 'admin'"
    v-model="drawerOpen"
    show-if-above
    bordered
    :mini="false"
    :mini-width="75"
    :side="drawerSide"
  >
    <sidebar-menu />
  </q-drawer>
</template>


<script setup>
import { ref } from 'vue'
import sidebarMenu from './DrawerMenuSimplex'
import { loggedUser } from 'boot/user'

const drawerOpen = ref(false)
const drawerSide = ref('left')

defineExpose({
  toggleDrawer: () => {
    drawerOpen.value = !drawerOpen.value
  }
})
</script>
