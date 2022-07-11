<template>
  <q-table
    title="Planos"
    :rows="plansData"
    :columns="columns"
    row-key="id"
    v-model:pagination="mainPagination"
    :loading="loading"
    loading-label="Carregando..."
    no-results-label="Nenhum plano encontrado"
    no-data-label="Nenhum plano encontrado"
    binary-state-sort
    @request="getPlansFunction"
  >
    <template v-slot:top-right>
      <q-btn
        icon="add"
        label="Cadastrar"
        color="primary"
        outline
        :to="{ name: 'plans_create' }"
      />
    </template>
    <template v-slot:body-cell-hidden="props">
      <q-td key="hidden" :props="props">
        <q-chip
          text-color="white"
          :label="t(`plan.hidden.${props.row.hidden}`)"
          :color="props.row.hidden === 0 ? 'warning' : 'negative'"
        />
      </q-td>
    </template>
    <template v-slot:body-cell-preferential="props">
      <q-td key="preferential" :props="props">
        <q-chip
          text-color="white"
          :label="t(`plan.preferential.${props.row.preferential}`)"
          :color="props.row.preferential === 0 ? 'warning' : 'negative'"
        />
      </q-td>
    </template>
    <template v-slot:body-cell-actions="props">
      <q-td key="actions" :props="props">
        <q-btn-group outline>
          <q-btn
            outline
            color="primary"
            icon="edit"
            :to="{ name: 'plans_update', params: { 'id': props.row.id } }"
          >
            <q-tooltip>
              Editar
            </q-tooltip>
          </q-btn>
          <q-btn
            outline
            color="negative"
            icon="delete"
            :loading="removing"
            :disable="removing"
            @click="destroyPlanFunction(props.row.id)"
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
import { getPlans, destroyPlan } from 'src/services/plan/plan-api'
import { t } from 'src/services/utils/i18n'
import { Notify, Dialog } from 'quasar'

let plansData = ref([])
let loading = ref(false)
let removing = ref(false)

const mainPagination = ref({
  page: 1,
  rowsPerPage: 10,
  rowsNumber: 0,
})

const columns = [
  {
    name: 'name',
    label: 'Nome',
    align: 'left',
    field: 'name',
    format: val => val || 'N/I',
  },
  {
    name: 'hidden',
    label: 'Visível',
    align: 'left',
    field: 'hidden',
    format: val => t(`plan.hidden.${val}`),
  },
  {
    name: 'preferential',
    label: 'Preferencial',
    align: 'left',
    field: 'preferential',
    format: val => t(`plan.preferential.${val}`),
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
  await getPlansFunction()
})

async function getPlansFunction (props) {
  loading.value = true
  try {
    mainPagination.value = props?.pagination || mainPagination.value
    plansData.value = await getPlans(mainPagination.value)
  } catch (e) {
    Notify.create({
      message: 'Falha ao buscar planos',
      type: 'negative'
    })
  }
  loading.value = false
}

function destroyPlanFunction (plan) {
  Dialog.create({
    title: 'Atenção!',
    message: 'Tem certeza que deseja excluir este plano?',
    cancel: true,
  }).onOk(async () => {
    removing.value = true
    try {
      await destroyPlan(plan)
      getPlansFunction()

      Notify.create({
        message: 'Plano excluído com sucesso!',
        type: 'positive'
      })
    } catch (e) {
      Notify.create({
        message: 'Falha ao excluir plano!',
        type: 'negative'
      })
    }
    removing.value = false
  })
}
</script>
