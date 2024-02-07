<template>
  <div class="text-center">
    <div class="col justify-center">
      <div class="row justify-center q-mb-md">
        <q-input style="width: 50%" outlined v-model="search" label="Pesquisar" @keyup.enter="getProdutos">
          <template v-slot:append>
            <q-btn round dense flat icon="search" @click="getProdutos" />
          </template>
        </q-input>
        <div class="flex items-center">
          <q-btn dense flat icon="swap_vert" push round size="18px">
            <q-menu :offset="[125, 10]" style="border-radius: 15px">
              <q-list style="min-width: 150px">
                <q-item v-for="(item, i) in orderProductsList" :key="i" clickable :disable="blockClickOrderModeProduct"
                  @click="changeOrderProducts(item.value)">
                  <q-item-section>{{ item.label }}</q-item-section>
                  <q-item-section v-if="orderProducts == item.value" side>
                    <q-icon name="check" />
                  </q-item-section>
                </q-item>
                <q-separator />
                <div class="row" :class="blockClickOrderModeProduct ? 'disabled' : ''">
                  <div :class="orderModeProducts == 'ASC' ? 'bg-grey' : ''" class="cursor-pointer q-pa-md"
                    @click="changeOrderModeProducts('ASC')">
                    Crescente
                  </div>
                  <div :class="orderModeProducts == 'DESC' ? 'bg-grey' : ''" class="cursor-pointer q-pa-md"
                    @click="changeOrderModeProducts('DESC')">
                    Decrescente
                  </div>
                </div>
              </q-list>
            </q-menu>
            <q-tooltip>
              Organizar produtos
            </q-tooltip>
          </q-btn>
          <q-btn color="primary" class="text-capitalize q-ml-lg" label="Criar Produto" @click="promptAddProduct = true" />
        </div>
      </div>
      
    </div>
    <div v-if = "produtos.length === 0" class="text-h6 text-grey-8">Nenhum produto cadastrado</div>
    <div class="row justify-center">
      <q-card dark bordered class="my-card col-sm-3 q-ma-sm" style="background: radial-gradient(circle, #35a2ff 0%, #014a88 100%)" v-for="produto in produtos" :key="produto.id">
        <q-card-section>
          <div class="text-subtitle2">ID: {{ produto.id }}</div>
          <div class="text-h6">Nome: {{ produto.nome }}</div>
          <div class="text-subtitle2">{{ formatter.format(produto.preco / 100) }}</div>
          <div class="text-subtitle2">{{ produto.descricao }}</div>
        </q-card-section>

        <q-separator dark inset />

        <q-card-section>
          <div class="text-subtitle2">Categorias:</div>
          <div class="text-subtitle2" v-for="categoria in produto.categorias" :key="categoria.id"><q-badge rounded :style="{ 'background-color': categoria.cor }" /> {{ categoria.nome }} </div>
        </q-card-section>
        <q-separator dark inset />

        <q-card-section>
          <q-btn flat label="Editar" class="text-capitalize" @click="editProduct(produto)"/>
          <q-btn flat label="Excluir" class="text-capitalize" @click="deleteProduct(produto.id)"/>
        </q-card-section>
      </q-card>
    </div>
    <div class="row justify-center">
      <q-pagination
        v-model="page"
        :max="maxPages"
        @click="getProdutos"
      />
    </div>
  </div>

  <q-dialog persistent v-model="promptAddProduct">
    <CriarProdutos @closeModal="promptAddProduct=false" :produtosType="produtosType" :produtosEdit="produtosEdit" @getProdutos="getProdutos"/>
  </q-dialog>
</template>
<script setup>
import axios, { api } from 'boot/axios'
import { ref, onBeforeMount } from 'vue'
import { useQuasar } from 'quasar'
import CriarProdutos from './CriarProdutos.vue';

const q = useQuasar()

const produtos = ref('')
const produtosEdit = ref(null)
const promptAddProduct = ref(false)
const produtosType = ref('insert')
const page = ref(1)
const maxPages = ref(1)
const formatter = ref(new Intl.NumberFormat('pt-BR', { style: 'currency', currency: 'BRL', minimumFractionDigits: 2 }))
const search = ref('')
const blockClickOrderModeProduct = ref(false)
const orderModeProducts = ref('ASC')
const orderProducts = ref('nome')
const orderProductsList = ref([
  { label: 'Nome', value: 'nome' },
  { label: 'Categoria', value: 'category' },
])

const getProdutos = () => {
  let params = {
    page: page.value,
    search: search.value,
    orderBy: orderProducts.value,
    orderMode: orderModeProducts.value
  }
  params = JSON.parse(JSON.stringify(params))
  api.get('produtos', { params }).then(response => {
    if (page.value > response.data.last_page) {
      page.value = response.data.last_page
      getProdutos()
      return
    }
    produtos.value = response.data.data
    maxPages.value = response.data.last_page
  }).catch(error => {
    console.log(error)
  })
}

const editProduct = (produto) => {
  produtosEdit.value = produto
  produtosType.value = 'edit'
  promptAddProduct.value = true
}

const deleteProduct = (id) => {
  q.dialog({
    title: 'Excluir Produto',
    message: 'Tem certeza que deseja excluir este produto?',
    cancel: true,
    persistent: true
  }).onOk(() => {
    api.delete(`produtos/${id}`).then(() => {
      q.notify({ color: 'primary', position: 'top', message: 'Produto excluído com sucesso' })
      getProdutos()
    }).catch(error => {
      console.log(error)
      q.notify({ color: 'negative', position: 'top', message: 'Erro ao excluir produto' })
    })
  })
}

const changeOrderProducts = (order) => {
  if (orderProducts.value === order || blockClickOrderModeProduct.value) return
  blockClickOrderModeProduct.value = true
  orderProducts.value = order
  getProdutos()
  setTimeout(() => {
    blockClickOrderModeProduct.value = false
  }, 1000)
}

const changeOrderModeProducts = (mode) => {
  if (orderModeProducts.value === mode || blockClickOrderModeProduct.value) return
  blockClickOrderModeProduct.value = true
  orderModeProducts.value = mode
  getProdutos()
  setTimeout(() => {
    blockClickOrderModeProduct.value = false
  }, 1000)
}

onBeforeMount(() => {
  getProdutos()
})
</script>