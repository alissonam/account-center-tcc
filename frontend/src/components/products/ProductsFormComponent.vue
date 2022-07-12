<template>
  <div>
    <q-btn
      color="primary"
      icon="arrow_back"
      dense
      outline
      rounded
      :to="{ name: 'products' }"
    >
      <q-tooltip :offset="[5, 5]">
        Voltar
      </q-tooltip>
    </q-btn>
    <h4 class="q-mt-lg" v-if="!route.params.id">Criar Produto</h4>
    <h4 class="q-mt-lg" v-else>Editar produto</h4>
    <q-form
      ref="productForm"
      @submit="submitProduct()"
    >
      <div>
        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-5 col-py-xs q-mr-md q-mb-lg">
            <q-input
              label="Nome"
              v-model="product.name"
              hide-bottom-space
              dense
              outlined
              :rules="[
                val => !!val || 'Preenchimento obrigatório',
                val => val.length <= 255 || 'Não pode ter mais de 255 caracteres'
              ]"
            />
          </div>
          <div class="col-xs-12 col-sm-12 col-md-4 col-py-xs q-mr-md q-mb-lg">
            <q-input
              label="Código"
              v-model="product.code"
              hide-bottom-space
              dense
              outlined
              :rules="[val => val.length <= 255 || 'Não pode ter mais de 255 caracteres']"
            />
          </div>
          <div class="col q-mb-lg">
            <q-select
              label="Status"
              map-options
              emit-value
              hide-bottom-space
              clearable
              v-model="product.status"
              :options="statusOptions"
              option-label="label"
              option-value="value"
              dense
              outlined
              :rules="[val => !!val || 'Preenchimento obrigatório']"
            />
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-12 col-py-xs q-mr-md q-mb-lg">
            <q-input
              type="textarea"
              label="Descrição"
              v-model="product.description"
              hide-bottom-space
              dense
              outlined
            />
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-6 col-py-xs q-mr-md q-mb-lg">
            <q-input
              label="Action URL"
              v-model="product.action_url"
              hide-bottom-space
              dense
              outlined
              :rules="[val => val.length <= 255 || 'Não pode ter mais de 255 caracteres']"
            >
              <q-btn
                round
                flat
                icon="exit_to_app"
                @click="openLink(product.action_url)"
              >
                <q-tooltip
                  class="bg-primary text-body2"
                  anchor="top middle"
                  self="bottom middle"
                  :offset="[10, 10]"
                  transition-show="rotate"
                  transition-hide="rotate"
                >
                  Acessar Link
                </q-tooltip>
              </q-btn>
            </q-input>
          </div>
          <div class="col q-mb-lg">
            <q-input
              label="Endereço do aplicativo"
              v-model="product.app_url"
              hide-bottom-space
              dense
              outlined
              :rules="[val => val.length <= 255 || 'Não pode ter mais de 255 caracteres']"
            >
              <q-btn
                round
                flat
                icon="exit_to_app"
                @click="openLink(product.app_url)"
              >
                <q-tooltip
                  class="bg-primary text-body2"
                  anchor="top middle"
                  self="bottom middle"
                  :offset="[10, 10]"
                  transition-show="rotate"
                  transition-hide="rotate"
                >
                  Acessar Link
                </q-tooltip>
              </q-btn>
            </q-input>
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-12 col-py-xs q-mr-md q-mb-lg">
            <q-input
              label="Token da API"
              v-model="product.api_token"
              hide-bottom-space
              dense
              outlined
              :rules="[val => val.length <= 255 || 'Não pode ter mais de 255 caracteres']"
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
import { createProduct, updateProduct, getProduct } from 'src/services/product/product-api'
import { Notify, Loading } from 'quasar'
import { formatResponseError } from "src/services/utils/error-formatter";

const router = useRouter()
const route = useRoute()
let saving = ref(false)

const statusOptions = [
  {
    label: 'Ativo',
    value: 'active'
  },
  {
    label: 'Inativo',
    value: 'inative'
  }
]

let product = ref({
  name: '',
  status: '',
  code: '',
  action_url: '',
  app_url: '',
  api_token: '',
  description: '',
})

const productForm = ref(null)

onMounted(async () => {
  if (route.params.id) {
    await getProductFunction()
  }
})

async function submitProduct() {
  saving.value = true
  try {
    const validated = productForm.value.validate()
    if (validated) {
      const productToSave = { ...product.value }
      !route.params.id ? await createProduct(productToSave) : await updateProduct(route.params.id, productToSave)

      Notify.create({
        message: !route.params.id ? 'Produto criado com sucesso!' : 'Produto editado com sucesso!',
        type: 'positive'
      })

      router.push({ name: 'products' })
    }
  } catch (error) {
    Notify.create({
      message: formatResponseError(error) || 'Falha ao salvar produto',
      type: 'negative'
    })
  }
  saving.value = false
}

async function getProductFunction() {
  Loading.show()
  try {
    const response = await getProduct(route.params.id)
    product.value = response
  } catch (e) {
    Notify.create({
      message: 'Falha ao buscar produto!',
      type: 'negative'
    })
  }
  Loading.hide()
}

function openLink(value)
{
  // Verificar se os links inseridos viram ou não com o protocolo antes... senão, manter como está.
  window.open("https://www." + value);
}
</script>
