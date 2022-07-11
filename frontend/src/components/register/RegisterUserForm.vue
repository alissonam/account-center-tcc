<template>
  <q-form @submit="login()">
    <div class="login-container column justify-between">
      <div class="justify-center text-center">
        <h1 class="login-title">
          Registre-se
        </h1>
      </div>
      <div class="row">
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
      <div class="text-left q-pa-lg q-gutter-lg">
        <q-btn
          style="background: #1976D2; color: white; text-align: center"
          type="submit"
          class="q-ma-sm q-px-xl q-py-sm"
          label="Registrar"
          icon-right="keyboard_arrow_right"
          push
        />
      </div>
    </div>
  </q-form>
</template>

<script setup>
import { ref } from "vue"

let account = ref({})

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
