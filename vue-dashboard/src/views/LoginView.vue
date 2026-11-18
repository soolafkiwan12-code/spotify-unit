<script setup>
import { reactive } from 'vue'
import { useAuth } from '../composables/useAuth'
import { useMock } from '../api/client'

const { login, error, loading } = useAuth()
const form = reactive({
  email: 'admin@spotify.test',
  password: 'Admin@123',
})

function onSubmit() {
  login(form.email, form.password)
}
</script>

<template>
  <main class="login-page">
    <form class="card" @submit.prevent="onSubmit">
      <h1>Vue Dashboard</h1>
      <p>Composition API + Laravel Sanctum token</p>
      <p v-if="useMock" class="hint">Running with mock API. Set VITE_USE_MOCK=false to use Laravel.</p>
      <label>Email <input v-model="form.email" type="email" required /></label>
      <label>Password <input v-model="form.password" type="password" required /></label>
      <p v-if="error" class="err">{{ error }}</p>
      <button type="submit" :disabled="loading">{{ loading ? 'Signing in…' : 'Log in' }}</button>
    </form>
  </main>
</template>
