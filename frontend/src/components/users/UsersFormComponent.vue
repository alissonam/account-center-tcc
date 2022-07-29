<template>
  <div>
    <q-btn
      color="primary"
      icon="arrow_back"
      dense
      outline
      rounded
      :to="loggedUser.role == 'admin' ? { name: 'users' } : { name: 'dashboard' }"
    >
      <q-tooltip :offset="[5, 5]">
        Voltar
      </q-tooltip>
    </q-btn>
    <h4 class="q-mt-lg" v-if="!route.params.id">Criar perfil</h4>
    <h4 class="q-mt-lg" v-else>Editar perfil</h4>
    <q-form
      ref="userForm"
      @submit="submitUser()"
    >
      <div>
        <q-dialog v-model="openPasswordModal">
          <q-card style="width: 660px; max-width: 80vw;">
            <div class="text-h6 q-ma-md">
              Definição de senha:
            </div>
            <q-card-section>
              <div class="col-xs-12 col-sm-12 col-md-6 col-py-xs q-mb-lg q-mr-md">
                <div class="text-deep-orange text-bold">
                  Aviso: Após o salvamento a senha não será mais exibida.
                </div>
                <q-input
                  v-model="user.password"
                  label="Senha"
                  outlined
                  clearable
                  dense
                  hide-bottom-space
                  :rules="[
                    val => val && val.length >= 6 || 'A senha deve ter no mínimo 6 caracteres'
                  ]"
                  lazy-rules
                />
              </div>
            </q-card-section>
            <q-card-actions align="right">
              <q-btn
                label="Fechar"
                dense
                outline
                color="negative"
                type="button"
                @click="openPasswordModal = false"
              />
            </q-card-actions>
          </q-card>
        </q-dialog>
        <div align="right" class="q-mb-md">
          <q-btn
            outline
            label="Definir senha"
            icon="lock_open"
            color="positive"
            type="button"
            @click="openPasswordModal = true"
          />
          <q-chip
            v-if="user.password"
            color="positive"
            text-color="white"
            label="Senha definida"
          />
          <div
            v-if="$route.params.id"
            class="text-deep-orange text-bold"
          >
            Aviso: caso a senha não seja definida será enviado um e-mail para definição da senha.
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-4 col-py-xs q-mr-md q-mb-lg">
            <q-input
              label="Nome"
              v-model="user.name"
              hide-bottom-space
              dense
              outlined
              :rules="[val => !!val || 'Preenchimento obrigatório']"
            />
          </div>
          <div class="col-xs-12 col-sm-12 col-md-4 col-py-xs q-mr-md q-mb-lg">
            <q-input
              label="Sobrenome/Razão social"
              v-model="user.last_name"
              hide-bottom-space
              dense
              outlined
            />
          </div>
          <div class="col q-mb-lg">
            <q-input
              label="Documento"
              :mask="(user.document || '').length < 15
                ? '###.###.###-######' : '##.###.###/####-##'"
              v-model="user.document"
              hide-bottom-space
              dense
              outlined
              :rules="[
                checkIfCPForCNPJIsValid
              ]"
            />
          </div>
        </div>
        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-4 col-py-xs q-mr-md q-mb-lg">
            <q-input
              label="Registro"
              v-model="user.registration"
              hide-bottom-space
              dense
              outlined
            />
          </div>
          <div class="col-xs-12 col-sm-12 col-md-4 col-py-xs q-mr-md q-mb-lg">
            <q-input
              :disable="loggedUser.role != 'admin'"
              label="E-mail"
              v-model="user.email"
              hide-bottom-space
              dense
              outlined
              :rules="[val => !!val || 'Preenchimento obrigatório']"
            />
          </div>
          <div class="col q-mb-lg">
            <q-input
              label="Telefone"
              :mask="(user.phone || '').length < 15
                ? '(##) ####-#####' : '(##) #####-####'"
              v-model="user.phone"
              hide-bottom-space
              dense
              outlined
              :rules="[val => !!val || 'Preenchimento obrigatório']"
            />
          </div>
        </div>
        <div
          v-if="loggedUser.role == 'admin'"
          class="row"
        >
          <div class="col-xs-12 col-sm-12 col-md-4 col-py-xs q-mr-md q-mb-lg">
            <q-select
              label="Perfil"
              map-options
              emit-value
              hide-bottom-space
              clearable
              v-model="user.role"
              :options="rolesOptions"
              option-label="label"
              option-value="value"
              dense
              outlined
              :rules="[val => !!val || 'Preenchimento obrigatório']"
            />
          </div>
          <div
            class="col-xs-12 col-sm-12 col-md-4 col-py-xs q-mr-md q-mb-lg"
            v-if="route.params.id"
          >
            <q-select
              label="Status"
              map-options
              emit-value
              hide-bottom-space
              clearable
              v-model="user.status"
              :options="statusOptions"
              option-label="label"
              option-value="value"
              dense
              outlined
              :rules="[val => !!val || 'Preenchimento obrigatório']"
            />
          </div>
          <div class="col q-mb-lg">
            <q-input
              label="Vindi ID"
              v-model="user.vindi_id"
              hide-bottom-space
              dense
              outlined
            />
          </div>
        </div>
        <div class="row justify-between q-mb-md">
          <div class="text-h6">Dados do endereço</div>
        </div>
        <div class="row">
          <div class="col-xs-12 col-sm-12 col-md-3 col-py-xs q-mr-md q-mb-lg">
            <q-input
              label="CEP"
              mask="#####-###"
              v-model="user.zipcode"
              hide-bottom-space
              dense
              outlined
              :loading="searchForZipCode"
              @change="searchAddressWithZipcode()"
            />
          </div>
          <div class="col-xs-12 col-sm-12 col-md-3 col-py-xs q-mr-md q-mb-lg">
            <q-input
              label="Estado"
              v-model="user.state"
              hide-bottom-space
              dense
              outlined
            />
          </div>
          <div class="col-xs-12 col-sm-12 col-md-3 col-py-xs q-mr-md q-mb-lg">
            <q-input
              label="Cidade"
              v-model="user.city"
              hide-bottom-space
              dense
              outlined
            />
          </div>
          <div class="col q-mb-lg">
            <q-input
              label="Bairro"
              v-model="user.neighborhood"
              hide-bottom-space
              dense
              outlined
            />
          </div>
        </div>

        <div class="row">

          <div class="col-xs-12 col-sm-12 col-md-3 col-py-xs q-mr-md q-mb-lg">
            <q-input
              label="Rua"
              v-model="user.street"
              hide-bottom-space
              dense
              outlined
            />
          </div>
          <div class="col-xs-12 col-sm-12 col-md-3 col-py-xs q-mr-md q-mb-lg">
            <q-input
              label="Número"
              v-model="user.number"
              hide-bottom-space
              dense
              outlined
            />
          </div>
          <div class="col-xs-12 col-sm-12 col-md-3 col-py-xs q-mr-md q-mb-lg">
            <q-input
              label="Complemento"
              v-model="user.complement"
              hide-bottom-space
              dense
              outlined
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
import { createUser, updateUser, getUser } from 'src/services/user/user-api'
import { Notify, Loading } from 'quasar'
import { formatResponseError } from "src/services/utils/error-formatter";
import {locationFromZipCode} from "src/services/utils/ceps";
import {validateCPForCNPJ} from "src/services/utils/documents";
import { loggedUser } from "boot/user"

const router = useRouter()
const route = useRoute()
let searchForZipCode = false
let saving = ref(false)
let openPasswordModal = ref(false)

const rolesOptions = [
  {
    label: 'Administrador',
    value: 'admin'
  },
  {
    label: 'Membro',
    value: 'member'
  },
]

const statusOptions = [
  {
    label: 'Ativo',
    value: 'active'
  },
  {
    label: 'Bloqueado',
    value: 'blocked'
  },
  {
    label: 'Pendente de senha',
    value: 'pending_password'
  },
]

let user = ref({
  name: '',
  email: null,
  password: null,
})

const userForm = ref(null)

onMounted(async () => {
  const userId = loggedUser.role == 'member' ? loggedUser.id : route.params.id
  if (userId) {
    await getUserFunction(userId)
  }
})

async function submitUser() {
  saving.value = true
  try {
    const validated = userForm.value.validate()
    if (validated) {
      const userToSave = { ...user.value }
      !route.params.id ? await createUser(userToSave) : await updateUser(route.params.id, userToSave)

      Notify.create({
        message: !route.params.id ? 'Usuário criado com sucesso!' : 'Usuário editado com sucesso!',
        type: 'positive'
      })

      loggedUser.role == 'admin' ? router.push({ name: 'users' }) : router.push({ name: 'dashboard' })
    }
  } catch (error) {
    Notify.create({
      message: formatResponseError(error) || 'Falha ao salvar usuário',
      type: 'negative'
    })
  }
  saving.value = false
}

async function getUserFunction(userId) {
  Loading.show()
  try {
    const response = await getUser(userId)
    user.value = response
  } catch (e) {
    Notify.create({
      message: 'Falha ao buscar usuário!',
      type: 'negative'
    })
  }
  Loading.hide()
}

async function searchAddressWithZipcode() {
  Loading.show()
  if (user.value.zipcode.length === 9) {
    searchForZipCode = true
    try {
      const response = await locationFromZipCode(user.value.zipcode)

      user.value.state = response.state
      user.value.city = response.city
      user.value.neighborhood = response.neighborhood
      user.value.street = response.street
      user.value.complement = response.complement
    } catch (e) {
      Notify.create({
        message: 'Falha ao encontrar CEP!',
        type: 'negative'
      })
    }
    searchForZipCode = false
  }
  Loading.hide()
}

async function checkIfCPForCNPJIsValid(value) {
  if (!value || value.length === 0) {
    return true
  }
  const isValid = await validateCPForCNPJ(value)
  return isValid || '* Documento inválido'
}
</script>
