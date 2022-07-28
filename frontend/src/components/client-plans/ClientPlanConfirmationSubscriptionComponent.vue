<template>
  <q-dialog v-model="openModal">
    <q-card class="card">
      <q-item-section class="q-pa-lg">
        <div class="text-center">
          <q-img
            class="q-ma-md"
            :src="productLogo?.url || 'logo.jpeg'"
            style="height: 80px; max-width: 80px; border-radius: 50%"
          />
          <h4 class="q-ma-none">
            {{ planData.name }}
          </h4>
          <h6>
            Para confirmar sua compra digite sua senha
          </h6>
        </div>
        <div>
          <h6 class="q-my-md">Confirmar senha</h6>
          <q-input
            v-model="userPassword"
            :type="passwordHidden ? 'password' : 'text'"
            label="Senha"
            dense
            hide-bottom-space
            :rules="[ val => val && val.length > 0 || 'A senha é obrigatória']"
          >
            <template v-slot:append>
              <q-icon
                :name="passwordHidden ? 'visibility_off' : 'visibility'"
                class="cursor-pointer"
                @click="passwordHidden = !passwordHidden"
              />
            </template>
          </q-input>
        </div>
        <q-btn
          class="q-mt-xl"
          color="primary"
          rounded
          push
          label="Assinar"
          :loading="saving"
          :disable="saving"
          @click="createSubscriptionFunction(planData.id, userPassword)"
        />
      </q-item-section>
    </q-card>
  </q-dialog>
</template>

<script setup>
import { ref } from 'vue'
import { Notify, Dialog } from 'quasar'
import { getMedia } from "src/services/media/media-api";
import { formatResponseError } from "src/services/utils/error-formatter";
import { createSubscription, getSubscriptions } from "src/services/subscription/subscription-api";
import { loggedUser } from 'boot/user'

let openModal = ref(false)
let saving = ref(false)
let planData = ref({})
let clientSubscriptions = ref([])
let passwordHidden = ref(true)
let productLogo = ref(null)
let userPassword = ref(null)

const emit = defineEmits({
  submit: () => {
    return true
  }
})

defineExpose({
  openModal: async (dataPlan) => {
    getLogoProductFunction(dataPlan.product_id)
    getClientSubscriptions()
    planData.value = dataPlan
    openModal.value = true
  }
})

async function createSubscriptionFunction(planId, password) {
  saving.value = true
  try {
    await createSubscription({
      'plan_id': planId,
      'password': password
    })
    Notify.create({
      message: 'Inscrição criada com sucesso!',
      type: 'positive'
    })
    userPassword.value = null
    openModal.value = false
    emit('submit')
    showSubscriptionMessages()
  } catch (error) {
    Notify.create({
      message: formatResponseError(error) || 'Falha ao salvar inscrição',
      type: 'negative'
    })
  }
  saving.value = false
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

function showSubscriptionMessages () {
  let firstMessageAfterSubscription = 'Verifique sua caixa de entrada. Em breve você deve receber um e-mail com o link de pagamento.'
  let secondMessageAfterSubscription = 'O restante da cota não utilizada será movida para o próximo plano.'

  if (!planData.value.default || clientSubscriptions.value?.length) {
    Dialog.create({
      title: 'Atenção!',
      message: !planData.value.default && clientSubscriptions.value?.length ? firstMessageAfterSubscription + '<br/><br/>' + secondMessageAfterSubscription : firstMessageAfterSubscription,
      html: true
    }).onOk(async () => {

    })
  }
}

async function getClientSubscriptions () {
  try {
    const result = await getSubscriptions({
      user_id: loggedUser.id,
      status: 'active',
      default: 0
    })
    clientSubscriptions.value = result
  } catch (error) {
    Notify.create({
      message: formatResponseError(error) || 'Falha ao carregar inscrições do usuário',
      type: 'negative'
    })
  }
}

</script>

<style>

.card{
  min-width: 400px;
  min-height:500px;
}

</style>
