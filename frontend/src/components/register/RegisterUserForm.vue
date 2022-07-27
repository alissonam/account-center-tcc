<template>
  <q-form
    ref="accountForm"
    @submit="submitRegister()"
  >
    <div class="login-container column justify-between">
      <div>
        <q-btn
          color="primary"
          icon="arrow_back"
          dense
          outline
          rounded
          :to="{ name: 'login' }"
        >
          <q-tooltip :offset="[5, 5]">
            Voltar
          </q-tooltip>
        </q-btn>
      </div>
      <div class="justify-center text-center">
        <h1 class="login-title">
          Registre-se
        </h1>
      </div>
      <div class="row" v-if="!userCreate">
        <div class="col-xs-12 col-sm-12 col-md-12 col-py-xs q-mr-md q-mb-lg">
          <q-input
            label="Nome"
            v-model="account.name"
            hide-bottom-space
            dense
            rounded
            outlined
            :rules="[val => !!val || 'Preenchimento obrigatório']"
          />
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-py-xs q-mr-md q-mb-lg">
          <q-input
            label="E-mail"
            v-model="account.email"
            hide-bottom-space
            dense
            rounded
            outlined
            :rules="[val => !!val || 'Preenchimento obrigatório']"
          />
        </div>
        <div class="col-xs-12 col-sm-12 col-md-12 col-py-xs q-mr-md q-mb-lg">
          <q-input
            label="Telefone"
            hide-bottom-space
            v-model="account.phone"
            :mask="(account.phone || '').length < 15
              ? '(##) ####-#####' : '(##) #####-####'"
            dense
            rounded
            outlined
            :rules="[val => !!val || 'Preenchimento obrigatório']"
          />
        </div>
      </div>
      <div v-else>
        <h5
          class="text-center text-bold"
          style="color: #3e7b27">
          Cadastro criado com sucesso!!
          <br>Por favor verifique seu e-mail para definir senha
        </h5>
      </div>
      <div class="text-left q-pa-lg q-gutter-lg">
        <q-btn
          v-if="!userCreate"
          type="submit"
          color="primary"
          class="q-ma-sm q-px-xl q-py-sm"
          label="Registrar"
          icon-right="keyboard_arrow_right"
          rounded
          outline
          push
          :disable="saving"
          :loading="saving"
        />
      </div>
    </div>
  </q-form>
</template>

<script setup>

import { onMounted, ref } from "vue"
import { Notify } from 'quasar'
import { useRouter, useRoute } from "vue-router"
import { createRegister } from "src/services/user/user-api";
import { formatResponseError } from "src/services/utils/error-formatter";

let account = ref({})
let accountForm = ref(null)
const router = useRouter()
const route = useRoute()
let saving = ref(false)
let userCreate = ref(false)

async function submitRegister() {
  saving.value = true
  try {
    const validated = accountForm.value.validate()
    if (validated) {
      account.value.product_code = route.query.code || null
      await createRegister(account.value)
      userCreate.value = true
      account.value = {}
    }
  } catch (error) {
    Notify.create({
      message: formatResponseError(error) || 'Falha ao realizar o cadastro',
      type: 'negative'
    })
  }
  saving.value = false
}

</script>

<style scoped>
.login-container {
  padding: 20px;
  border-radius: 10px;
  background: white;
  min-height: 50vh;
  display: flex;
  -webkit-box-shadow: 0px 4px 15px 0px rgba(0, 0, 0, 0.75);
  -moz-box-shadow: 0px 4px 15px 0px rgba(0, 0, 0, 0.75);
  box-shadow: 0px 4px 15px 0px rgba(0, 0, 0, 0.75);
}

.login-title {
  font-size: 2rem;
  line-height: 1rem;
}

</style>
