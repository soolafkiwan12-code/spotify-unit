<script setup>
import { onMounted, reactive, ref } from 'vue'
import AppShell from '../components/AppShell.vue'
import { api } from '../api/client'
import { useAuth } from '../composables/useAuth'

const { canManage } = useAuth()
const tracks = ref([])
const q = ref('')
const error = ref('')
const form = reactive({
  id: null,
  title: '',
  genre: 'Pop',
  duration_seconds: 180,
  album_id: 1,
})

async function load() {
  error.value = ''
  try {
    const { data } = await api.tracks(q.value)
    tracks.value = data
  } catch (err) {
    error.value = err.response?.data?.message || 'Could not load tracks.'
  }
}

function edit(track) {
  form.id = track.id
  form.title = track.title
  form.genre = track.genre || ''
  form.duration_seconds = track.duration_seconds
  form.album_id = track.album_id
}

function resetForm() {
  form.id = null
  form.title = ''
  form.genre = 'Pop'
  form.duration_seconds = 180
  form.album_id = 1
}

async function save() {
  try {
    if (form.id) {
      await api.updateTrack(form.id, { ...form })
    } else {
      await api.createTrack({ ...form })
    }
    resetForm()
    await load()
  } catch (err) {
    error.value = err.response?.data?.message || 'Save failed. Check your role.'
  }
}

async function remove(id) {
  if (!confirm('Delete this track?')) return
  await api.deleteTrack(id)
  await load()
}

onMounted(load)
</script>

<template>
  <AppShell>
    <div class="toolbar">
      <h1>Tracks</h1>
    </div>
    <form class="search" @submit.prevent="load">
      <input v-model="q" type="search" placeholder="Search tracks" />
      <button type="submit">Search</button>
    </form>
    <p v-if="error" class="err">{{ error }}</p>

    <form v-if="canManage" class="card form" @submit.prevent="save">
      <h2>{{ form.id ? 'Edit track' : 'Add track' }}</h2>
      <label>Title <input v-model="form.title" required /></label>
      <label>Genre <input v-model="form.genre" /></label>
      <label>Duration <input v-model.number="form.duration_seconds" type="number" min="1" required /></label>
      <button type="submit">{{ form.id ? 'Update' : 'Insert' }}</button>
      <button v-if="form.id" type="button" class="ghost" @click="resetForm">Cancel</button>
    </form>

    <table>
      <thead>
        <tr>
          <th>Title</th>
          <th>Artist</th>
          <th>Album</th>
          <th>Genre</th>
          <th v-if="canManage"></th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="track in tracks" :key="track.id">
          <td>{{ track.title }}</td>
          <td>{{ track.artist }}</td>
          <td>{{ track.album }}</td>
          <td>{{ track.genre }}</td>
          <td v-if="canManage">
            <button type="button" class="ghost" @click="edit(track)">Edit</button>
            <button type="button" class="danger" @click="remove(track.id)">Delete</button>
          </td>
        </tr>
      </tbody>
    </table>
  </AppShell>
</template>
