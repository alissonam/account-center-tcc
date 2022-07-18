<template>
  <div>
    <q-btn
      color="primary"
      icon="arrow_back"
      dense
      outline
      rounded
      :to="{ name: 'dashboard' }"
    >
      <q-tooltip :offset="[5, 5]">
        Voltar
      </q-tooltip>
    </q-btn>
  </div>
  <div class="row items-center justify-center">
    <div v-if="!existProduct">
      <h5> Não foi possivel carregar os planos do produto</h5>
    </div>
    <div class="q-pa-md" v-else>
      <h3 class="row items-center justify-center" style="color: #0a457d" > {{ productData.name}}</h3>
      <div style="display: flex">
        <q-card
        class="q-ma-md card"
        v-for="(plan, i) in planData"
        :key="i"
        >
          <q-item-section
            class="items-center justify-center"
          >
          <q-img
            :src="productLogo?.url || 'logo.jpeg'"
            style="width: 2cm; margin: 15px"
          />
          </q-item-section>
          <q-item>
            <q-item-section>
              <q-item-label align="center">
                <h5 style="color: #0a457d; margin: 12px; align: center;"> {{ plan.name }}</h5>
              </q-item-label>
            </q-item-section>
          </q-item>
          <div class="q-pa-md text-center" style="text-align: center; padding-bottom: 70px">
            <q-separator/>
            <div v-html="plan.description"/>
          </div>
          <q-card-actions align="center" class="q-pa-md q-gutter-sm">
            <q-btn
              class="button"
              padding="xs lg"
              :key="`btn_size_dense_rd_${size}`"
              type="submit"
              label="Assinar"
              push
              rounded
              size="lg"
            />
          </q-card-actions>
        </q-card>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { getPlans } from 'src/services/plan/plan-api'
import { Notify } from 'quasar'
import { getMedia } from "src/services/media/media-api"
import { formatResponseError } from "src/services/utils/error-formatter"
import { getProducts } from "src/services/product/product-api"
import { useRoute } from "vue-router"

let productCode = ref(null)
let planData = ref([])
const loading = ref(false)
let productLogo = ref(null)
let productData = ref({})
const route = useRoute()
let existProduct = ref(false)

onMounted(async () => {
  productCode.value = route.params.code
  await getProductFunction(productCode.value)
})

async function getProductFunction(productCode) {
  loading.value = true
  try {
    const result = await getProducts({ code: productCode })
    productData.value = result[0]
    if (productData.value) {
      getPlanFunction(productData.value.id)
      getLogoProductFunction(productData.value.id)
      existProduct.value = true
    } else {
      existProduct.value = false
    }
  } catch (e) {
    Notify.create({
      message: 'Falha ao buscar produto',
      type: 'negative'
    })
  }
  loading.value = false
}

async function getPlanFunction(productId) {
  loading.value = true
  try {
    const result = await getPlans({
      product_id: productId
    })
    planData.value = result
  } catch (e) {
    Notify.create({
      message: 'Falha ao buscar planos',
      type: 'negative'
    })
  }
  loading.value = false
}

async function getLogoProductFunction(productId) {
  try {
    const result = await getMedia({
      media_type: 'product_logo',
      subject_id: productId
    })
    productLogo.value = result[0]
  } catch (error) {
    Notify.create({
      message: formatResponseError(error) || 'Falha ao carregar logo',
      type: 'negative'
    })
  }

}
</script>

<style scoped>

.button {
  background: #1976D2;
  color: #faf8f8;
  width: 90%;
  transition-duration: 0.4s;
  position: absolute;
  bottom: 30px;
}

.button:hover {
  background: #e3780c;
}

.card {
  max-width: 800px;
  min-width: 300px;
  min-height: 600px;
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
  transition: 0.3s;
  border-radius: 5px;
  display: inline-block;
}

.card:hover {
  box-shadow: 0 16px 24px 0 rgba(0, 0, 0, 0.2);
}

</style>
