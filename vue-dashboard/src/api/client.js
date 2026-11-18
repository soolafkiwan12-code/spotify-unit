import axios from 'axios'
import * as mock from './mock'

const http = axios.create({
  baseURL: import.meta.env.VITE_API_URL || 'http://127.0.0.1:8000/api',
})

http.interceptors.request.use((config) => {
  const token = localStorage.getItem('token')
  if (token) {
    config.headers.Authorization = `Bearer ${token}`
  }
  return config
})

export const useMock = mock.isMock()

export const api = {
  login(email, password) {
    return useMock ? mock.login(email, password) : http.post('/login', { email, password })
  },
  logout() {
    return useMock ? mock.logout() : http.post('/logout')
  },
  dashboard() {
    return useMock ? mock.dashboard() : http.get('/dashboard')
  },
  tracks(q) {
    return useMock ? mock.listTracks(q) : http.get('/tracks', { params: { q } })
  },
  createTrack(payload) {
    return useMock ? mock.createTrack(payload) : http.post('/tracks', payload)
  },
  updateTrack(id, payload) {
    return useMock ? mock.updateTrack(id, payload) : http.put(`/tracks/${id}`, payload)
  },
  deleteTrack(id) {
    return useMock ? mock.deleteTrack(id) : http.delete(`/tracks/${id}`)
  },
  artists() {
    return useMock ? mock.listArtists() : http.get('/artists')
  },
  createArtist(payload) {
    return useMock ? mock.createArtist(payload) : http.post('/artists', payload)
  },
}
