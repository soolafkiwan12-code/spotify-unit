import { computed, ref } from 'vue'
import { useRouter } from 'vue-router'
import { api } from '../api/client'

const user = ref(JSON.parse(localStorage.getItem('user') || 'null'))

export function useAuth() {
  const router = useRouter()
  const error = ref('')
  const loading = ref(false)

  const isLoggedIn = computed(() => Boolean(localStorage.getItem('token')))
  const canManage = computed(() => ['admin', 'editor'].includes(user.value?.role))

  async function login(email, password) {
    error.value = ''
    loading.value = true
    try {
      const { data } = await api.login(email, password)
      localStorage.setItem('token', data.token)
      localStorage.setItem('user', JSON.stringify(data.user))
      user.value = data.user
      await router.push('/')
    } catch (err) {
      error.value = err.response?.data?.message || 'Login failed.'
    } finally {
      loading.value = false
    }
  }

  async function logout() {
    try {
      await api.logout()
    } catch {
      /* still clear local session */
    }
    localStorage.removeItem('token')
    localStorage.removeItem('user')
    user.value = null
    await router.push('/login')
  }

  return { user, error, loading, isLoggedIn, canManage, login, logout }
}
