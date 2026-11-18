<script setup>
import { onMounted, reactive, ref } from 'vue'
import AppShell from '../components/AppShell.vue'
import { api } from '../api/client'
import { useAuth } from '../composables/useAuth'

const { canManage } = useAuth()
const artists = ref([])
const error = ref('')
const form = reactive({ name: '', country: '' })

async function load() {
  try {
    const { data } = await api.artists()
    artists.value = data
  } catch (err) {
    error.value = err.response?.data?.message || 'Could not load artists.'
  }
}

async function save() {
  try {
    await api.createArtist({ ...form })
    form.name = ''
    form.country = ''
    await load()
  } catch (err) {
    error.value = err.response?.data?.message || 'Could not add artist.'
  }
}

onMounted(load)
</script>

<template>
  <AppShell>
    <h1>Artists</h1>
    <p v-if="error" class="err">{{ error }}</p>
    <form v-if="canManage" class="card form" @submit.prevent="save">
      <label>Name <input v-model="form.name" required /></label>
      <label>Country <input v-model="form.country" /></label>
      <button type="submit">Insert artist</button>
    </form>
    <table>
      <thead>
        <tr><th>Name</th><th>Country</th><th>Albums</th></tr>
      </thead>
      <tbody>
        <tr v-for="artist in artists" :key="artist.id">
          <td>{{ artist.name }}</td>
          <td>{{ artist.country }}</td>
          <td>{{ artist.albums_count }}</td>
        </tr>
      </tbody>
    </table>
  </AppShell>
</template>
