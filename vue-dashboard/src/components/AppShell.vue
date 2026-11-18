<script setup>
import { RouterLink } from 'vue-router'
import { useAuth } from '../composables/useAuth'
import { useMock } from '../api/client'

const { user, logout } = useAuth()
</script>

<template>
  <div class="shell">
    <aside>
      <p class="logo">Spotify Vue</p>
      <RouterLink to="/">Dashboard</RouterLink>
      <RouterLink to="/tracks">Tracks</RouterLink>
      <RouterLink to="/artists">Artists</RouterLink>
      <p class="meta">{{ user?.name }} · {{ user?.role }}</p>
      <p v-if="useMock" class="meta">Mock API mode</p>
      <button type="button" @click="logout">Log out</button>
    </aside>
    <section class="content">
      <slot />
    </section>
  </div>
</template>
