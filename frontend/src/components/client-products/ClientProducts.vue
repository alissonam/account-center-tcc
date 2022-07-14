<template>
  <div>
    <h4 class="q-ma-lg">Produtos</h4>
    <q-card
      style="min-height: 250px;"
      :class="productsData[i].id === applyShadow ? shadow : (width > 400 ? 'q-ma-xl' : 'q-ma-md')"
      v-on:mouseover="applyShadow = product.id"
      v-on:mouseleave="applyShadow = null"
      v-for="(product, i) in productsData"
      :key="i"
    >
      <q-card-section>
        <div class="row q-gutter-md">
          <div
            class="col-xs-12 col-sm-12 col-md-3 q-my-auto"
            :class="product.id % 2 == 0 ? 'order-first' : (width > 1023 ? 'order-last' : 'order-first')"
            align="center"
          >
            <q-img
              style="height: 200px; max-width: 200px; border-radius: 50%"
              class="q-mt-md"
              :class="productsData[i].id === applyShadow ? shadow : ''"
              :src="product?.logoUrl"
              no-native-menu
            />
          </div>
          <div
            class="col q-ma-auto"
            :class="product.id % 2 == 0 ? 'order-last' : 'order-first'"
            align="center"
          >
            <div class="q-pa-md text-h5 text-center">
              {{ product.name }}
            </div>
            <div class="q-pa-md text-body1 text-center">
              {{ product.description }}
            </div>
            <div class="q-mt-xl">
              <q-btn
                :label="subscriptionsData[i]?.product_id == product.id ? 'Acessar' : 'Assinar'"
                :color="subscriptionsData[i]?.product_id == product.id ? 'primary' : 'positive'"
                :icon="subscriptionsData[i]?.product_id == product.id ? 'rocket_launch' : 'assignment'"
                :class="productsData[i].id === applyShadow ? shadow : ''"
                :disable="loading || loadingSubscriptions"
                size="18px"
                rounded
                outline
                @click="subscriptionsData[i]?.product_id == product.id ? accessProduct(product) : subscribeInProduct(product)"
              />
            </div>
          </div>
        </div>
      </q-card-section>
    </q-card>
  </div>
</template>

<script setup>
import { onMounted, onUnmounted, ref, computed } from 'vue'
import { getProducts } from 'src/services/product/product-api'
import { getSubscriptions } from 'src/services/subscription/subscription-api'
import { getLoggedUser } from 'src/services/user/user-api'
import { Notify } from 'quasar'

let productsData = ref([])
let subscriptionsData = ref([])
let loggedUser = ref(null)
let applyShadow = ref(null)
let loading = ref(false)
let loadingSubscriptions = ref(false)
let shadow = computed(() => "shadow-24")
let windowWidth = ref(window.innerWidth)

const onWidthChange = () => windowWidth.value = window.innerWidth
onMounted(() => window.addEventListener('resize', onWidthChange))
onUnmounted(() => window.removeEventListener('resize', onWidthChange))
const width = computed(() => windowWidth.value)

const mainPagination = ref({
  page: 1,
  rowsPerPage: 10,
  rowsNumber: 0,
})

onMounted(async () => {
  await getProductsFunction()
  await getLoggedUserFunction()
  await getSubscriptionsFunction()
})

async function getProductsFunction () {
  loading.value = true
  try {
    const params = {
      mainPagination: mainPagination.value,
      with: ['logo'],
      status: 'active'
    }
    productsData.value = await getProducts(params)
  } catch (e) {
    Notify.create({
      message: 'Falha ao buscar produtos',
      type: 'negative'
    })
  }
  loading.value = false
}

async function getLoggedUserFunction () {
  loading.value = true
  try {
    loggedUser.value = await getLoggedUser()
  } catch (e) {
    Notify.create({
      message: 'Falha ao buscar usuário logado',
      type: 'negative'
    })
  }
  loading.value = false
}

async function getSubscriptionsFunction () {
  loadingSubscriptions.value = true
  try {
    const params = {
      mainPagination: mainPagination.value,
      user_id: loggedUser.value.id
    }
    subscriptionsData.value = await getSubscriptions(params)
  } catch (e) {
    Notify.create({
      message: 'Falha ao buscar inscrições',
      type: 'negative'
    })
  }
  loadingSubscriptions.value = false
}

async function accessProduct (product) {
  product.action_url.includes("https://www.")
  ? window.open(product.action_url)
  : window.open("https://www." + product.action_url)
}

async function subscribeInProduct (product) {
  // Aguardar finalização da tela de planos para realizar essa ação
  console.log('assinar')
}
</script>
