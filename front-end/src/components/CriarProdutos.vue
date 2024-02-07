<template>
  <q-card>
    <q-card-section class="q-pa-sm">
      <q-toolbar>
        <q-toolbar-title class="text-subtitle1 text-weight-medium text-grey-8">{{ produtosType === 'edit' ? 'Atualizar' : 'Adicionar'}} Produto</q-toolbar-title>
      </q-toolbar>

      <div class="q-pt-md q-px-md">
        <q-input v-model="nome" label="Nome *" spellcheck="false" dense />
        <q-input
          v-model="preco"
          dense
          label="Preço *"
          prefix="R$"
          mask="###.###.###.###.###.###.###.###.###.###.###.###,##"
          unmasked-value
          reverse-fill-mask
        />
        <q-input v-model="descricao" label="Descrição *" spellcheck="false" dense />
        <q-select
          standard
          v-model="categorias"
          multiple
          :options="options"
          :use-chips="true"
          label="Categorias *"
          style="width: 250px"
          option-value="id"
          option-label="nome"
        >
          <template v-slot:no-option>
            <q-item>
              <q-item-section class="text-grey-8">Nenhuma categoria encontrada</q-item-section>
            </q-item>
          </template>
        </q-select>
      </div>
    </q-card-section>

    <q-card-actions align="right">
      <q-btn flat label="Cancel" color="primary" v-close-popup />
      <q-btn flat label="OK" color="primary" @click="produtosType === 'edit' ? updateProduct() : addProduct()" />
    </q-card-actions>
  </q-card>
</template>

<script setup>
import axios, { api } from 'boot/axios'
import { ref, onBeforeMount } from 'vue'
import { useQuasar } from 'quasar'

const q = useQuasar()

const props = defineProps(['produtosEdit', 'produtosType'])
const emit = defineEmits(['closeModal', 'getProdutos'])

const nome = ref('')
const preco = ref('')
const descricao = ref('')
const categorias = ref([])
const options = ref([])

const addProduct = () => {
  if (verifyProduct()) return
  const params = {
    nome: nome.value,
    preco: preco.value,
    descricao: descricao.value,
    categorias: categorias.value
  }

  api.post('produtos', params).then(() => {
    q.notify({ color: 'primary', position: 'top', message: 'Produto adicionado com sucesso' })
    emit('closeModal')
    emit('getProdutos')
  }).catch(error => {
    console.log(error)
    q.notify({ color: 'negative', position: 'top', message: 'Erro ao adicionar produto'
    })
  })
}

const updateProduct = () => {
  if (verifyProduct()) return
  const params = {
    nome: nome.value,
    preco: preco.value,
    descricao: descricao.value,
    categorias: categorias.value
  }

  api.put(`produtos/${props.produtosEdit.id}`, params).then(() => {
    q.notify({ color: 'primary', position: 'top', message: 'Produto atualizado com sucesso' })
    emit('closeModal')
    emit('getProdutos')
  }).catch(error => {
    console.log(error)
    q.notify({ color: 'negative', position: 'top', message: 'Erro ao atualizar produto'
    })
  })
}

const verifyProduct = () => {
  if (nome.value === '' || preco.value === '' || descricao.value === '' || categorias.value.length === 0) {
    q.notify({ color: 'negative', position: 'top', message: 'Preencha os campos obrigatórios' })
    return true
  }
  return false
}

onBeforeMount(() => {
  api.get('categorias').then(response => {
    options.value = response.data.data
  }).catch(error => {
    console.log(error)
  })
  onBeforeMount(() => {
    if (props.produtosType === 'edit') {
      nome.value = props.produtosEdit.nome
      preco.value = props.produtosEdit.preco
      descricao.value = props.produtosEdit.descricao
      categorias.value = props.produtosEdit.categorias
    } else {
      nome.value = ''
      preco.value = ''
      descricao.value = ''
      categorias.value = []
    }
  })
})
</script>