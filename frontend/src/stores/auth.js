import { defineStore } from 'pinia'
import api from '../lib/api'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: null,
  }),
  getters: {
    isAuthenticated: (state) => !!state.token,
    isCandidate: (state) => state.user?.user_type === 'candidate',
    isCompanyOwner: (state) => state.user?.user_type === 'company_owner',
  },
  actions: {
    hydrate() {
      this.token = localStorage.getItem('auth_token')
      const raw = localStorage.getItem('auth_user')
      this.user = raw ? JSON.parse(raw) : null
    },
    persist() {
      if (this.token) localStorage.setItem('auth_token', this.token)
      else localStorage.removeItem('auth_token')
      if (this.user) localStorage.setItem('auth_user', JSON.stringify(this.user))
      else localStorage.removeItem('auth_user')
    },
    async register(payload) {
      const { data } = await api.post('/register', payload)
      this.user = data.user
      this.token = data.token
      this.persist()
    },
    async login(email, password) {
      const { data } = await api.post('/login', { email, password })
      this.user = data.user
      this.token = data.token
      this.persist()
    },
    async logout() {
      try {
        await api.post('/logout')
      } catch {
        // ignore — clear local state regardless
      }
      this.user = null
      this.token = null
      this.persist()
    },
  },
})
