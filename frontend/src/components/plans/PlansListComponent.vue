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
    <template v-slot:top>
      <div class="table-top-row full-width">
        <div class="row">
          <div class="col">
            <h6 class="q-mt-none q-mb-none text-weight-regular">
              Planos
            </h6>
          </div>
          <div class="col">
            <q-btn
              class="float-right"
              icon="add"
              label="Cadastrar"
              color="primary"
              outline
              :to="{ name: 'plans_create' }"
            />
          </div>
        </div>
        <div class="row q-mt-md q-gutter-md">
          <q-select
            v-model="mainPagination.product_id"
            :options="productsData"
            label="Produtos"
            color="primary"
            map-options
            emit-value
            outlined
            dense
            :option-label="opt => opt.name"
            option-value="id"
            clearable
            use-input
            fill-input
            hide-selected
            placeholder="Digite para pesquisar"
            @filter="filterProducts"
            @update:model-value="getPlansFunction"
          >
            <template v-slot:no-option>
              <no-option-select-slot label="Nenhuma filial encontrada"/>
            </template>
          </q-select>
        </div>
      </div>
    </template>
    <template v-slot:body-cell-preferential="props">
      <q-td key="preferential" :props="props">
        <q-chip
          text-color="white"
          :icon="props.row.preferential ? 'done' : 'close'"
          :label="t(`plan.preferential.${props.row.preferential}`)"
          :color="props.row.preferential ? 'positive' : 'negative'"
        />
      </q-td>
    </template>
    <template v-slot:body-cell-visible="props">
      <q-td key="visible" :props="props">
        <q-chip
          text-color="white"
          :icon="props.row.visible ? 'done' : 'close'"
          :label="t(`plan.visible.${props.row.visible}`)"
          :color="props.row.visible ? 'positive' : 'negative'"
        />
      </q-td>
    </template>
    <template v-slot:body-cell-default="props">
      <q-td key="default" :props="props">
        <q-chip
          text-color="white"
          :icon="props.row.default ? 'done' : 'close'"
          :label="t(`plan.default.${props.row.default}`)"
          :color="props.row.default ? 'positive' : 'negative'"
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
            :loading="removing === props.row.id"
            :disable="removing === props.row.id"
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
import { getProducts } from 'src/services/product/product-api'
import { t } from 'src/services/utils/i18n'
import { Notify, Dialog } from 'quasar'

let plansData = ref([])
let productsData = ref([])
let loading = ref(false)
let removing = ref(null)

const mainPagination = ref({
  page: 1,
  rowsPerPage: 10,
  rowsNumber: 0,
  with: ['product']
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
    name: 'product',
    label: 'Produto',
    align: 'left',
    field: row => row.product?.name,
    format: val => val || 'N/I',
  },
  {
    name: 'preferential',
    label: 'Preferencial',
    align: 'left',
    field: 'preferential',
    format: val => t(`plan.preferential.${val}`),
  },
  {
    name: 'visible',
    label: 'Visível',
    align: 'left',
    field: 'visible',
    format: val => t(`plan.visible.${val}`),
  },
  {
    name: 'default',
    label: 'Padrão',
    align: 'left',
    field: 'default',
    format: val => t(`plan.default.${val}`),
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
    removing.value = plan
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
    removing.value = null
  })
}

async function filterProducts(val, update, abort) {
  try {
    productsData.value = await getProducts({
      name: val,
      rowsPerPage: 25,
    })
    update()
  } catch {
    Notify.create({
      message: 'Falha ao buscar produto!',
      type: 'negative'
    })
    abort()
  }
}
</script>
