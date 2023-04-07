<template>
  <q-layout view="lHh Lpr lFf">
    <q-header :class="isActiveDarkMode ? 'bg-dark' : 'bg-white'">
      <q-toolbar>
        <div
          class="q-pa-sm absolute-left"
          v-if="loggedUser.role === 'member'">
          <img
            src="~assets/kolina-logo.png"
            style="height: 40px; "
          >
        </div>
        <div class="full-width q-pt-sm">
          <q-btn
            v-if="loggedUser.role === 'admin'"
            flat
            dense
            round
            icon="menu"
            aria-label="Menu"
            @click="appDrawer.toggleDrawer()"
            color="grey-6"
            size="20px"
          />
          <div class="absolute-right">
            <q-toggle
              v-model="isActiveDarkMode"
              @update:model-value="darkMode(isActiveDarkMode)"
              :icon="isActiveDarkMode ? 'dark_mode' : 'light_mode'"
              size="xl"
              :color="isActiveDarkMode ? 'dark' : 'white'"
              keep-color
            >
              <q-tooltip
                :class="isActiveDarkMode ? 'bg-white text-dark' : 'bg-dark'"
                :offset="[5, 5]"
                transition-show="rotate"
                transition-hide="rotate"
              >
                {{ isActiveDarkMode ? 'Dark Mode' : 'Light Mode' }}
              </q-tooltip>
            </q-toggle>
            <b
              class="text-h6 q-mr-sm"
              style="color: #1061a6"
            >
              {{ loggedUser.name }}
            </b>
            <q-btn-dropdown
              flat
              round
              icon="account_circle"
              color="primary"
              size="22px"
            >
              <q-list>
                <q-item
                  clickable
                  v-close-popup
                  :to="{ name: 'users_update', params: { id: loggedUser.id }}"
                >
                  <q-item-section>
                    <q-item-label>Editar perfil</q-item-label>
                  </q-item-section>
                </q-item>
                <q-item
                  clickable
                  v-close-popup
                  @click="logoutUser"
                >
                  <q-item-section>
                    <q-item-label>Sair</q-item-label>
                  </q-item-section>
                </q-item>
              </q-list>
            </q-btn-dropdown>
          </div>
        </div>
      </q-toolbar>
    </q-header>

    <app-drawer ref="appDrawer"/>

    <q-page-container>
      <router-view/>
    </q-page-container>
  </q-layout>
</template>

<script setup>
import AppDrawer from 'src/components/drawer/AppDrawer.vue'
import { postLogoutUser } from "src/services/login/login-api"
import { resetLoggedUser, resetUserInLocalStorage, loggedUser } from "boot/user"
import { useRouter } from 'vue-router'
import { useQuasar } from 'quasar'
import { Notify } from "quasar"
import { ref } from "vue";

let isActiveDarkMode = ref(false);
const router = useRouter()
const appDrawer = ref(null)
const $q = useQuasar()

const logoutUser = async () => {
  try {
    await postLogoutUser()

    resetLoggedUser()
    resetUserInLocalStorage()
    router.push({ name: 'login' })
  } catch {
    Notify.create({
      message: 'Falha ao deslogar!',
      type: 'negative'
    })
  }
}

const darkMode = (val) => {
  $q.dark.set(val);
}
</script>

<style scoped>
.pre-text-info {
  font-size: .75rem;
  font-weight: bold;
}
</style>
