<template>
  <q-table
    title="Produtos"
    :rows="productsData"
    :columns="columns"
    row-key="id"
    v-model:pagination="mainPagination"
    :loading="loading"
    loading-label="Carregando..."
    no-results-label="Nenhum produto encontrado"
    no-data-label="Nenhum produto encontrado"
    binary-state-sort
    @request="getProductsFunction"
  >
    <template v-slot:top-right>
      <q-btn
        icon="add"
        label="Cadastrar"
        color="primary"
        outline
        :to="{ name: 'products_create' }"
      />
    </template>
    <template v-slot:body-cell-status="props">
      <q-td key="status" :props="props">
        <q-chip
          text-color="white"
          :label="t(`product.status.${props.row.status}`)"
          :color="props.row.status === 'active' ? 'positive' : 'negative'"
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
            :to="{ name: 'products_update', params: { 'id': props.row.id } }"
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
            @click="destroyProductFunction(props.row.id)"
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
import { getProducts, destroyProduct } from 'src/services/product/product-api'
import { t } from 'src/services/utils/i18n'
import { Notify, Dialog } from 'quasar'

let productsData = ref([])
let loading = ref(false)
let removing = ref(null)

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
    name: 'code',
    label: 'Codigo',
    align: 'left',
    field: 'code',
    format: val => val || 'N/I',
  },
  {
    name: 'status',
    label: 'Status',
    align: 'left',
    field: 'status',
    format: val => t(`product.status.${val}`),
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
  await getProductsFunction()
})

async function getProductsFunction (props) {
  loading.value = true
  try {
    mainPagination.value = props?.pagination || mainPagination.value
    productsData.value = await getProducts(mainPagination.value)
  } catch (e) {
    Notify.create({
      message: 'Falha ao buscar produtos',
      type: 'negative'
    })
  }
  loading.value = false
}

function destroyProductFunction (product) {
  Dialog.create({
    title: 'Atenção!',
    message: 'Tem certeza que deseja excluir este produto?',
    cancel: true,
  }).onOk(async () => {
    removing.value = product
    try {
      await destroyProduct(product)
      getProductsFunction()

      Notify.create({
        message: 'Produto excluído com sucesso!',
        type: 'positive'
      })
    } catch (e) {
      Notify.create({
        message: 'Falha ao excluir produto!',
        type: 'negative'
      })
    }
    removing.value = null
  })
}
</script>