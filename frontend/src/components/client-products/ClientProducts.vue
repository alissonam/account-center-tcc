<template>
  <div>
    <h1 class="q-ma-lg">Produtos</h1>
    <q-card
      style="min-height: 250px;"
      :class="productsData[i].id === applyShadow ? 'shadow-24' : (width > 400 ? 'q-ma-xl' : 'q-ma-md')"
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
              :class="productsData[i].id === applyShadow ? 'shadow-24' : ''"
              :src="product?.logoUrl || defaultImage"
              no-native-menu
            />
          </div>
          <div
            class="col q-ma-auto"
            :class="product.id % 2 == 0 ? 'order-last' : 'order-first'"
            align="center"
          >
            <div class="q-pa-md text-h4">
              {{ product.name }}
            </div>
            <q-card-section
              class="q-pa-md text-center"
              v-html="product.description"
            />
            <div class="q-mt-xl">
              <q-btn
                :label="subscriptionsData[i]?.product_id == product.id ? 'Acessar' : 'Assinar'"
                :color="subscriptionsData[i]?.product_id == product.id ? 'primary' : 'positive'"
                :icon="subscriptionsData[i]?.product_id == product.id ? 'rocket_launch' : 'assignment'"
                :class="productsData[i].id === applyShadow ? 'shadow-24' : ''"
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
import { useRouter } from 'vue-router'
import { getProducts } from 'src/services/product/product-api'
import { getSubscriptions } from 'src/services/subscription/subscription-api'
import defaultImage from 'src/assets/images/default-image.png'
import { Notify } from 'quasar'

const router = useRouter()
let productsData = ref([])
let subscriptionsData = ref([])
let applyShadow = ref(null)
let loading = ref(false)
let loadingSubscriptions = ref(false)
let windowWidth = ref(window.innerWidth)

const onWidthChange = () => windowWidth.value = window.innerWidth
onMounted(() => window.addEventListener('resize', onWidthChange))
onUnmounted(() => window.removeEventListener('resize', onWidthChange))
const width = computed(() => windowWidth.value)

onMounted(async () => {
  await getProductsFunction()
  await getSubscriptionsFunction()
})

async function getProductsFunction () {
  loading.value = true
  try {
    productsData.value = await getProducts({
      with: ['logo'],
      status: 'active'
    })
  } catch (e) {
    Notify.create({
      message: 'Falha ao buscar produtos',
      type: 'negative'
    })
  }
  loading.value = false
}

async function getSubscriptionsFunction () {
  loadingSubscriptions.value = true
  try {
    subscriptionsData.value = await getSubscriptions({
      status: 'active'
    })
  } catch (e) {
    Notify.create({
      message: 'Falha ao buscar inscrições',
      type: 'negative'
    })
  }
  loadingSubscriptions.value = false
}

async function accessProduct (product) {
  window.open(product.action_url)
}

async function subscribeInProduct (product) {
  router.push({ name: 'client_plans', params: { code: product.code }})
}
</script>
