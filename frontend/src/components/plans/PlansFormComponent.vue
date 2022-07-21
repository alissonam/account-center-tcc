<template>
  <div>
    <q-btn
      color="primary"
      icon="arrow_back"
      dense
      outline
      rounded
      :to="{ name: 'plans' }"
    >
      <q-tooltip :offset="[5, 5]">
        Voltar
      </q-tooltip>
    </q-btn>
    <h4 class="q-mt-lg" v-if="!route.params.id">Criar plano</h4>
    <h4 class="q-mt-lg" v-else>Editar plano</h4>
    <q-form
      ref="planForm"
      @submit="submitPlan()"
    >
      <div class="row q-mb-lg">
        <div class="col q-gutter-md">
          <q-toggle
            label="Preferencial:"
            left-label
            v-model="plan.preferential"
            color="green"
          />
          <q-toggle
            label="Visível:"
            left-label
            v-model="plan.visible"
            color="green"
          />
          <q-toggle
            label="Padrão:"
            left-label
            v-model="plan.default"
            color="green"
          />
        </div>
      </div>
      <div>
        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-4 col-py-xs q-mr-md q-mb-lg">
            <q-input
              label="Nome"
              v-model="plan.name"
              hide-bottom-space
              dense
              outlined
              :rules="[val => !!val || 'Preenchimento obrigatório']"
            />
          </div>
          <div class="col-xs-12 col-sm-12 col-md-3 col-py-xs q-mr-md">
            <q-select
              :readonly="route.params.id"
              v-model="plan.product_id"
              :options="productsOptions"
              label="Produto"
              map-options
              emit-value
              dense
              outlined
              :option-label="opt => opt.name || plan.product?.name || 'N/I'"
              option-value="id"
              clearable
              :rules="[val => !!val || 'Preenchimento obrigatório']"
              use-input
              fill-input
              hide-selected
              placeholder="Digite para pesquisar"
              input-debounce="300"
              @filter="filterProducts"
            >
              <template v-slot:no-option>
                <q-item>
                  <q-item-section class="text-italic text-grey">
                    Nenhum produto encontrado
                  </q-item-section>
                </q-item>
              </template>
            </q-select>
          </div>
            <div class="col-xs-5 col-sm-5 col-md-2 col-py-xs q-mr-md q-mb-lg">
              <q-input
                label="Plano Vindi"
                v-model="plan.vindi_plan_id"
                hide-bottom-space
                dense
                outlined
              />
            </div>
            <div class="col-xs-5 col-sm-5 col-md-2 col-py-xs q-mr-md q-mb-lg">
              <q-input
                label="Produto Vindi"
                v-model="plan.vindi_product_id"
                hide-bottom-space
                dense
                outlined
              />
            </div>
          </div>
        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-6 col-py-xs q-mr-md q-mb-lg">
            <div class="text-h6">JSON</div>
            <Vue3JsonEditor
              v-model="plan.payload"
              mode="code"
              :expandedOnStart="false"
              @json-change="val => plan.payload = val"
            />
          </div>
          <div class="col q-mb-md">
            <div class="text-h6">HTML</div>
            <q-editor
              v-model="plan.description"
              flat
              content-class="bg-blue-1"
              toolbar-text-color="white"
              toolbar-toggle-color="yellow-8"
              toolbar-bg="primary"
              :toolbar="[
                [
                  {
                    icon: $q.iconSet.editor.align,
                    fixedLabel: true,
                    fixedIcon: true,
                    options: [
                      'left',
                      'center',
                      'right',
                      'justify'
                    ]
                  },
                  {
                    icon: 'filter_1',
                    fixedLabel: true,
                    fixedIcon: true,
                    options: [
                      'bold',
                      'italic',
                      'strike',
                      'underline'
                    ]
                  },
                  {
                    icon: $q.iconSet.editor.formatting,
                    fixedLabel: true,
                    fixedIcon: true,
                    options: [
                      'p',
                      'h1',
                      'h2',
                      'h3',
                      'h4',
                      'h5',
                      'h6',
                      'code'
                    ]
                  },
                  {
                    icon: $q.iconSet.editor.fontSize,
                    fixedLabel: true,
                    fixedIcon: true,
                    options: [
                      'size-1',
                      'size-2',
                      'size-3',
                      'size-4',
                      'size-5',
                      'size-6',
                      'size-7'
                    ]
                  },
                ],
                ['undo', 'redo', 'fullscreen']
              ]"
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
import { createPlan, updatePlan, getPlan } from 'src/services/plan/plan-api'
import { getProducts } from 'src/services/product/product-api'
import { Notify, Loading } from 'quasar'
import { formatResponseError } from "src/services/utils/error-formatter";
import { Vue3JsonEditor } from 'vue3-json-editor'

const router = useRouter()
const route = useRoute()
let saving = ref(false)

let plan = ref({
  payload: {},
  description: '',
  preferential: false,
  visible: false,
  default: false
})

const planForm = ref(null)
let productsOptions = ref([])

onMounted(async () => {
  if (route.params.id) {
    await getPlanFunction()
  }
})

async function submitPlan() {
  saving.value = true
  try {
    const validated = planForm.value.validate()
    if (validated) {
      const planToSave = { ...plan.value }
      !route.params.id ? await createPlan(planToSave) : await updatePlan(route.params.id, planToSave)

      Notify.create({
        message: !route.params.id ? 'Plano criado com sucesso!' : 'Plano editado com sucesso!',
        type: 'positive'
      })

      router.push({ name: 'plans' })
    }
  } catch (error) {
    Notify.create({
      message: formatResponseError(error) || 'Falha ao salvar plano',
      type: 'negative'
    })
  }
  saving.value = false
}

async function getPlanFunction() {
  Loading.show()
  try {
    const response = await getPlan(route.params.id, {
      with: ['product']
    })
    plan.value = response
  } catch (e) {
    Notify.create({
      message: 'Falha ao buscar plano!',
      type: 'negative'
    })
  }
  Loading.hide()
}

async function filterProducts(val, update, abort) {
  try {
    productsOptions.value = await getProducts({
      name: val,
      rowsPerPage: 25,
    })
    update()
  } catch (e) {
    Notify.create({
      message: 'Falha ao buscar produtos!',
      type: 'negative'
    })
    abort()
  }
}
</script>
