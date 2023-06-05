<template>
  <div>
    <div class="text-h2 q-ma-lg">
      <b>Nossos produtos:</b>
    </div>
    <q-card
      style="min-height: 250px;"
      class="card q-mx-md q-my-xl"
      v-for="(product, i) in productsData"
      :key="i"
    >
      <q-card-section>
        <div class="q-gutter-md">
          <div class="text-center md-hide lg-hide xl-hide">
            <q-img
              style="height: 200px; max-width: 200px; border-radius: 50%"
              class="q-mt-md"
              :src="product?.logoUrl || 'logo.jpeg'"
              no-native-menu
            />
          </div>
          <div class="row">
            <div
              class="col col-md-3 q-my-auto xs-hide sm-hide"
              :class="product.id % 2 == 0 ? 'order-first' : 'order-last'"
              align="center"
            >
              <q-img
                style="height: 200px; max-width: 200px; border-radius: 50%"
                class="q-mt-md"
                :src="product?.logoUrl || 'logo.jpeg'"
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
              <div class="q-mt-xl q-gutter-md">
                <q-btn
                  label="Planos"
                  color="positive"
                  icon="assignment"
                  size="18px"
                  rounded
                  outline
                  :to="{ name: 'client_plans', params: { code: product.code }}"
                />
                <q-btn
                  v-if="subscriptionsData[i]?.product_id == product.id"
                  label="Acessar"
                  color="primary"
                  icon="rocket_launch"
                  :disable="loadingSubscriptions"
                  size="18px"
                  rounded
                  outline
                  @click="accessProduct(product)"
                />
                <q-btn
                  v-if="subscriptionsData[i]?.product_id == product.id"
                  label="Sugestões"
                  color="yellow-9"
                  icon="o_lightbulb"
                  :disable="loadingSubscriptions"
                  size="18px"
                  rounded
                  outline
                  :to="{name:'suggestions', params: {product_id: product.id}}"
                />
                <q-btn
                  v-if="subscriptionsData[i]?.product_id == product.id"
                  label="Cancelar Assinatura"
                  color="negative"
                  icon="warning"
                  :disable="loadingSubscriptions"
                  size="18px"
                  rounded
                  outline
                  @click="cancelSubscription(product)"
                />
              </div>
            </div>
          </div>
        </div>
      </q-card-section>
    </q-card>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import { getProducts } from 'src/services/product/product-api'
import { getSubscriptions } from 'src/services/subscription/subscription-api'
import { Loading, Notify } from 'quasar'

const router = useRouter()
let productsData = ref([])
let subscriptionsData = ref([])
let loadingSubscriptions = ref(false)

onMounted(async () => {
  await getProductsFunction()
  await getSubscriptionsFunction()
})

async function getProductsFunction () {
  Loading.show()
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
  Loading.hide()
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
  window.open(product.app_url)
}
</script>

<style scoped>

.card:hover, .card:hover .q-img, .card:hover .q-btn {
  box-shadow: 0 16px 24px 0 rgba(0, 0, 0, 0.4)
}

</style>
