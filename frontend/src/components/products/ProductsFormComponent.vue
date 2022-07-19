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
          <div class="row col-xs-12 col-sm-12 col-md-4 col-py-xs q-mr-md q-mb-lg">
            <div
              class="col-xs-12 col-sm-12 col-md-6 col-py-xs q-mr-md"
              :class="width > 1023 ? '' : 'q-mb-lg'"
            >
              <q-input
                label="Código"
                v-model="product.code"
                hide-bottom-space
                dense
                outlined
                :rules="[val => val.length <= 255 || 'Não pode ter mais de 255 caracteres']"
              />
            </div>
            <div class="col">
              <q-input
                label="Vindi ID"
                v-model="product.vindi_id"
                hide-bottom-space
                dense
                outlined
              />
            </div>
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
            <div class="text-h6">HTML</div>
            <q-editor
              v-model="product.description"
              flat
              content-class="bg-blue-1"
              toolbar-text-color="white"
              toolbar-toggle-color="yellow-8"
              toolbar-bg="primary"
              min-height="355px"
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
        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-6 col-py-xs q-mr-md q-mb-lg">
            <q-input
              label="Action URL"
              v-model="product.action_url"
              hide-bottom-space
              dense
              outlined
              :rules="[val => (val || '').length <= 255 || 'Não pode ter mais de 255 caracteres']"
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
              :rules="[val => (val || '').length <= 255 || 'Não pode ter mais de 255 caracteres']"
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
              :rules="[val => (val || '').length <= 255 || 'Não pode ter mais de 255 caracteres']"
            />
          </div>
        </div>
      </div>
      <div class="row">
        <div v-if="product.id">
          <q-chip
            text-color="white"
            :label="productLogo ? 'Logo de produto cadastrado' : 'Sem Logo de produto cadastrado'"
            :color="productLogo ? 'positive' : 'negative'"
          />
          <q-uploader
            ref="attachmentUploader"
            dense
            hide-bottom-space
            class="row"
            max-file-size="3000000"
            batch
            :url="`${uploadURL}/media`"
            :headers="[{ name: 'Authorization', value: `Bearer ${token}` }]"
            label="Clique para selecionar ou arraste arquivos aqui"
            no-thumbnails
            auto-upload
            color="primary"
            accept=".jpg,.png,.jpeg,.gif"
            :form-fields="() => [
                  {name: 'subject_id', value: product.id},
                  {name: 'media_type', value: 'product_logo'}
                ]"
            @start="() => closeLabel = 'Cancelar'"
            @uploaded="getLogoFunction(product.id)"
          />
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
import { onMounted, onUnmounted, ref, computed } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import { createProduct, updateProduct, getProduct } from 'src/services/product/product-api'
import { Notify, Loading } from 'quasar'
import { formatResponseError } from "src/services/utils/error-formatter"
import { getMedia } from "src/services/media/media-api"
import { userToken } from "src/services/utils/local-storage"

const router = useRouter()
const route = useRoute()
let saving = ref(false)
let productLogo = ref(null)

const token = localStorage.getItem('userToken')
const uploadURL = process.env.API_URL

let windowWidth = ref(window.innerWidth)
const onWidthChange = () => windowWidth.value = window.innerWidth
onMounted(() => window.addEventListener('resize', onWidthChange))
onUnmounted(() => window.removeEventListener('resize', onWidthChange))
const width = computed(() => windowWidth.value)

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
    getLogoFunction(route.params.id)
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
  window.open(value)
}

async function getLogoFunction(productId){
  try {
    let result = await getMedia({
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
</script>
