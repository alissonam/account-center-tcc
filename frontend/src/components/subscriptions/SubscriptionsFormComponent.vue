<template>
  <div>
    <q-btn
      color="primary"
      icon="arrow_back"
      dense
      outline
      rounded
      :to="{ name: 'subscriptions' }"
    >
      <q-tooltip :offset="[5, 5]">
        Voltar
      </q-tooltip>
    </q-btn>
    <h4 class="q-mt-lg" v-if="!route.params.id">Criar inscrição</h4>
    <h4 class="q-mt-lg" v-else>Editar inscrição</h4>
    <q-form
      ref="subscriptionForm"
      @submit="submitSubscription()"
    >
      <div>
        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-3 col-py-xs q-mr-md q-mb-lg">
            <q-select
              label="Usuário"
              map-options
              emit-value
              hide-bottom-space
              clearable
              :readonly="!!route.params.id"
              v-model="subscription.user_id"
              :options="userOptions"
              :option-label="opt => opt.name || subscription.user?.name || 'N/I'"
              option-value="id"
              dense
              outlined
              use-input
              fill-input
              hide-selected
              placeholder="Digite para pesquisar"
              input-debounce="300"
              :rules="[val => !!val || 'Preenchimento obrigatório']"
              @filter="filterUsers"
            />
          </div>
          <div class="col-xs-12 col-sm-12 col-md-3 col-py-xs q-mr-md q-mb-lg">
            <q-select
              label="Produto"
              map-options
              emit-value
              hide-bottom-space
              clearable
              :readonly="!!route.params.id"
              v-model="subscription.product_id"
              :options="productOptions"
              :option-label="opt => opt.name || subscription.product?.name || 'N/I'"
              option-value="id"
              dense
              outlined
              use-input
              fill-input
              hide-selected
              placeholder="Digite para pesquisar"
              input-debounce="300"
              :rules="[val => !!val || 'Preenchimento obrigatório']"
              @filter="filterProducts"
            />
          </div>
          <div class="col-xs-12 col-sm-12 col-md-3 col-py-xs q-mr-md q-mb-lg">
            <q-select
              label="Plano"
              map-options
              emit-value
              hide-bottom-space
              clearable
              :readonly="!subscription.product_id"
              v-model="subscription.plan_id"
              :options="planOptions"
              :option-label="opt => opt.name || subscription.plan?.name || 'N/I'"
              option-value="id"
              dense
              outlined
              use-input
              fill-input
              hide-selected
              placeholder="Digite para pesquisar"
              input-debounce="300"
              :rules="[val => !!val || 'Preenchimento obrigatório']"
              @filter="filterPlan"
            />
          </div>
          <div class="col q-mb-lg">
            <q-input
              label="Vindi ID"
              v-model="subscription.vindi_id"
              hide-bottom-space
              dense
              outlined
            />
          </div>
        </div>
      </div>
      <div align="right">
        <q-btn
          outline
          label="Salvar"
          icon="save"
          type="submit"
          color="primary"
          :disable="saving"
          :loading="saving"
        />
      </div>
    </q-form>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { Notify, Loading } from 'quasar'
import { formatResponseError } from "src/services/utils/error-formatter";
import { createSubscription, getSubscription, updateSubscription } from "src/services/subscription/subscription-api";
import { getUsers } from "src/services/user/user-api";
import { getProducts } from "src/services/product/product-api";
import { getPlans } from "src/services/plan/plan-api";

const router = useRouter()
const route = useRoute()
let saving = ref(false)
let productOptions = ref([])
let userOptions = ref([])
let planOptions = ref([])

let subscription = ref({})

const subscriptionForm = ref(null)

onMounted(async () => {
  if (route.params.id) {
    await getSubscriptionFunction(route.params.id)
  }
})

async function submitSubscription() {
  saving.value = true
  try {
    const validated = subscriptionForm.value.validate()
    if (validated) {
      const subscriptionToSave = { ...subscription.value }
      !route.params.id ? await createSubscription(subscriptionToSave) : await updateSubscription(route.params.id, subscriptionToSave)

      Notify.create({
        message: !route.params.id ? 'Inscrição criada com sucesso!' : 'Inscrição editada com sucesso!',
        type: 'positive'
      })

      router.push({ name: 'subscriptions' })
    }
  } catch (error) {
    Notify.create({
      message: formatResponseError(error) || 'Falha ao salvar inscrição',
      type: 'negative'
    })
  }
  saving.value = false
}

async function getSubscriptionFunction(subscriptionId) {
  Loading.show()
  try {
    const params = {
      with: [ 'plan', 'product', 'user' ]
    }
    const response = await getSubscription(subscriptionId, params)
    subscription.value = response
  } catch (e) {
    Notify.create({
      message: 'Falha ao buscar inscrição!',
      type: 'negative'
    })
  }
  Loading.hide()
}

async function filterUsers(val, update, abort) {
  try {
    userOptions.value = await getUsers({
      role: 'member',
      name: val,
      rowsPerPage: 25,
    })
    update()
  } catch (e) {
    Notify.create({
      message: 'Falha ao buscar usuário',
      type: 'negative'
    })
    abort()
  }
}

async function filterProducts(val, update, abort) {
  try {
    productOptions.value = await getProducts({
      name: val,
      rowsPerPage: 25,
    })
    update()
  } catch (e) {
    Notify.create({
      message: 'Falha ao buscar produto',
      type: 'negative'
    })
    abort()
  }
}

async function filterPlan(val, update, abort) {
  try {
    planOptions.value = await getPlans({
      product_id: subscription.value.product_id,
      name: val,
      rowsPerPage: 25,
    })
    update()
  } catch (e) {
    Notify.create({
      message: 'Falha ao buscar plano',
      type: 'negative'
    })
    abort()
  }
}

</script>
