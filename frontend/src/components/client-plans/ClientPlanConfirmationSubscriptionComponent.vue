<template>
  <q-dialog v-model="openModal">
    <q-card class="card">
      <q-item-section
        class="items-center justify-center"
      >
        <q-img
          class="q-ma-md"
          :src="productLogo?.url || 'logo.jpeg'"
          style="height: 80px; max-width: 80px; border-radius: 50%"
        />
      </q-item-section>
      <div class="row items-center justify-center">
        <h6
          class="login-title"
          style="margin: 15px"
        >
          {{ planData.name }}
        </h6>
        <p class="padding q-pa-sm" style="font-size: 20px; margin: 30px">
          Para confirmar sua compra digite sua senha
        </p>
      </div>
      <div class="padding q-pa-lg q-gutter-sm q-px-xl" style="margin-top: 15px">
        <p class="padding q-pa-sm" style="font-size: 20px">
          Confirmar senha
        </p>
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
      <div class="padding q-pa-lg">
        <q-btn
          type="submit"
          class="button q-ma-sm"
          color="primary"
          rounded
          push
          label="Assinar"
          @click="createSubscriptionFunction(planData.id, userPassword)"
        />
      </div>
    </q-card>
  </q-dialog>
</template>

<script setup>
import { ref } from 'vue'
import { useRoute } from 'vue-router'
import { Notify } from 'quasar'
import { getMedia } from "src/services/media/media-api";
import { formatResponseError } from "src/services/utils/error-formatter";
import { createSubscription } from "src/services/subscription/subscription-api";

let openModal = ref(false)
const route = useRoute()
let saving = ref(false)
let loading = ref(false)
let planData = ref({})
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
    planData.value = dataPlan
    openModal.value = true
  }
})

async function createSubscriptionFunction(planId, password){
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

</script>

<style>

.card{
  min-width: 400px;
  min-height:500px;
}

.login-title {
  font-size: 2rem;
  line-height: 1rem;
}
.button {
  background: #1976D2;
  color: #faf8f8;
  width: 90%;
  transition-duration: 0.4s;
  position: absolute;
  bottom: 30px;
}

</style>
