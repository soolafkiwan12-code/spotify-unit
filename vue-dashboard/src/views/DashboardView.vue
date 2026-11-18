<script setup>
import { onMounted, ref } from 'vue'
import AppShell from '../components/AppShell.vue'
import { api } from '../api/client'

const stats = ref({ users: 0, artists: 0, albums: 0, tracks: 0 })
const error = ref('')

onMounted(async () => {
  try {
    const { data } = await api.dashboard()
    stats.value = data
  } catch (err) {
    error.value = err.response?.data?.message || 'Could not load dashboard.'
  }
})
</script>

<template>
  <AppShell>
    <h1>Dashboard</h1>
    <p v-if="error" class="err">{{ error }}</p>
    <div class="stats">
      <article v-for="(value, key) in stats" :key="key" class="card">
        <span>{{ key }}</span>
        <strong>{{ value }}</strong>
      </article>
    </div>
  </AppShell>
</template>
