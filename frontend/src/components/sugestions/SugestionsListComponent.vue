<template>
  <div>
    <h3>Sugestões</h3>
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
import { formatDatetimeBR } from 'src/services/utils/date'
import { formatResponseError } from "src/services/utils/error-formatter";
import { Notify } from 'quasar'

const route = useRoute()

let sugestionsData = ref([])
let loading = ref(false)
let saving = ref(false)

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
})

async function getSugestionsFunction (props) {
  loading.value = true
  try {
    mainPagination.value = props?.pagination || mainPagination.value
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
