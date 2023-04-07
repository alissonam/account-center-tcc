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
          v-model="suggestion.description"
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
          @click="submitSuggestion"
          color="primary"
          :disable="!suggestion.description.trim()"
          :loading="saving"
        />
      </div>
    </div>
    <div
      class="q-pa-sm"
      v-for="suggestion in suggestionsData"
      :key="suggestion.id"
    >
      <q-card dark style="color:dark; background-color:#E3E3E3">
        <div class="row">
          <span class="col-md-10 q-pa-lg">
            {{ suggestion.description }}
          </span>
          <span class="col q-pa-lg">
            Postado em: {{ formatDatetimeBR(suggestion.created_at) }}
          </span>
        </div>
      </q-card>
    </div>
  </div>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { useRoute } from 'vue-router'

import { getSuggestions, createSuggestion } from 'src/services/suggestion/suggestion-api'
import { getMedia } from "src/services/media/media-api"
import { getProduct } from "src/services/product/product-api"
import { formatDatetimeBR } from 'src/services/utils/date'
import { formatResponseError } from "src/services/utils/error-formatter"
import { Notify, Loading } from 'quasar'

const route = useRoute()

let suggestionsData = ref([])
let saving = ref(false)
let productLogo = ref(null)
let productData = ref(null)

const mainPagination = ref({
  page: 1,
  rowsPerPage: 0,
  rowsNumber: 0,
})

const productId = route.params.product_id

let suggestion = ref({
  description: '',
})

const suggestionForm = ref(null)

onMounted(async () => {
  Loading.show()
  await getSuggestionsFunction()
  await getProductFunction(productId)
  Loading.hide()

})

async function getSuggestionsFunction (props) {
  try {
    mainPagination.value.product_id = productId
    suggestionsData.value = await getSuggestions(mainPagination.value)
  } catch (e) {
    Notify.create({
      message: 'Falha ao buscar sugestões',
      type: 'negative'
    })
  }
}

async function getProductFunction(productId) {
  try {
    const result = await getProduct(productId)
    productData.value = result
  } catch (error) {
    Notify.create({
      message: formatResponseError(error) || 'Falha ao buscar produto',
      type: 'negative'
    })
  }
}

async function submitSuggestion() {
  saving.value = true
  try {
    const suggestionToSave = { ...suggestion.value, product_id: productId }
    await createSuggestion(suggestionToSave)

    Notify.create({
      message: 'Sugestão criada com sucesso!',
      type: 'positive'
    })

    suggestion.value.description = ''
    await getSuggestionsFunction()
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
