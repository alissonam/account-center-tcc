<template>
  <q-table
    title="Inscrições"
    :rows="subscriptionsData"
    :columns="columns"
    row-key="id"
    v-model:pagination="mainPagination"
    :loading="loading"
    loading-label="Carregando..."
    no-results-label="Nenhuma inscrição encontrada"
    no-data-label="Nenhuma inscrição encontrada"
    binary-state-sort
    @request="getSubscriptionsFunction"
  >
    <template v-slot:top-right>
      <q-btn
        icon="add"
        label="Cadastrar"
        color="primary"
        outline
        :to="{ name: 'subscriptions_create' }"
      />
    </template>
    <template v-slot:body-cell-actions="props">
      <q-td key="actions" :props="props">
        <q-btn-group outline>
          <q-btn
            outline
            color="primary"
            icon="edit"
            :to="{ name: 'subscriptions_update', params: { 'id': props.row.id } }"
          >
            <q-tooltip>
              Editar
            </q-tooltip>
          </q-btn>
          <q-btn
            outline
            color="negative"
            icon="delete"
            :loading="removing === props.row.id"
            :disable="removing === props.row.id"
            @click="destroySubscriptionFunction(props.row.id)"
          >
            <q-tooltip>
              Excluir
            </q-tooltip>
          </q-btn>
        </q-btn-group>
      </q-td>
    </template>
  </q-table>
</template>

<script setup>
import { onMounted, ref } from 'vue'
import { getSubscriptions, destroySubscription } from 'src/services/subscription/subscription-api'
import { Notify, Dialog } from 'quasar'

let subscriptionsData = ref([])
let loading = ref(false)
let removing = ref(null)

const mainPagination = ref({
  page: 1,
  rowsPerPage: 10,
  rowsNumber: 0,
})

const columns = [
  {
    name: 'user',
    label: 'Usuário',
    align: 'left',
    field: 'user',
    format: val => val?.name || 'N/I',
  },
  {
    name: 'product',
    label: 'Produto',
    align: 'left',
    field: 'product',
    format: val => val?.name || 'N/I',
  },
  {
    name: 'plan',
    label: 'Plano',
    align: 'left',
    field: 'plan',
    format: val => val?.name || 'N/I',
  },
  {
    name: 'actions',
    align: 'center',
    label: 'Ações',
    field: 'id',
    sortable: false
  },
]

onMounted(async () => {
  await getSubscriptionsFunction()
})

async function getSubscriptionsFunction (props) {
  loading.value = true
  try {
    mainPagination.value = props?.pagination || mainPagination.value
    mainPagination.value.with = [ 'plan', 'product', 'user' ]
    subscriptionsData.value = await getSubscriptions(mainPagination.value)
  } catch (e) {
    Notify.create({
      message: 'Falha ao buscar inscrição',
      type: 'negative'
    })
  }
  loading.value = false
}

function destroySubscriptionFunction (subscription) {
  Dialog.create({
    title: 'Atenção!',
    message: 'Tem certeza que deseja excluir esta inscrição?',
    cancel: true,
  }).onOk(async () => {
    removing.value = subscription
    try {
      await destroySubscription(subscription)
      getSubscriptionsFunction()

      Notify.create({
        message: 'Inscrição excluída com sucesso!',
        type: 'positive'
      })
    } catch (e) {
      Notify.create({
        message: 'Falha ao excluir inscrição!',
        type: 'negative'
      })
    }
    removing.value = null
  })
}

</script>
