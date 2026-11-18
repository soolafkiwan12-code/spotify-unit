const useMock = import.meta.env.VITE_USE_MOCK !== 'false'

let tracks = [
  { id: 1, title: 'Blinding Lights', genre: 'Synth-pop', duration_seconds: 200, album_id: 5, album: 'After Hours', artist: 'The Weeknd' },
  { id: 2, title: 'WILDFLOWER', genre: 'Pop', duration_seconds: 261, album_id: 2, album: 'WILDFLOWER', artist: 'Billie Eilish' },
  { id: 3, title: 'Salma', genre: 'Rock', duration_seconds: 230, album_id: 7, album: 'Malyeesh', artist: 'Jadal' },
  { id: 4, title: 'Garee2a Awy', genre: 'Afrobeats', duration_seconds: 214, album_id: 1, album: 'Garee2a Awy — A COLORS SHOW', artist: 'TUL8TE' },
]

let artists = [
  { id: 1, name: 'TUL8TE', country: 'Egypt', albums_count: 1 },
  { id: 2, name: 'Billie Eilish', country: 'USA', albums_count: 2 },
  { id: 3, name: 'The Weeknd', country: 'Canada', albums_count: 1 },
  { id: 4, name: 'Jadal', country: 'Jordan', albums_count: 1 },
]

const accounts = {
  'admin@spotify.test': { password: 'Admin@123', user: { id: 1, name: 'Soola Admin', email: 'admin@spotify.test', role: 'admin' } },
  'editor@spotify.test': { password: 'Editor@123', user: { id: 2, name: 'Music Editor', email: 'editor@spotify.test', role: 'editor' } },
  'user@spotify.test': { password: 'User@123', user: { id: 3, name: 'Lina Listener', email: 'user@spotify.test', role: 'listener' } },
}

function wait(data) {
  return new Promise((resolve) => setTimeout(() => resolve({ data }), 200))
}

export function isMock() {
  return useMock
}

export function login(email, password) {
  const account = accounts[email]
  if (!account || account.password !== password) {
    return Promise.reject({ response: { status: 422, data: { message: 'Invalid credentials.' } } })
  }
  return wait({ token: 'mock-sanctum-token', user: account.user })
}

export function logout() {
  return wait({ message: 'Logged out.' })
}

export function dashboard() {
  return wait({
    users: 3,
    artists: artists.length,
    albums: 7,
    tracks: tracks.length,
  })
}

export function listTracks(q = '') {
  const query = q.toLowerCase()
  const rows = !query
    ? tracks
    : tracks.filter((t) => t.title.toLowerCase().includes(query) || (t.genre || '').toLowerCase().includes(query) || t.artist.toLowerCase().includes(query))
  return wait(rows)
}

export function createTrack(payload) {
  const row = {
    id: Date.now(),
    title: payload.title,
    genre: payload.genre || '',
    duration_seconds: Number(payload.duration_seconds),
    album_id: Number(payload.album_id) || 1,
    album: payload.album || 'Demo Album',
    artist: payload.artist || 'Demo Artist',
  }
  tracks = [row, ...tracks]
  return wait(row)
}

export function updateTrack(id, payload) {
  tracks = tracks.map((t) => (t.id === id ? { ...t, ...payload } : t))
  return wait(tracks.find((t) => t.id === id))
}

export function deleteTrack(id) {
  tracks = tracks.filter((t) => t.id !== id)
  return wait({ message: 'Deleted.' })
}

export function listArtists() {
  return wait(artists)
}

export function createArtist(payload) {
  const row = { id: Date.now(), name: payload.name, country: payload.country || '', albums_count: 0 }
  artists = [...artists, row]
  return wait(row)
}
