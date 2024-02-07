<template>
  <div class="q-pa-md row justify-center">
    <div class="q-gutter-y-md" style="width: 80%">
      <q-card class="no-shadow">
        <q-tabs
          v-model="tab"
          dense
          class="text-grey"
          active-color="primary"
          indicator-color="primary"
          align="justify"
          narrow-indicator
        >
          <q-tab name="produtos" label="produtos" />
          <q-tab name="categorias" label="categorias" />
        </q-tabs>

        <q-separator />

        <q-tab-panels v-model="tab" animated>
          <q-tab-panel name="produtos">
            <Produtos />
          </q-tab-panel>

          <q-tab-panel name="categorias">
            <Categorias />
          </q-tab-panel>
        </q-tab-panels>
      </q-card>
    </div>
  </div>
</template>

<script setup>
import Categorias from 'src/components/Categorias.vue';
import Produtos from 'src/components/Produtos.vue';
import { defineComponent, ref } from 'vue'

const tab = ref('produtos')
</script>
