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
    <template v-slot:top>
      <div class="table-top-row full-width">
        <div class="row">
          <div class="col">
            <h6 class="q-mt-none q-mb-none text-weight-regular">
              Inscrições
            </h6>
          </div>
          <div class="col">
            <q-btn
              class="float-right"
              icon="add"
              label="Cadastrar"
              color="primary"
              outline
              :to="{ name: 'subscriptions_create' }"
            />
          </div>
        </div>
        <div class="row q-mt-md q-gutter-md">
          <q-btn-toggle
            v-model="mainPagination.status"
            spread
            no-caps
            size="md"
            toggle-color="primary"
            :options="statusOptions"
            @update:model-value="getSubscriptionsFunction"
          />
        </div>
      </div>
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
import { formatDateBR, parseDate } from 'src/services/utils/date'

let subscriptionsData = ref([])
let loading = ref(false)
let removing = ref(null)

const mainPagination = ref({
  page: 1,
  rowsPerPage: 10,
  rowsNumber: 0,
  status: 'active'
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
    name: 'finished_in',
    label: 'Finalizado em',
    align: 'left',
    field: 'finished_in',
    format: val => formatDateBR(val) || 'N/I',
  },
  {
    name: 'actions',
    align: 'center',
    label: 'Ações',
    field: 'id',
    sortable: false
  },
]

const statusOptions = [
  {
    label: 'Ativas',
    value: 'active',
  },
  {
    label: 'Pendentes',
    value: 'awaiting'
  },
  {
    label: 'Inactivas',
    value: 'inactive'
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
