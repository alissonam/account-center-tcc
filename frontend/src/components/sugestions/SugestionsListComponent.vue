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
        <q-tooltip :offset="[5, 5]">
          Voltar
        </q-tooltip>
      </q-btn>
    </div>
      <h3>Sugestões</h3>
    <div class="text-center">
      <q-img
        style="height: 50; max-width: 100px; border-radius: 50%"
        class="q-mt-md"
        :src="productData?.logo || 'logo.jpeg'"
        no-native-menu
      />
            <h5
        style="color: #0a457d; margin: 12px; align: center;"
      >
        {{ productData?.name }}
      </h5>
    </div>
    <div class="row">
      <div class="col-xs-12 col-sm-8 col-md-10 q-pa-md">
        <q-input
          label="Sugestão"
          v-model="sugestion.description"
          hide-bottom-space
          dense
          autogrow
          outlined
        />
      </div>
      <div class="col q-pa-md text-right">
        <q-btn
          outline
          label="Enviar"
          icon="send"
          @click="submitSugestion"
          color="primary"
          :disable="!sugestion.description.trim()"
          :loading="saving"
        />
      </div>
    </div>
    <div
      class="q-pa-sm"
      v-for="sugestion in sugestionsData"
      :key="sugestion.id"
    >
      <q-card dark style="color:black; background-color:#E3E3E3">
        <div class="row">
          <span class="col-md-10 q-pa-lg">
            {{ sugestion.description }}
          </span>
          <span class="col q-pa-lg">
            Postado em: {{ formatDatetimeBR(sugestion.created_at) }}
          </span>
        </div>
      </q-card>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'

import { getSugestions, createSugestion } from 'src/services/sugestion/sugestion-api'
import { getMedia } from "src/services/media/media-api"
import { getProduct } from "src/services/product/product-api"
import { formatDatetimeBR } from 'src/services/utils/date'
import { formatResponseError } from "src/services/utils/error-formatter"
import { Notify, Loading } from 'quasar'

const route = useRoute()

let sugestionsData = ref([])
let loading = ref(false)
let saving = ref(false)
let productLogo = ref(null)
let productData = ref(null)

const mainPagination = ref({
  page: 1,
  rowsPerPage: 0,
  rowsNumber: 0,
})

const productId = route.params.product_id

let sugestion = ref({
  description: '',
})

const sugestionForm = ref(null)

onMounted(async () => {
  await getSugestionsFunction()
  await getProductFunction(productId)
})

async function getSugestionsFunction (props) {
  loading.value = true
  try {
    mainPagination.value.product_id = productId
    sugestionsData.value = await getSugestions(mainPagination.value)
  } catch (e) {
    Notify.create({
      message: 'Falha ao buscar sugestões',
      type: 'negative'
    })
  }
  loading.value = false
}

async function getProductFunction(productId) {
  Loading.show()
  loading.value = true
  try {
    const result = await getProduct(productId)
    productData.value = result
    if (productData.value) {
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

async function submitSugestion() {
  try {
    const sugestionToSave = { ...sugestion.value, product_id: productId }
    await createSugestion(sugestionToSave)

    Notify.create({
      message: 'Sugestão criada com sucesso!',
      type: 'positive'
    })

    await getSugestionsFunction()
    sugestion.value.description = ''
  } catch (error) {
    Notify.create({
      message: formatResponseError(error) || 'Falha ao criar sugestão',
      type: 'negative'
    })
  }
  saving.value = false
}

</script>

<style scoped>
  .q-img {
    box-shadow: 0 0 20px 0 rgba(0, 30, 255, 0.650);
    transition: 0.3s;
    border-radius: 5px;
  }
</style>
