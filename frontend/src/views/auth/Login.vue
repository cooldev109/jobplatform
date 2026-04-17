<script setup>
import { reactive, ref } from 'vue'
import { useRouter, RouterLink } from 'vue-router'
import { useAuthStore } from '../../stores/auth'

const router = useRouter()
const auth = useAuthStore()

const form = reactive({ email: '', password: '' })
const errors = ref({})
const submitting = ref(false)
const generalError = ref('')

async function submit() {
  submitting.value = true
  errors.value = {}
  generalError.value = ''
  try {
    await auth.login(form.email, form.password)
    router.push({ name: 'dashboard' })
  } catch (e) {
    if (e.response?.status === 422) errors.value = e.response.data.errors || {}
    else if (e.response?.status === 401) generalError.value = 'E-mail ou senha incorretos.'
    else generalError.value = 'Erro ao autenticar. Tente novamente.'
  } finally {
    submitting.value = false
  }
}
</script>

<template>
  <div class="auth-shell">
    <div class="auth-card">
      <h1>Entrar no Vagas Agro</h1>
      <p v-if="generalError" class="error-banner">{{ generalError }}</p>

      <form @submit.prevent="submit" novalidate>
        <label>
          E-mail
          <input v-model="form.email" type="email" autocomplete="email" required />
          <span v-if="errors.email" class="field-error">{{ errors.email[0] }}</span>
        </label>

        <label>
          Senha
          <input v-model="form.password" type="password" autocomplete="current-password" required />
          <span v-if="errors.password" class="field-error">{{ errors.password[0] }}</span>
        </label>

        <button :disabled="submitting" type="submit">
          {{ submitting ? 'Entrando…' : 'Entrar' }}
        </button>
      </form>

      <p class="switch">
        Ainda não tem conta?
        <RouterLink to="/register">Cadastre-se</RouterLink>
      </p>
    </div>
  </div>
</template>

<style scoped>
.auth-shell {
  min-height: 100vh;
  display: grid;
  place-items: center;
  padding: 1rem;
}
.auth-card {
  background: #fff;
  padding: 2rem;
  border-radius: 12px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
  width: 100%;
  max-width: 400px;
}
h1 {
  margin: 0 0 1.5rem;
  font-size: 1.5rem;
}
form {
  display: grid;
  gap: 1rem;
}
label {
  display: grid;
  gap: 0.35rem;
  font-size: 0.9rem;
  font-weight: 500;
}
input {
  padding: 0.65rem 0.8rem;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  font-size: 1rem;
}
input:focus {
  outline: 2px solid #2563eb;
  outline-offset: 1px;
}
button {
  padding: 0.75rem 1rem;
  background: #2563eb;
  color: #fff;
  border: none;
  border-radius: 8px;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
}
button:disabled {
  opacity: 0.7;
  cursor: not-allowed;
}
.switch {
  margin-top: 1.5rem;
  font-size: 0.9rem;
  text-align: center;
}
.error-banner {
  background: #fee2e2;
  color: #991b1b;
  padding: 0.75rem;
  border-radius: 8px;
  margin-bottom: 1rem;
  font-size: 0.9rem;
}
.field-error {
  color: #b91c1c;
  font-size: 0.8rem;
  font-weight: 400;
}
</style>
