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
            <div class="row">
              <div class="col-2 q-mb-sm">
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
              </div>
              <div class="col-6 q-mt-md q-ml-lg">
                <q-btn-dropdown
                  flat
                  round
                  :label="loggedUser.name"
                  color="primary"
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
              <q-img
                class="q-mt-md q-mr-md"
                style="height: 43px; max-width: 43px; border-radius: 50%"
                :src="userImage || 'logo.jpeg'"
                no-native-menu
              />
            </div>
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
import { useRouter, useRoute } from 'vue-router'
import { useQuasar } from 'quasar'
import { Notify } from "quasar"
import { onMounted, ref } from "vue";
import { formatResponseError } from "src/services/utils/error-formatter";
import { getMedia } from "src/services/media/media-api"

let isActiveDarkMode = ref(false);
const router = useRouter()
const route = useRoute()
const appDrawer = ref(null)
const $q = useQuasar()
let userImage = ref(null)

onMounted(async () => {
  const userId = loggedUser.role == 'member' ? loggedUser.id : route.params.id
  if (userId) {
    await getLogoFunction(userId)
  }
})

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

async function getLogoFunction(userId){
  try {
    let result = await getMedia({
      media_type: 'user_profile',
      subject_id: userId
    })
    userImage.value = result[0].filename
  } catch (error) {
    console.log(error)
    Notify.create({
      message: formatResponseError(error) || 'Falha ao carregar logo',
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
