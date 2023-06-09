<template>
  <div>
    <div>
      <q-btn
      color="primary"
      icon="arrow_back"
      dense
      outline
      rounded
      :to="{ name: 'dashboard' }"
    >
      <q-tooltip
        class="bg-blue text-body2"
        :offset="[5, 5]"
        transition-show="rotate"
        transition-hide="rotate"
      >
        Voltar
      </q-tooltip>
    </q-btn>
    </div>
    <div class="row items-center justify-center">
      <div class="q-pa-md">
        <h3 class="row items-center justify-center" style="color: #0a457d" > {{ productData?.name}}</h3>
        <div v-if="!planData?.length && !loading && !loadingSubscriptions">
          <q-card class="shadow-24">
            <q-card-section>
              <div class="row">
                <q-icon
                  class="col"
                  name="warning"
                  color="warning"
                  size="4rem"
                >
                  <h5> Nenhum plano encontrado!</h5>
                </q-icon>
              </div>
            </q-card-section>
          </q-card>
        </div>
        <div class="q-pa-md row justify-center" v-else>
            <q-card
              style="width: 250px;"
              class="q-mx-md card"
              :class="plan.preferential ? 'preferential q-mb-sm' : (windowWidth > 1475 ? 'q-my-xl' : 'q-my-md')"
              v-for="(plan, i) in planData"
              :key="i"
            >
              <q-item-section
                class="items-center"
              >
                <q-banner
                  v-if="plan.preferential"
                  class="bg-positive text-white text-center"
                  style="width: 100%"
                  dense
                  rounded
                >
                  Recomendado
                </q-banner>
                <q-img
                  class="q-ma-md"
                  :src="productLogo?.url || 'logo.jpeg'"
                  style="border-radius: 50%"
                  :style="plan.preferential ? 'width: 100px; height: 100px' : 'width: 80px; height: 80px;'"
                />
              </q-item-section>
              <q-item>
                <q-item-section>
                  <q-item-label align="center">
                    <h5 style="color: #0a457d; margin: 12px; align: center;"> {{ plan.name }}</h5>
                    <q-chip
                      class="text-h6"
                      outline
                      color="positive"
                      text-color="white"
                    >
                      {{  plan.payload.value.toLocaleString('pt-br',{style: 'currency', currency: 'BRL'}) }}
                    </q-chip>
                  </q-item-label>
                </q-item-section>
              </q-item>
              <div class="q-pa-xs text-left">
                <q-separator/>
                <div v-html="plan.description"/>
              </div>
              <q-card-actions align="center" class="q-pa-md q-gutter-sm">
                <q-btn
                  class="button"
                  :label="plan.subscription_status === 'active' ? 'Plano ativo': (plan.subscription_status  ? 'Aguardando Ativação': 'Assinar') "
                  padding="xs lg"
                  :key="`btn_size_dense_rd_${size}`"
                  :disable="['active', 'awaiting'].includes(plan.subscription_status)"
                  type="submit"
                  push
                  rounded
                  size="lg"
                  @click="openModalConfirmationSubscription.openModal(planData[i])"
                />
              </q-card-actions>
            </q-card>
            <client-plan-confirmation-subscription-component
              ref="openModalConfirmationSubscription"
              @submit="getSubscriptionsFunction(productData.id)"
            />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted, onUnmounted, ref } from 'vue'
import { getPlans } from 'src/services/plan/plan-api'
import { Notify, Loading } from 'quasar'
import { getMedia } from "src/services/media/media-api"
import { formatResponseError } from "src/services/utils/error-formatter"
import { getProducts } from "src/services/product/product-api"
import { useRoute } from "vue-router"
import ClientPlanConfirmationSubscriptionComponent from "components/client-plans/ClientPlanConfirmationSubscriptionComponent"
import { getSubscriptions } from "src/services/subscription/subscription-api";

let openModalConfirmationSubscription = ref(null)
let productCode = ref(null)
let planData = ref([])
let productLogo = ref(null)
let productData = ref({})
const route = useRoute()
let openModal = ref(false)
let loading = ref(false)
let loadingSubscriptions = ref(false)
let windowWidth = ref(window.innerWidth)

const onWidthChange = () => windowWidth.value = window.innerWidth
onMounted(() => window.addEventListener('resize', onWidthChange))
onUnmounted(() => window.removeEventListener('resize', onWidthChange))

onMounted(async () => {
  productCode.value = route.params.code
  await getProductFunction(productCode.value)
  await getSubscriptionsFunction(productData.value.id)
})

async function getProductFunction(productCode) {
  Loading.show()
  loading.value = true
  try {
    const result = await getProducts({ code: productCode })
    productData.value = result[0]

    if (productData.value) {
      await getPlanFunction(productData.value.id)
      getLogoProductFunction(productData.value.id)
    }
  } catch (error) {
    Notify.create({
      message: formatResponseError(error) || 'Falha ao buscar produto',
      type: 'negative'
    })
  }
  Loading.hide()
  loading.value = false
}

async function getPlanFunction(productId) {
  Loading.show()
  try {
    const result = await getPlans({
      product_id: productId
    })
    planData.value = result
  } catch (error) {
    Notify.create({
      message: formatResponseError(error) || 'Falha ao buscar planos',
      type: 'negative'
    })
  }
  Loading.hide()
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

async function getSubscriptionsFunction (productId) {
  loadingSubscriptions.value = true
  try {
    const subscriptionsData = await getSubscriptions({
      'product_id': productId,
      'status': ['active','awaiting']
    })

    for (const plan of planData.value) {
      const subscription = subscriptionsData.find(sub => {
        return ['active','awaiting'].includes(sub.status) && sub.plan_id == plan.id
      })

      plan.subscription_status = subscription?.status || null
    }
  } catch (error) {
    Notify.create({
      message: formatResponseError(error) || 'Falha ao buscar inscrições',
      type: 'negative'
    })
  }
  loadingSubscriptions.value = false
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
  box-shadow: 0 0 40px 0 rgba(227, 120, 12, 0.8);
}

.card {
  box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.4);
  transition: 0.3s;
  border-radius: 5px;
}

.card:hover {
  box-shadow: 0 16px 24px 0 rgba(0, 0, 0, 0.4);
}

.preferential.card:hover {
  box-shadow: 0 0 40px 0 rgba(0, 40, 255, 0.800);
}

.preferential.card, .preferential .q-img {
  box-shadow: 0 0 20px 0 rgba(0, 30, 255, 0.650);
  transition: 0.3s;
  border-radius: 5px;
}

.silver.card:hover {
  box-shadow: 0 0 40px 0 rgba(192,192,192, 0.800);
}

.silver.card, .silver .q-img {
  box-shadow: 0 0 20px 0 rgba(192,192,192, 0.650);
  transition: 0.3s;
  border-radius: 5px;
}

.gold.card:hover {
  box-shadow: 0 0 40px 0 rgb(255, 215, 0, 0.800);
}

.gold.card, .gold .q-img {
  box-shadow: 0 0 20px 0 rgb(255, 215, 0, 0.650);
  transition: 0.3s;
  border-radius: 5px;
}

</style>
