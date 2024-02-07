<template>
  <div class="text-center">
    <div class="col justify-center">
      <div class="row justify-center q-mb-md">
        <q-input style="width: 50%" outlined v-model="search" label="Pesquisar" @keyup.enter="getCategorias">
          <template v-slot:append>
            <q-btn round dense flat icon="search" @click="getCategorias" />
          </template>
        </q-input>
        <div class="flex items-center">
          <q-btn dense flat icon="swap_vert" push round size="18px">
            <q-menu :offset="[125, 10]" style="border-radius: 15px">
              <q-list style="min-width: 150px">
                <q-item v-for="(item, i) in orderCategoriesList" :key="i" clickable :disable="blockClickOrderModeCategory"
                  @click="changeOrderCategories(item.value)">
                  <q-item-section>{{ item.label }}</q-item-section>
                  <q-item-section v-if="orderCategories == item.value" side>
                    <q-icon name="check" />
                  </q-item-section>
                </q-item>
                <q-separator />
                <div class="row" :class="blockClickOrderModeCategory ? 'disabled' : ''">
                  <div :class="orderModeCategories == 'ASC' ? 'bg-grey' : ''" class="cursor-pointer q-pa-md"
                    @click="changeOrderModeCategories('ASC')">
                    Crescente
                  </div>
                  <div :class="orderModeCategories == 'DESC' ? 'bg-grey' : ''" class="cursor-pointer q-pa-md"
                    @click="changeOrderModeCategories('DESC')">
                    Decrescente
                  </div>
                </div>
              </q-list>
            </q-menu>
            <q-tooltip>
              Organizar categorias
            </q-tooltip>
          </q-btn>
          <q-btn color="primary" class="text-capitalize q-ml-lg" label="Criar Categoria" @click="promptAddCategory = true" />
        </div>
      </div>
    </div>
    <div v-if = "categorias.length === 0" class="text-h6 text-grey-8">Nenhuma categoria cadastrada</div>
    <div class="row justify-center">
      <q-card dark bordered class="my-card col-sm-3 q-ma-sm" style="background: radial-gradient(circle, #35a2ff 0%, #014a88 100%)" v-for="categoria in categorias" :key="categoria.id">
        <q-card-section>
          <div class="text-subtitle2">ID: {{ categoria.id }}</div>
          <div class="text-h6">Nome: {{ categoria.nome }}</div>
          <q-badge rounded :style="{ 'background-color': categoria.cor }" />
        </q-card-section>

        <q-separator dark inset />

        <q-card-section>
          <q-btn flat label="Editar" class="text-capitalize" @click="editCategory(categoria)"/>
          <q-btn flat label="Excluir" class="text-capitalize" @click="deleteCategory(categoria.id)"/>
        </q-card-section>
      </q-card>
    </div>
    <div class="row justify-center">
      <q-pagination
        v-model="page"
        :max="maxPages"
        @click="getCategorias"
      />
    </div>
  </div>

  <q-dialog persistent v-model="promptAddCategory">
    <CriarCategorias @closeModal="promptAddCategory=false" :categoriasType="categoriasType" :categoriasEdit="categoriasEdit" @getCategorias="getCategorias"/>
  </q-dialog>
</template>
<script setup>
import axios, { api } from 'boot/axios'
import { ref, onBeforeMount } from 'vue'
import { useQuasar } from 'quasar'
import CriarCategorias from './CriarCategorias.vue'

const q = useQuasar()

const categorias = ref('')
const promptAddCategory = ref(false)
const categoriasEdit = ref(null)
const categoriasType = ref('insert')
const page = ref(1)
const maxPages = ref(1)
const search = ref('')
const blockClickOrderModeCategory = ref(false)
const orderModeCategories = ref('ASC')
const orderCategories = ref('nome')
const orderCategoriesList = ref([
  { label: 'Nome', value: 'nome' }
])

const getCategorias = () => {
  let params = {
    page: page.value,
    search: search.value,
    orderBy: orderCategories.value,
    orderMode: orderModeCategories.value
  }
  params = JSON.parse(JSON.stringify(params))
  api.get('categorias', { params }).then(response => {
    categorias.value = response.data.data
    maxPages.value = response.data.last_page
  }).catch(error => {
    console.log(error)
  })
}

const editCategory = (categoria) => {
  categoriasEdit.value = categoria
  categoriasType.value = 'edit'
  promptAddCategory.value = true
}

const deleteCategory = (id) => {
  q.dialog({
    title: 'Excluir Produto',
    message: 'Tem certeza que deseja excluir este categoria?',
    cancel: true,
    persistent: true
  }).onOk(() => {
    api.delete(`categorias/${id}`).then(() => {
      q.notify({ color: 'primary', position: 'top', message: 'Categoria excluído com sucesso' })
      getCategorias()
    }).catch(error => {
      console.log(error)
      q.notify({ color: 'negative', position: 'top', message: 'Erro ao excluir categoria' })
    })
  })
}

const changeOrderCategories = (order) => {
  if (orderCategories.value === order || blockClickOrderModeCategory.value) return
  blockClickOrderModeCategory.value = true
  orderCategories.value = order
  getCategorias()
  setTimeout(() => {
    blockClickOrderModeCategory.value = false
  }, 1000)
}

const changeOrderModeCategories = (mode) => {
  if (orderModeCategories.value === mode || blockClickOrderModeCategory.value) return
  blockClickOrderModeCategory.value = true
  orderModeCategories.value = mode
  getCategorias()
  setTimeout(() => {
    blockClickOrderModeCategory.value = false
  }, 1000)
}


onBeforeMount(() => {
  getCategorias()
})
</script>