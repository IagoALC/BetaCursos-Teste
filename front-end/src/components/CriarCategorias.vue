<template>
  <q-card style="min-width: 500px;">
    <q-card-section class="q-pa-sm">
      <q-toolbar>
        <q-toolbar-title class="text-subtitle1 text-weight-medium text-grey-8">{{ categoriasType === 'edit' ? 'Atualizar' : 'Adicionar'}} Categoria</q-toolbar-title>
      </q-toolbar>

      <div class="q-pt-md q-px-md">
        <q-input v-model="nome" label="Nome *" spellcheck="false" dense />
        <q-input v-model="cor" label="Cor *" class="teste" spellcheck="false" dense readonly @click="viewColorPicker = !viewColorPicker">
          <template v-slot:no-option>
            <q-item>
              <q-item-section class="text-grey-8">Nenhuma categoria encontrada</q-item-section>
            </q-item>
          </template>
        </q-input>
        <q-color v-if="viewColorPicker" style="width: 200px;" v-model="cor" no-header no-footer class="my-picker q-mt-md" />
      </div>
    </q-card-section>

    <q-card-actions align="right">
      <q-btn flat label="Cancel" color="primary" v-close-popup />
      <q-btn flat label="OK" color="primary" @click="categoriasType === 'edit' ? updateCategory() : addCategory()" />
    </q-card-actions>
  </q-card>
</template>

<script setup>
import axios, { api } from 'boot/axios'
import { onBeforeMount, ref } from 'vue'
import { useQuasar } from 'quasar'

const q = useQuasar()
const props = defineProps(['categoriasEdit', 'categoriasType'])
const emit = defineEmits(['closeModal', 'getCategorias'])

const nome = ref('')
const cor = ref('')
const viewColorPicker = ref(false)

const addCategory = () => {
  if (verifyCategory()) return
  const params = {
    nome: nome.value,
    cor: cor.value,
  }

  api.post('categorias', params).then(() => {
    q.notify({ color: 'primary', position: 'top', message: 'Categoria adicionada com sucesso' })
    emit('closeModal')
    emit('getCategorias')
  }).catch(error => {
    console.log(error)
    q.notify({ color: 'negative', position: 'top', message: 'Erro ao adicionar categoria'
    })
  })
}

const updateCategory = () => {
  if (verifyCategory()) return
  const params = {
    nome: nome.value,
    cor: cor.value,
  }

  api.put(`categorias/${props.categoriasEdit.id}`, params).then(() => {
    q.notify({ color: 'primary', position: 'top', message: 'Categoria atualizada com sucesso' })
    emit('closeModal')
    emit('getCategorias')
  }).catch(error => {
    console.log(error)
    q.notify({ color: 'negative', position: 'top', message: 'Erro ao atualizar categoria'
    })
  })
}

const verifyCategory = () => {
  if (nome.value === '' || cor.value === '') {
    q.notify({ color: 'negative', position: 'top', message: 'Preencha os campos obrigatórios' })
    return true
  }
  return false
}

onBeforeMount(() => {
  if (props.categoriasType === 'edit') {
    nome.value = props.categoriasEdit.nome
    cor.value = props.categoriasEdit.cor
  }
})
</script>
<style>
  .q-field{
    &.q-field--readonly {
        .q-field__control {
          &:before {
            border-bottom-style: solid;
          }
        }
      }
  }
</style>